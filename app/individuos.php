<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Auth;
class individuos extends Model
{
   
    protected $table = 'individuos';
    public $timestamps = false;
    
    
    protected $fillable = [
         'id','modifiedBy','modifiedDate','statusId','createdDate','createdBy','nombre_completo',
         'documento','telefono','email','tipo_documento'      
    ];
    
   public function getActive()
   {
       return $this->where("statusId",1);
   }  
   
   public static function existIndividuoByDomuneto($documento)
   {
       return static::where("documento",$documento)->count()>0?true:false;
   }
   
   public static function getIdByDocumento($documento)
   {
       $individuo=static::where("documento",$documento)->limit(1)->get();
       return $individuo[0]["id"];
   }
   
   public static function getIndividuoByDocumento($documento)
   {
       return static::where("documento",$documento)->get()->first();     
   }
   
   public static function getIndividuoById($id)
   {
       return static::where("id",$id)->get()->first();
   }
   
   public function paginated($perPage,$search)
   {
       $query=  $this->query();
       
   
       
       $query->where("statusId",1);
       
       if($search)
           $query->where("nombre_completo",'like','%'.$search.'%');
       
       $result= $query->paginate($perPage);
        
        if ($search) {
            $result->appends(['search' => $search]);
        }
        
        return $result;
   }
   
}
