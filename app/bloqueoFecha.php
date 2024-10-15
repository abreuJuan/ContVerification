<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;

class bloqueoFecha extends Model
{
   
    protected $table = 'bloqueo_fecha_servicio_espacio';
    public $timestamps = false;
    
    
    protected $fillable = [
         'id','fecha','createdDate','servicio_id','timeblocked'               
    ];
    


   public static function blockByServicioId($fecha,$servicio_id,$time)
   {        
       return static::create(["fecha" => $fecha,"timeblocked" => $time,"servicio_id" => $servicio_id,"createdDate" => Carbon::now()]);
   }
   
   public static function dateIsBlocked($fecha,$servicio_id,$time)
   {
       return static::where("fecha",$fecha)->where("timeblocked",$time)->where("servicio_id",$servicio_id)->count() > 0;
   }
}
