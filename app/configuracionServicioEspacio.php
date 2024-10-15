<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Auth;
class configuracionServicioEspacio extends Model
{
   
    protected $table = 'configuracion_servicio_espacio';
    public $timestamps = false;
    
    
    protected $fillable = [
         'servicio_id','id','lun','mar','mie','jue','vie','sab','dom','modifiedBy','modifiedDate','statusId','interval',
         'lun_hora_inicio','lun_hora_fin','mar_hora_inicio','mar_hora_fin','mie_hora_inicio','mie_hora_fin','jue_hora_inicio','jue_hora_fin',
         'vie_hora_inicio','vie_hora_fin','sab_hora_inicio','sab_hora_fin','dom_hora_inicio','dom_hora_fin',        
    ];
    
   public function getActive()
   {
       return $this->where("statusId",1);
   }
   
   public function getEspacioByServicioId($id)
   {
     return  $this->where("servicio_id",$id)->where("statusId",1);
   }
   
   
   public static function getActualDay()
   {
       $day=['lun','mar','mie','jue','vie','sab','dom'];
       return $day[date("w")];       
   }
   
   public static function getDayWeek($index)
   {
       $day=['dom','lun','mar','mie','jue','vie','sab'];
       return $day[$index];       
   }
   
   public static function hasInterval($id)
   {
       return static::where("servicio_id",$id)->where("statusId",1)->whereNull("interval")->count()>0?true:false;
   }
   
   public static function getInterval($id)
   {
       $interval= static::where("servicio_id",$id)->limit(1)->get();
       
       return isset($interval[0]["interval"])?$interval[0]["interval"]:0;
       
   }
   
   public static function getEspacioByTimeAndDay($id,$day)
   {
     return  static::where("servicio_id",$id)->where("statusId",1)->selectRaw("*,$day"."_hora_inicio"." AS current_hora_inicio,"
             . "$day"."_hora_fin"." AS current_hora_fin")->first();
   }
   

}
