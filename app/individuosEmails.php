<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Auth;
class individuosEmails extends Model
{
   
    protected $table = 'individuos_emails';
    public $timestamps = false;
    
    
    protected $fillable = [
         'email','id','statusId','createdDate','individuo_id'
         
    ];
   

    public static function existIndividuoEmail($email,$individuo_id)
    {
        return static::where("email",$email)->where("individuo_id",$individuo_id)->count()>0?true:false;
    }
}
