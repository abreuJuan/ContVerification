<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Auth;
class configuracionServicio extends Model
{
   
    protected $table = 'configuracion_servicio';
    public $timestamps = false;
    
    
    protected $fillable = [
         'servicio_id','id','active','modifiedBy','modifiedDate','statusId'
         
    ];
    
   public function getActive()
   {
       return $this->where("statusId",1);
   }
   
   public static function allowJustInYard($clasificacionId)
   {
       return static::where("statusId",1)->where("servicio_id",$clasificacionId)->get()->first()->active == 1;
   }
}
