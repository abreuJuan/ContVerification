<?php

namespace Vanguard\Http\Controllers\Api;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\configuracionServicio;

class configuracionPatioController extends Controller
{

    public function getByServicioId($id)
    {
        return configuracionServicio::where("statusId",1)->where("servicio_id",$id)->get();
    }

}
