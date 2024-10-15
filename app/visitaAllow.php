<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Auth;
class visitaAllow extends Model
{
   
    protected $table = 'visita_allow';
    public $timestamps = false;
    
    
    protected $fillable = [
         'cantidad','id','createdDate','createdBy','modifiedBy','modifiedDate','statusId'
         
    ];
    
   public function getActive()
   {
       return $this->where("statusId",1);
   }
   
   
   

}
