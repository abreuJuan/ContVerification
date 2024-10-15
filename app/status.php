<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Auth;
class status extends Model
{
   
    protected $table = 'status';
    public $timestamps = false;
    
    
    protected $fillable = [
         'id','description','createdDate','createdBy'       
    ];
    
   public function getActive()
   {
       return $this->where("statusId",1);
   }   
   
  public static function getSolicitudStatusSelect()
  {
      return static::where(function($w){
          $w->where("id",5);
          $w->orWhere("id",6);
          $w->orWhere("id",4);
      })->pluck('description','id');
  }
  
    public static function getAllSolicitudStatusSelect()
  {
      return static::all(['description','id']);
  }
}
