<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use Vanguard\individuos;
use Vanguard\tipoDocumento;

class solicitanteController extends Controller
{

 

    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('session.database', ['only' => ['sessions', 'invalidateSession']]);
    }


    public function view(individuos $individuo)
    {

        $tipoDocumento = tipoDocumento::getById($individuo->tipo_documento);
        
        return view('solicitante.view', compact('individuo','tipoDocumento'));
    }    




}
