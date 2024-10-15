<?php

namespace Vanguard;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;
use DB;

class solicitud extends Model
{
   
    protected $table = 'solicitud';
    public $timestamps = false;
    
    
    protected $fillable = [
         'bl','id','modifiedBy','modifiedDate','statusId','createdDate','createdBy','servicio_id',
         'fecha_verificar','hora_verificar','individuo_id','condicion','mercancia_id','consignatario',
         'hash_to_cancel','observacion','email','allow_scanner','entrada'         
    ];
    
   public function getActive()
   {
       return $this->where("statusId",1);
   }   

   public static function getHash( $solicitudId )
   {
       $solicitud = static::find($solicitudId);
       return $solicitud->hash_to_cancel;
   }

   public static function getById( $solicitudId )
   {
       return static::find($solicitudId);
   }

   
   public static function amountContaineryDateAndHours($date,$hours)
   {
       $solicitud= static::whereNotIn("solicitud.statusId",[2,3,6])->where("solicitud.fecha_verificar",$date)
               ->join("contenedores AS c","c.solicitud_id","=","solicitud.id")->where("c.statusId",1)
               ->where("hora_verificar",$hours)
               ->get()->count();
       
       return $solicitud;
   }
   
   public static function amountContaineryDate($date)
   {
       
       $solicitud= static::whereNotIn("solicitud.statusId",[2,3,6])->where("solicitud.fecha_verificar",$date)
               ->join("contenedores AS c","c.solicitud_id","=","solicitud.id")->where("c.statusId",1)
               ->get()->count();
       
       return $solicitud;
   }
   
   public static function amountContaineryByServicioIdDate($date,$servicio_id)
   {
       
       $solicitud= static::whereNotIn("solicitud.statusId",[2,3,6])->where("solicitud.fecha_verificar",$date)
               ->join("contenedores AS c","c.solicitud_id","=","solicitud.id")->where("c.statusId",1)->where("solicitud.servicio_id",$servicio_id)
               ->get()->count();
       
       return $solicitud;
   }
   
   public static function changeStatus($solicitudId , $statusId)
   {
       return static::where('id',$solicitudId)->update(["statusId" => $statusId,"modifiedBy" => Auth()->user()->id,"modifiedDate" => Carbon::now()]);
   }
   
   public static function joinFilterQueries($query,$input,$search,$fechaDesde,$fechaHasta)
   {
       $query->leftJoin("servicio AS s","s.id","=","solicitud.servicio_id")
             ->leftJoin('individuos As i','i.id','=','solicitud.individuo_id')
             ->leftJoin('mercancias As m','m.id','=','solicitud.mercancia_id')
             ->leftJoin('status As st','st.id','=','solicitud.statusId')
             ->leftJoin('users As u','u.id','=','solicitud.modifiedBy')
             ->leftJoin("contenedores AS con","con.id","=",
               DB::raw("(select id from contenedores where solicitud_id = solicitud.id AND statusId = 1 AND contenedor like '%".$search."%' order by id desc limit 1)"));
       
       $query->where(function($q){
           $q->where("solicitud.statusId",1);
           $q->orWhere("solicitud.statusId",5);
           $q->orWhere("solicitud.statusId",6);
           $q->orWhere("solicitud.statusId",7);
           $q->orWhere("solicitud.statusId",4);
       });
       
       if($fechaDesde && $fechaHasta)
        $query->whereBetween("solicitud.fecha_verificar",[$fechaDesde,$fechaHasta]);
       
       
       
       if($search)
       {
           $query->where(function($q) use($search){
               $q->where("solicitud.bl",'like','%'.$search.'%');
               $q->orWhere("i.documento",'like',"%{$search}%");
               $q->orWhere("solicitud.consignatario",'like',"%{$search}%");
               $q->orWhere("i.nombre_completo",'like',"%{$search}%"); 
               $q->orWhere("con.contenedor",'like',"%{$search}%"); 
           });
          // $query->where("solicitud.bl",'like','%'.$search.'%');
       }
      // dd($input->all());
      //  dd($query->toSql());
       if($input->get("servicio") && $input->get("servicio"))
           $query->where("solicitud.servicio_id",$input->get("servicio"));

       if( $input->get("allow_scanner") )
           $query->where("solicitud.allow_scanner","Si");
       
       if($input->get("mercancia") && $input->get("mercancia"))
           $query->where("solicitud.mercancia_id",$input->get("mercancia"));
       
       if($input->get("condicion") && $input->get("condicion"))
           $query->where("solicitud.condicion",$input->get("condicion"));
       
       if($input->get("statusId") && $input->get("statusId"))
           $query->where("solicitud.statusId",$input->get("statusId"));
       
       return $query;
   }
   
   
   public function countContenedores($search,$fechaDesde,$fechaHasta,$input)
   {
       $query=  $this->query();  
       
       $query = static::joinFilterQueries($query,$input,$search,$fechaDesde,$fechaHasta);
       
       $result=    $query->selectRaw(
                      "sum((select count(*) from contenedores where solicitud_id=solicitud.id AND statusId=1)) As cantidadContenedores"
                     )
             ->get()->first();
       
       return isset($result->cantidadContenedores) && !empty($result->cantidadContenedores) ? $result->cantidadContenedores : 0;
   }
   
