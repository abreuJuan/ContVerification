<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use Vanguard\contenedores;

class contenedoresController extends Controller
{

    private $contenedores;


    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('session.database', ['only' => ['sessions', 'invalidateSession']]);
       
       $this->contenedores= new contenedores();

    }


    public function index($solicitudId)
    {
        $contenedores = $this->contenedores->paginated(
            20,
            Input::get('search'),
            $solicitudId
        );      
      
        return view('contenedores.list', compact('contenedores'));
    }    


    public function delete(contenedores $contenedores)
    {        
        $contenedores->statusId=3;
        $contenedores->save();      

        return redirect()->back()
            ->withSuccess(trans('app.contendor_deleted_message'));
    }

}
