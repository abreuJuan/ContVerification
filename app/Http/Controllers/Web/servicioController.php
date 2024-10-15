<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Vanguard\servicio;
use Auth;

class servicioController extends Controller
{      

    private $servicio;

    public function __construct()
    {
      $this->middleware('auth');
      $this->middleware('session.database', ['only' => ['sessions', 'invalidateSession']]);
        
      $this->servicio=new servicio();
    }
    
    public function index()
    {
        $servicios=$this->servicio->getActive();
        
        return view('settings.servicio',compact("servicios"));
    }

  
}
