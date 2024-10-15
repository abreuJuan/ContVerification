<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Auth;
class individuoTelefonos extends Model
{
   
    protected $table = 'individuos_telefonos';
    public $timestamps = false;
    
    
    protected $fillable = [
         'telefono','id','statusId','createdDate','individuo_id'
         
    ];
   

    public static function existIndividuoTelefono($telefono,$individuo_id)
    {
        return static::where("telefono",$telefono)->where("individuo_id",$individuo_id)->count()>0?true:false;
    }

    public static function getIndividuoTelefonoById( $individuo_id )
    {
        return static::where("individuo_id",$individuo_id)->get()->first();
    }
}
