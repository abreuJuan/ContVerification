<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Auth;
class visitantes extends Model
{
   
    protected $table = 'visitantes';
    public $timestamps = false;
    
    
    protected $fillable = [
         'id','modifiedBy','modifiedDate','statusId','createdDate','createdBy','solicitud_id',
         'individuo_id','estatus','rol', 'contenedor_id'        
    ];
    
   public function getActive()
   {
       return $this->where("statusId",1);
   }   
   
   public function paginated($perPage,$search,$solicitudId)
   {
       $query=  $this->query();
       
       $query->join('individuos As i','i.id','=','visitantes.individuo_id')
             ->leftJoin("individuo_tipo_documento AS itd","itd.id","=","i.tipo_documento")
             ->leftJoin("contenedores AS c","c.id","=","visitantes.contenedor_id")
             ->selectRaw("i.*,visitantes.id,visitantes.estatus,visitantes.rol,
                          itd.descripcion AS tipo_documento,c.contenedor");
       
       $query->where("visitantes.statusId",1);
       $query->where("visitantes.solicitud_id",$solicitudId);
       $query->orderBy("c.contenedor","ASC");
       
       if($search)
           $query->where("i.nombre_completo",'like','%'.$search.'%');
       
       $result= $query->paginate($perPage);
        
        if ($search) {
            $result->appends(['search' => $search]);
        }
        
        return $result;
   }

   public static function deleteBySolicitudId($solicitudId)
   {
       return static::where("solicitud_id",$solicitudId)->update([
              "statusId" => 3,             
              "modifiedDate" => \Carbon\Carbon::now()
         ]);
       
   }

   public static function getRepresentanteBySolicitudId( $solicitudId, $rol )
   {
       $query = static::query();

       $query->join("individuos AS i", "i.id", "=", "visitantes.individuo_id" );
       $query->leftJoin("contenedores AS c", "c.id", "=", "visitantes.contenedor_id" );

       $query->where("visitantes.solicitud_id", $solicitudId );
       $query->where("visitantes.rol", $rol );
       $query->where("visitantes.statusId", 1 );

      $query->selectRaw("i.nombre_completo,i.documento,i.tipo_documento, visitantes.id,
                        visitantes.estatus, visitantes.rol, c.contenedor");

      return $query->get();
   }
   
}
