<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use Vanguard\solicitud;
use Vanguard\status;
use Vanguard\servicio;
use Vanguard\mercancia;
use Carbon\Carbon;

class solicitudController extends Controller
{

    private $solicitud;
    private $condiciones = array( "Verificación",
        "Verificación y despacho",
        "Descarga en puerto",
        "Dejado a piso"
   //     "Cancelar requerimiento"
      );

    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('session.database', ['only' => ['sessions', 'invalidateSession']]);
       $this->solicitud= new solicitud();

    }


    public function index(\Vanguard\Http\Requests\Solicitud\FilterRequest $input)
    {
       $fechaDesde=Input::get("fechaDesde")?Input::get("fechaDesde"):date("Y-m")."-01";
       $fechaHasta=Input::get("fechaHasta")?Input::get("fechaHasta"):date("Y-m-d");
               
       if($fechaDesde>$fechaHasta)
        return redirect()->route('solicitud.index')
                ->withErrors(trans('app.date_greater'));  
        
        
        $solicitudes = $this->solicitud->paginated(
            20,
            Input::get('search'),
            $fechaDesde." 00:00:00",
            $fechaHasta." 23:59:00",
            $input
        ); 
        
        $countContenedores = $this->solicitud->countContenedores(
            Input::get('search'),
            $fechaDesde." 00:00:00",
            $fechaHasta." 23:59:00",
            $input
        ); 
        
        
        $statuses = status::getSolicitudStatusSelect();
        
        $condiciones = $this->condiciones;        
        $servicios = servicio::getServicio();
        //dd($servicios);
        $mercancias = mercancia::getMercancia();
        $estados = status::getAllSolicitudStatusSelect();
        
        return view('solicitud.list', compact('solicitudes','fechaDesde','fechaHasta','statuses','condiciones','servicios','mercancias','estados',"countContenedores"));
    }    
    
    public function export(\Vanguard\Http\Requests\Solicitud\FilterRequest $input)
    {
       if(Auth()->user()->hasPermission("solicitud.export"))
       {
           
       $fechaDesde=Input::get("fechaDesde")?Input::get("fechaDesde"):date("Y-m")."-01";
       $fechaHasta=Input::get("fechaHasta")?Input::get("fechaHasta"):date("Y-m-d");
               
       if($fechaDesde>$fechaHasta)
        return redirect()->route('solicitud.index')
                ->withErrors(trans('app.date_greater'));  
        
        
        $solicitudes = $this->solicitud->export(            
            Input::get('search'),
            $fechaDesde." 00:00:00",
            $fechaHasta." 23:59:00",
            $input
        ); 
        
          $title = [];
          $title [] = ["BL","Servicio","Mercancía","Consignatario","Condición","Solicitud escanear","Fecha verificar",
              "Hora verificar","Fecha creación solicitud","Cantidad contenedores","Cantidad visitantes","Estado"];
        return  download($title, $solicitudes, "solicitudes generadas", ";");
       }
       
       return \Redirect::back()->withErrors(trans("app.no_allow_to_export"));
    }


    public function delete(solicitud $solicitud)
    {        
        $solicitud->statusId=6;
        $solicitud->modifiedBy = Auth()->user()->id;
        $solicitud->modifiedDate = Carbon::now();
        $solicitud->save();      

        return redirect()->route('solicitud.index')
            ->withSuccess(trans('app.solicitud_deleted_message'));
    }
    
    public function changeStatus()
    {
        $solicitud_selecionadas = Input::get('solicitud_selecionadas');
        
        if(sizeof($solicitud_selecionadas) < 1)            
        return redirect()->route('solicitud.index')
            ->withErrors(trans('app.error_selection'));
        
        for($i =0; $i < sizeof($solicitud_selecionadas); $i++ )
        {
            solicitud::changeStatus($solicitud_selecionadas[$i] , input::get('statusId'));
        }
       
       return redirect()->back()->withInput()
            ->withSuccess(trans('app.solicitud_change_status'));
        
    }
    
    public function showComentario(solicitud $solicitud)
    {        
        
        
        return view("solicitud.comentario",compact("solicitud"));
    }

}
