<?php

namespace Vanguard\Http\Controllers\Api;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\mercancia;


class MercanciaController extends Controller
{

    public function getMercanciaByDate($date)
    {
        
       $dayOfWeek=date("w",strtotime($date));
       
       if($dayOfWeek==6 || $dayOfWeek==0)
       {
          return mercancia::mercanciaNoInSab();
       }
       
       return mercancia::where("statusId",1)->get();
    }

   
}
