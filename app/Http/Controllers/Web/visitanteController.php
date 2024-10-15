<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use Vanguard\solicitud;
use Vanguard\visitantes;

class visitanteController extends Controller
{

    private $visitantes;


    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('session.database', ['only' => ['sessions', 'invalidateSession']]);
       $this->visitantes= new visitantes();

    }


    public function index($solicitudId)
    {
       
        $visitantes = $this->visitantes->paginated(
            20,
            Input::get('search'),
            $solicitudId
        );     
        
        $hash = solicitud::getHash($solicitudId);
        $solicitud = solicitud::getById($solicitudId);
      
        return view('visitante.list', compact('visitantes','hash','solicitud'));
    }    

    public function entrada( $hash )
    {
        $solicitud = solicitud::getByHash($hash);

        solicitud::updateEntrada($solicitud->id);

        return redirect()->back()->withSuccess("La entrada de los visitantes ha sido registrada exitosamente");
    }


    public function delete(visitantes $visitantes)
    {        
        $visitantes->statusId=3;
        $visitantes->save();      

        return redirect()->back()
            ->withSuccess(trans('app.visitante_deleted_message'));

    }

}
