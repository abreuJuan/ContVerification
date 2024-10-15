<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Vanguard\mercancia;

class mercanciaBloqueada extends Model
{
   
    protected $table = 'mercancias_bloqueda_dia';
    public $timestamps = false;
    
    
    protected $fillable = [
         'mercancia_id','id','modifiedBy','modifiedDate','createdDate','createdBy','statusId'
         
    ];
    
   public function getActive()
   {
       return $this->where("statusId",1);
   }
   
   public function getMercanciasPaginated($perPage,$search)
   {
       $mercancia=  $this->query();
       
       $mercancia->leftJoin("mercancias AS s","s.id","=","mercancias_bloqueda_dia.mercancia_id")
                 ->where("mercancias_bloqueda_dia.statusId",1)
                 ->selectRaw("mercancias_bloqueda_dia.*,s.descripcion");
       
       if($search)
          $mercancia->where("s.descripcion","like","%".$search."%");
       
       $result=$mercancia->paginate($perPage);
       
       if($search)
           $result->appends(["search"=>$search]);
       
       return $result;
       
   }
   
   public function getActiveMercancia()
   {
       return mercancia::whereRaw("id NOT IN (select mercancia_id from mercancias_bloqueda_dia where statusId=1)")
              ->where("statusId",1)->get();
   }
   

}
