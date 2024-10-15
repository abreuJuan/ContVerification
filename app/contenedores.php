<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Auth;
class contenedores extends Model
{
   
    protected $table = 'contenedores';
    public $timestamps = false;
    
    
    protected $fillable = [
         'solicitud_id','id','modifiedBy','modifiedDate','statusId','createdDate','createdBy',
         'contenedor','estatus','tipo'
    ];
    
   public function getActive()
   {
       return $this->where("statusId",1);
   }   
   
   public function paginated($perPage,$search,$solicitudId)
   {
       $query=  $this->query();      
       
       $query->where("statusId",1);
       $query->where("solicitud_id",$solicitudId);
       
       if($search)
           $query->where("contenedor",'like','%'.$search.'%');
       
       $result= $query->paginate($perPage);
        
        if ($search) {
            $result->appends(['search' => $search]);
        }
        
        return $result;
   }

   public static function getContenedoresBySolicitudId( $solicitudId )
   {
       return static::where("solicitud_id",$solicitudId)->get();
   }

   public static function getContenedorIdBySolicitudIdAndContainer( $solicitudId, $container )
   {
       return static::where("solicitud_id",$solicitudId)->where("contenedor",$container)->first();
   }
   

}
