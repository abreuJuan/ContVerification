<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Auth;

class confDiaFeriado extends Model
{
   
    protected $table = 'conf_dias_feriados';
    public $timestamps = false;
    
    
    protected $fillable = [
         'id','modifiedBy','modifiedDate','statusId','createdDate','createdBy','hollydate','descripcion'                
    ];
    
   public function getActive()
   {
       return $this->where("statusId",1);
   }   
   
   public function paginated($perPage,$search)
   {
       $query =  $this->query();
       
       $query->where("statusId",1)->selectRaw("*");
       
       if($search)
          $query->where("hollydate","like","%".$search."%");
       
       $result=$query->paginate($perPage);
       
       if($search)
           $result->appends(["search"=>$search]);
       
       return $result;
       
   }
   
   public static function isHollyDate($fecha)
   {
       return static::where('hollydate',$fecha)->where("statusId",1)->count() > 0;
   }
   
}
