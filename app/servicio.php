<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Vanguard\Support\Enum\ServicioEnum;
use Carbon\Carbon;

class servicio extends Model
{
   
    protected $table = 'servicio';
    public $timestamps = false;
    
    
    protected $fillable = [
         'descripcion','id','statusId','createdBy','createdDate','allow_scanner','modifiedDate','modifiedBy'
         
    ];
   
   public static function getById( $servicioId )
   {
       return static::find($servicioId);
   }
   
   public function getActive()
   {
       return servicio::where("statusId",1)->get();
   }
   
   public static function getServicioSelect()
   {
       return static::where('statusId',1)->pluck("descripcion","id");
   }

   public static function getAllowScanner( $servicio_id )
   {
      return static::where("id",$servicio_id)->get()->first()->allow_scanner;
   }
   
   public static function getServicio()
   {
       return static::where('statusId',1)->select(["descripcion","id"])->get();
   }

   public static function enableAllowScanner( $servicio_id )
   {
       return static::where("id",$servicio_id)->update([
           "allow_scanner" => ServicioEnum::ANABLE_ALLOW_SCANNER,
           "modifiedDate" => Carbon::now(),
           "modifiedBy" => Auth::id()
       ]);
   }

   public static function disableAllowScanner( $servicio_id )
   {
       return static::where("id",$servicio_id)->update([
           "allow_scanner" => ServicioEnum::DISABLE_ALLOW_SCANNER,
           "modifiedDate" => Carbon::now(),
           "modifiedBy" => Auth::id()
       ]);
   }
}
