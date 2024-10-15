<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use Vanguard\confDiaFeriado;

class confDiaFeriadoController extends Controller
{

    private $confDiaFeriado;


    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('session.database', ['only' => ['sessions', 'invalidateSession']]);
       $this->confDiaFeriado= new confDiaFeriado();

    }


    public function index()
    {
        $confDiaFeriados= $this->confDiaFeriado->paginated(
            20,
            Input::get('search')            
        );      
      
        return view('settings.conf_dia_feriado_list', compact('confDiaFeriados'));
    } 
    
    public function store(\Vanguard\Http\Requests\settings\DiaFeriadoRequest $request)
    {
        $data=$request->all();
        $data["createdDate"]=date("Y-m-d H:i:s");
        $data["statusId"]=1;
        $data["createdBy"]=Auth::id();
        
        confDiaFeriado::create($data);
           
        return redirect()->route('conf_dia_feriado.index')
            ->withSuccess(trans('app.conf_dia_feriado_created_message'));      

    }


    public function delete(confDiaFeriado $confDiaFeriado)
    {        
        $confDiaFeriado->statusId=3;
        $confDiaFeriado->modifiedDate = date("Y-m-d H:i:s");
        $confDiaFeriado->modifiedBy = Auth::id();
        $confDiaFeriado->save();  
        

        return redirect()->back()
            ->withSuccess(trans('app.conf_dia_feriado_deleted_message'));

    }

}
