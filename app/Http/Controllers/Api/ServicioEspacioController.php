<?php

namespace Vanguard\Http\Controllers\Api;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\configuracionServicioEspacio;
use Vanguard\solicitud;
use Vanguard\bloqueoFecha;
use Carbon\Carbon;
use Vanguard\confDiaFeriado;
use Vanguard\servicio;

class ServicioEspacioController extends Controller
{

    
    
    public function getByServicioId($configuracionServicio)
    {    
        $allow_scanner = servicio::getAllowScanner( $configuracionServicio );

        $data=Array(
            "setting"=> configuracionServicioEspacio::where("servicio_id",$configuracionServicio)
                        ->where("statusId",1)->selectRaw("*,".configuracionServicioEspacio::getActualDay()." AS actualDay,
                        $allow_scanner AS allow_scanner")->get()
            );
       
       return $data;
    }
    
    public function getSelectedDay($date)
    {
        $dateOfWeek=date("w",strtotime($date));
        $day=  configuracionServicioEspacio::getDayWeek($dateOfWeek);
        
        return $day;
    }
    
    public function getAvailableSpace(Request $request)
    {
        
        
        $dateOfWeek=date("w",strtotime($request->get("date")));
        $day=  configuracionServicioEspacio::getDayWeek($dateOfWeek);
        
        $time = date("H:i");
        
        if( $this->requestExistForThisDay($request) )
            return Array("day"=>$day,"amount"=> 0,"exist_this_date"=> true);        
        
        
        // si es dia feriado 
        if(confDiaFeriado::isHollyDate($request->get("date")))
        return Array("day"=>$day,"amount"=> 0,"hollydate"=> true);
        
        if( Carbon::parse( $request->get("date") )->format("Y-m-d") < Carbon::now()->format("Y-m-d") )
        return Array("day"=>$day,"amount"=> 0);

        $cantidadSolicitudSetting = configuracionServicioEspacio::getEspacioByTimeAndDay($request->get("servicio_id"), $day);
        $cantidadActual = $cantidadSolicitudSetting->{$day};

        if(!configuracionServicioEspacio::hasInterval($request->get("servicio_id")))
        {
            if(empty($request->get("hour")))
                return -1;
            
            $hora=configuracionServicioEspacio::getInterval($request->get("servicio_id"));

            $cantidadRestante = $cantidadActual - solicitud::amountContaineryDateAndHours($request->get("date"), $request->get("hour"));
            
            $datos = Array("day"=>$day,"amount"=> $cantidadRestante );
            
            if( $this->todayIsClose($request->get("servicio_id"), $time) && $this->hasSelectedNextDayOfActualDay($request->get("date") ))
            return Array("day"=>$day,"amount"=> 0, "outoftime"=>true );    
            
            //$this->blockDate($request->get("servicio_id"), $day,$request->get("date"), $time);
            
           // if(bloqueoFecha::dateIsBlocked( $request->get("date") , $request->get("servicio_id") , $time ) )
           //    $datos=Array("day"=>$day,"amount"=> 10000000000);
                    
            return $datos;            
        }
      
        
        
        

        //dd(solicitud::amountContaineryByServicioIdDate($request->get("date"),$request->get("servicio_id")), $request->get("date") );
        $cantidadRestante = $cantidadActual - solicitud::amountContaineryByServicioIdDate($request->get("date"),$request->get("servicio_id"));
        $datos = Array("day"=>$day,"amount"=> $cantidadRestante );
//dd($this->todayIsClose($request->get("servicio_id"), $time));
        //dd($request->get("date"),$request->get("servicio_id") ,solicitud::amountContaineryByServicioIdDate($request->get("date"),$request->get("servicio_id")));
       // && $this->hasSelectedNextDayOfActualDay($request->get("date") )
        if( $this->todayIsClose($request->get("servicio_id"), $time) && $this->hasSelectedNextDayOfActualDay($request->get("date") )
 )
          return Array("day"=>$day,"amount"=> 0, "outoftime"=>true);        
       
       // $this->blockDate($request->get("servicio_id"), $day,$request->get("date"), $time);
        
         
       /* if(bloqueoFecha::dateIsBlocked( $request->get("date") , $request->get("servicio_id") , $time ) )
        $datos=Array("day"=>$day,"amount"=> 10000000000);*/
        
      //  dd($isTimeNoAvailable);
        return $datos;
    }
    
    

    public function isTimeAvailable($id,$day,$time)
    {
        $espacioSetting = configuracionServicioEspacio::getEspacioByTimeAndDay($id,$day);
        
        
       // $time = date("H:i");
       
       // dd($espacioSetting->current_hora_inicio,$time.":00", $time.":00" >= $espacioSetting->current_hora_inicio,$day);
       
        if( $time.":00" >= $espacioSetting->current_hora_inicio && $time.":59" <= $espacioSetting->current_hora_fin )
            return true;        
        
        
        return false;
    }
    
    public function isFull($id,$day,$time)
    {
       $espacioSetting = configuracionServicioEspacio::getEspacioByTimeAndDay($id,$day);
       // dd($espacioSetting);
        
       // $time = date("H:i");
       
        //dd($espacioSetting->current_hora_inicio,$time.":00", $time.":00" >= $espacioSetting->current_hora_inicio,$day);
       
       if($id !=2) return true;
       
        if( ($time.":00" >= $espacioSetting->current_hora_inicio && $time.":59" <= $espacioSetting->current_hora_fin ) )
            return true;        
        
        
        return false;
    }
    
    public function todayIsClose($id,$time)
    {
        return !$this->isTimeAvailable($id, $this->actualDate(), $time);
    }
    
    public function hasSelectedNextDayOfActualDay($fecha)
    {
        return Carbon::parse($fecha)->format("y-m-d") == Carbon::now()->tomorrow()->format("y-m-d");
    }
    
    public function blockDate($id,$day,$fecha,$time)
    {
        $isTimeNoAvailable = $this->isFull($id, $day,$time);
        
        if($isTimeNoAvailable)
         return true;
        
        
        if($this->clientSelectTomorrow($fecha))
        {
           
            bloqueoFecha::blockByServicioId(Carbon::now()->tomorrow(),$id,$time);            
            
            $dayActual = $this->actualDate();
            if($dayActual == "vie" || $dayActual == "sab")
            {
              bloqueoFecha::blockByServicioId(Carbon::now()->tomorrow()->addDays(1),$id,$time);
            }

        }  
        
      
        return false;
        
        
    }
    
    public function clientSelectTomorrow($fecha)
    {       
        return Carbon::parse($fecha) == Carbon::now()->tomorrow();
    }
    
    public function actualDate()
    {
       $dateOfWeek = date("w",strtotime(Carbon::now()));
       $day =  configuracionServicioEspacio::getDayWeek($dateOfWeek);
       
       return $day;
    }
    
    
    public function requestExistForThisDay($request)
    {
        $solicitud = solicitud::getSolicitudByBlAndFechaVerificar($request->get("bl"), $request->get("date"));
        
        return isset($solicitud->id);
    }
}