   public function paginated($perPage,$search,$fechaDesde,$fechaHasta,$input)
   {
       
       
       $query=  $this->query();  
       
       $query = static::joinFilterQueries($query,$input,$search,$fechaDesde,$fechaHasta);
       
             $query->selectRaw("solicitud.*,"
                     . " i.nombre_completo,i.documento,IFNULL(solicitud.email,i.email) AS email,i.telefono,"
                     . "s.descripcion As servicio,m.descripcion As mercancia,"
                     . "(select count(*) from contenedores where solicitud_id=solicitud.id AND statusId=1) As cantidadContenedores,"
                     . "(select count(*) from visitantes where solicitud_id=solicitud.id And statusId=1) AS cantidadVisitantes,"
                     . "st.description as estado,IFNULL(u.username,'Cliente') AS modificadoPor")
             ->orderBy("solicitud.id",'DESC');
       
      

       
       $result= $query->paginate($perPage);
        
        if ($search) {
            $result->appends(['search' => $search]);
        }
        
        if ($input->get("servicio") && $input->get("servicio")) {
            $result->appends(['servicio' => $input->get("servicio")]);
        }
        
        if ($input->get("mercancia") && $input->get("mercancia")) {
            $result->appends(['mercancia' => $input->get("mercancia")]);
        }
        
        if($input->get("condicion") && $input->get("condicion")) {
            $result->appends(['condicion' => $input->get("condicion")]);
        }
        
        if ($input->get("statusId") && $input->get("statusId")) {
            $result->appends(['statusId' => $input->get("statusId")]);
        }
        
        if ($fechaHasta) {
            $result->appends(['fechaHasta' => $fechaHasta]);
        }

        if ($fechaDesde) {
            $result->appends(['fechaDesde' => $fechaDesde]);
        }
        
        return $result;
   }
   
   
   public function export($search,$fechaDesde,$fechaHasta,$input)
   {     
       $query=  $this->query();  
       
       $query = static::joinFilterQueries($query,$input,$search,$fechaDesde,$fechaHasta);
       
             $query->selectRaw("solicitud.bl,"
                     . "s.descripcion As servicio,"
                     . "m.descripcion As mercancia,"
                     . "solicitud.consignatario AS consignatario,"
                     . "solicitud.condicion,"
                     . "solicitud.allow_scanner,"
                     . "solicitud.fecha_verificar,"
                     . "solicitud.hora_verificar,"
                     . "solicitud.createdDate,"                                   
                     . "(select count(*) from contenedores where solicitud_id=solicitud.id AND statusId=1) As cantidadContenedores,"
                     . "(select count(*) from visitantes where solicitud_id=solicitud.id And statusId=1) AS cantidadVisitantes,"
                     . "st.description as estado")
             ->orderBy("solicitud.id",'DESC');
               
        return $query->get()->toArray();
   }
   
   public static function cancelByHash($hash)
   {
       return static::where("hash_to_cancel",$hash)->update(["modifiedDate" => Carbon::now(),"statusId" => 6,"modifiedBy" => null]);
   }
   
   public static function cancelByBl($bl)
   {
       return static::whereIn("statusId",[1,4,5])->where("bl",$bl)->update(["modifiedDate" => Carbon::now(),"statusId" => 6]);
   }
   
   public static function getByHash($hash)
   {
       return static::where("hash_to_cancel",$hash)->get()->first();
   }
   
   public static function getSolicitudByBlAndFechaVerificar($bl,$fecha_verificar)
   {
       return static::where("bl",$bl)->where("fecha_verificar",$fecha_verificar)->where("statusId","!=",6)->get()->first();
   }
   
   public static function getSolicitudByBlBiggerThanToday($bl)
   {
       return static::where("bl",$bl)->where("fecha_verificar",">", Carbon::now()->format("Y-m-d"))->
               whereIn("statusId",[1,4,5])->get()->first();
   }

   public static function updateCondicion( $condicion, $solicitud_id )
   {
       return static::where("id", $solicitud_id )->update([
           "condicion" => $condicion,
           "modifiedDate" => Carbon::now(),
           "modifiedBy" => null
       ]);
   }

   public static function updateEntrada(  $solicitud_id )
   {
       return static::where("id", $solicitud_id )->update([
           "entrada" => 1,
           "modifiedDate" => Carbon::now(),
           "modifiedBy" => Auth::user()->id
       ]);
   }



}
