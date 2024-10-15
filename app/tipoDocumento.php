<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Auth;
class tipoDocumento extends Model
{
  
    protected $table = 'individuo_tipo_documento';
    public $timestamps = false;
    
    
    protected $fillable = [
         'id','descripcion','createdDate','createdBy','statusId','modifiedDate','modifiedBy'       
    ];
    
   public function getActive()
   {
       return $this->where("statusId",1);
   }   
   
   public static function getById($id)
   {
       return static::where("id" , $id)->get()->first();
   }

}
