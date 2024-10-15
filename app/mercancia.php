<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Auth;
class mercancia extends Model
{
   
    protected $table = 'mercancias';
    public $timestamps = false;
    
    
    protected $fillable = [
         'descripcion','id','modifiedBy','modifiedDate','statusId','createdDate','createdBy'         
    ];
    
   public function getActive()
   {
       return $this->where("statusId",1);
   }   
   
   public static function getMercanciaSelect()
   {
       return static::where("statusId",1)->pluck("descripcion","id");
   }
   
   public static function getMercancia()
   {
       return static::where("statusId",1)->get(["descripcion","id"]);
   }
   
   public function paginated($perPage,$search)
   {
       $query=  $this->query();      
       
       $query->where("statusId",1);
       
       if($search)
           $query->where("descripcion",'like','%'.$search.'%');
       
       $result= $query->paginate($perPage);
        
        if ($search) {
            $result->appends(['search' => $search]);
        }
        
        return $result;
   }
   
   public static function mercanciaNoInSab()
   {
      return mercancia::whereRaw("id NOT IN (select mercancia_id from mercancias_bloqueda_dia where statusId=1)")
              ->where("statusId",1)->get();
   }
   
   public static function getMercanciaById($id)
   {
       return static::where("id",$id)->get()->first();
   }
}
