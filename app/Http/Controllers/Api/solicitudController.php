<?php

namespace Vanguard\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Vanguard\Http\Controllers\Controller;
use Vanguard\solicitud;
use Vanguard\contenedores;
use Vanguard\visitantes;
use Vanguard\individuos;
use Vanguard\configuracionServicio;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Vanguard\mercancia;
use Vanguard\individuosEmails;
use Carbon\Carbon;
use Vanguard\individuoTelefonos;

use \BaconQrCode\Renderer\Image\Png;
use \BaconQrCode\Writer;
use Vanguard\session;
use Vanguard\servicio;
use Vanguard\Http\Controllers\Api\ServicioEspacioController;


class solicitudController extends Controller
{

    public function store(Request $request)
    {    
         
        $datosGenerales=$request->get("datosGenerales") ;
        $contenedores=$request->get("containers");
        $visitantes=$request->get("visitors");
        $observacion = isset($datosGenerales['observacion']) ?  $datosGenerales['observacion'] : "";
        
       if(configuracionServicio::allowJustInYard($request->get('clasificacion')))
          $contenedores = $this->allowContainerJustInYard($contenedores);
        
        if(sizeof($contenedores) < 1) 
          return \Response::json (["servicio_empatio_solo_permitido" => 1]);
        
        $individuo_id=  $this->saveIndividuo($datosGenerales);
        
        $hash = Str::random(20);
       // dd($datosGenerales);
        $solicitud = solicitud::create([
           'bl'=>$request->get("bl"),
           'servicio_id'=>$request->get('clasificacion'),
           'fecha_verificar'=>$datosGenerales['fechaVerificar'],
           'hora_verificar'=>$datosGenerales['timeVerificar'],
           'individuo_id'=>$individuo_id,
           'condicion'=>$datosGenerales['condicionf'],
           'mercancia_id'=>$datosGenerales['mercanciaf'],
           'consignatario'=>$datosGenerales['cosignatario'],
           'createdDate'=>date("Y-m-d H:i:s"),
           'hash_to_cancel' => $hash,
           'statusId'=> 5,
           'observacion' => $observacion,
           'email' => $datosGenerales["correo"],
           'allow_scanner' => $datosGenerales['allow_scanner']
        ]);
        
        for($i=0;$i<sizeof($contenedores);$i++)
        {
            $contenedor_id = contenedores::create([
                'solicitud_id'=>$solicitud->id,
                'contenedor'=>$contenedores[$i]["Contenedor"],
                'estatus'=>$contenedores[$i]["Estatus"],
                'tipo'=>$contenedores[$i]["Tipo"],
                'createdDate'=>date("Y-m-d H:i:s"),
                'statusId'=>1  
             ])->id;  

             for($x=0;$x<sizeof($visitantes);$x++)
             {
                $visitante_id = $this->saveVisitante($visitantes[$x]);
                 
                /* visitantes::create([
                     'solicitud_id'=>$solicitud->id,
                     'individuo_id'=>$visitante_id,
                     'createdDate'=>date("Y-m-d H:i:s"),
                     'statusId'=>1,
                     'estatus'=> $visitantes[$x]["visit"]?"Sin restriccion":"Pendiente huella",
                     'rol'=> $visitantes[$x]["rol"]=='1'?1:0
                 ]);*/
                 $status = $visitantes[$x]["visit"]?"Sin restriccion":"Pendiente huella";

                 $this->createVisitas( $solicitud->id, $visitante_id, $contenedor_id, $status, $visitantes[$x]["rol"]);
             }
             
             
        }
        
       /* for($x=0;$x<sizeof($visitantes);$x++)
        {
           $visitante_id= $this->saveVisitante($visitantes[$x]);
            
            visitantes::create([
                'solicitud_id'=>$solicitud->id,
                'individuo_id'=>$visitante_id,
                'createdDate'=>date("Y-m-d H:i:s"),
                'statusId'=>1,
                'estatus'=> $visitantes[$x]["visit"]?"Sin restriccion":"Pendiente huella",
                'rol'=> $visitantes[$x]["rol"]=='1'?1:0
            ]);
        }*/
        
        $this->createVisita($request->all());
        
        $mercancia = mercancia::getMercanciaById($datosGenerales['mercanciaf']);
        
        
        $this->sendMail($request,$hash,$mercancia->descripcion,true);
        $this->sendMail($request,$hash,$mercancia->descripcion,false);

        $qrCode = $this->generateQrCode($hash);
        $solicitud->qrCode = base64_encode($qrCode); 
        $solicitud->enlace =  route("solicitud.acceso_zona_verificacion",$hash);
       // dd($request->all());
       return $solicitud;
        
    }
    
    public function saveEmailIndividuo($individuo)
    {
        if( !empty($individuo->email) && !individuosEmails::existIndividuoEmail($individuo->email, $individuo->id) )
        {
            individuosEmails::create(["email"=> $individuo->email,"individuo_id" => $individuo->id,"statusId" => 2,"createdDate" => Carbon::now()]);
            
        }
    }
    
    
    public function saveTelefonoIndividuo($individuo)
    {
        if( !empty($individuo->telefono) && !individuoTelefonos::existIndividuoTelefono($individuo->telefono, $individuo->id) )
        {
            individuoTelefonos::create(["telefono"=> $individuo->telefono,"individuo_id" => $individuo->id,"statusId" => 2,"createdDate" => Carbon::now()]);
            
        }
    }
    
    
    public static function updateIndividuo($datosGenerales,$individuo_id)
    {
        $tipoDocumento = $datosGenerales["pasaporteCedula"] == "Pasaporte"?2:1;
        
        individuos::where("id",$individuo_id)->update([
           'telefono'=> $datosGenerales['telefono'],
           'email'=> $datosGenerales['correo'],
           'modifiedDate' => Carbon::now(),
           'tipo_documento' => $tipoDocumento
                ]);
    }
    
    
    public function saveIndividuo($datosGenerales)
    {
       if(individuos::existIndividuoByDomuneto($datosGenerales['cedulaVisitante']))
       {
           $individuo = individuos::getIndividuoByDocumento($datosGenerales['cedulaVisitante']);
           $this->saveEmailIndividuo($individuo);
           $this->saveTelefonoIndividuo($individuo);
           $this->updateIndividuo($datosGenerales,$individuo->id);
           
           return $individuo->id;
           //return individuos::getIdByDocumento ($datosGenerales['cedulaVisitante']);
       }
        
       $cedula = str_replace("-", "", $datosGenerales['cedulaVisitante']);
       $tipoDocumento = $datosGenerales["pasaporteCedula"] == "Pasaporte"?2:1;
       
       $id= individuos::create([
           'documento'=> $cedula,
           'nombre_completo'=>$datosGenerales['nombreVisitante'],
           'telefono'=> $datosGenerales['telefono'],
           'email'=> $datosGenerales['correo'],
           'createdDate'=>date("Y-m-d H:i:s"),
           'tipo_documento'=> $tipoDocumento,
           'statusId'=>1 
        ]);
      
       return $id->id;
        
    }
    
    public function saveVisitante($visitors)
    {
        $documento = isset( $visitors['documento'] ) ? $visitors['documento'] : $visitors['cedula'];
       if(individuos::existIndividuoByDomuneto($documento) )
           return individuos::getIdByDocumento ($documento);
        
       $InputTipoDocumento = isset( $visitors["tipoDocumento"] ) ? $visitors["tipoDocumento"] : $visitors["pasaporteCedula"]; 
       $tipoDocumento = $InputTipoDocumento == "Pasaporte"? 2 : 1;
       
       $nombre = isset( $visitors['nombre'] ) ? $visitors['nombre'] : $visitors['nombreVisitante'];

       $id = individuos::create([
           'documento'=>$documento,
           'nombre_completo'=> $nombre,
           'createdDate'=>date("Y-m-d H:i:s"),
           'tipo_documento'=> $tipoDocumento,
           'statusId'=>1 
        ]);
      
       return $id->id;
        
    }

    
   public function allowContainerJustInYard($contenedores)
   {
       $newArrays = [];
       
        for($i=0;$i<sizeof($contenedores);$i++)
        {
           if( $contenedores[$i]["Estatus"] == "En puerto" )
               $newArrays [] = $contenedores[$i];
        }
        
       return $newArrays; 
   }

   public function  isAbleToProccee( solicitud $solicitud )
   {
      $obj = new ServicioEspacioController();

      $todayIsClose = $obj->todayIsClose($solicitud->servicio_id, Date("H:i"));

      if( $todayIsClose )
      {
        $response = Array( "outoftime" => true );
      }
      else
      {
        $response = $obj->getAvailableSpace(new Request([
            "date" => Carbon::now()->format("Y-m-d"),
            "bl" => $solicitud->bl,
            "servicio_id" => $solicitud->servicio_id,
            "hour" => Date("H:i:s")
   
   
         ]));
      }



      $obj = null;

      return $response;
   }


   
   public function cancelSolicitud($hash, $open = null)
   {
      // solicitud::cancelByHash($hash);
    
       $solicitud = solicitud::getByHash($hash);
       //dd( $solicitud);
       if( $solicitud->statusId == 6)
         return view("solicitud.solicitud_canceled");


         $available = $this->isAbleToProccee( $solicitud );

         if( Carbon::parse( $solicitud->fecha_verificar)->format("Y-m-d") <= Carbon::now()->format("Y-m-d") )
         return view("solicitud.solicitud_cambio_condicion_outoff_range",["message" =>
          "No se puede realizar cambio en la misma fecha o maryo a esta"]);
  //dd( date("H") );
        //if( date("H") >= 17 && date("H") >= 7 )
         if( ( isset( $available["outoftime"] ) && $available["outoftime"] ) ||  ( isset( $available["hollydate"] ) && $available["hollydate"] ))
          return view("solicitud.solicitud_cambio_condicion_outoff_range",["message" =>
           "le recordamos que las cancelaciones y cambios de condiciones deben ser realizadas de
            lunes a viernes de 8:00 am hasta las 05:00 pm y los sábados de 08:00 am hasta las 11:00 am."]);
  
        /* if( ( Carbon::now()->dayOfWeek == 6 || Carbon::now()->dayOfWeek == 0 ) && date("H") >= 11 && date("H") >= 7 )
            return view("solicitud.solicitud_cambio_condicion_outoff_range",["message" =>
             "le recordamos que las cancelaciones y cambios de condiciones deben ser realizadas de
              lunes a viernes de 8:00 am hasta las 05:00 pm y los sábados de 08:00 am hasta las 11:00 am."]);
         */

       return view("solicitud.solicitud_alert_canceled",compact("solicitud",'hash'));
      // return view("solicitud.solicitud_canceled");
   }
   
  public function cancelSolicitudByBl(Request $request)
  {
      $result = solicitud::cancelByBl($request->get("bl"));      
      return array("response" => $result);
  }
   
   public function storeCancelSolicitud($hash)
   {
       $solicitud = solicitud::getByHash($hash);
       if( $solicitud->entrada == 1 )
       return view( "solicitud.solicitud_entrada_realizada" );

       if( $solicitud->statusId == 6 ||  $solicitud->statusId == 2 || $solicitud->statusId == 3 )
       return view( "solicitud.solicitud_advertencia_cancelada" );

       solicitud::cancelByHash($hash);
       $objIndividuo = individuos::getIndividuoById($solicitud->individuo_id);
       
       $this->sendMailCanceled($solicitud, $objIndividuo->email);

       return view("solicitud.solicitud_canceled");      

   }
   
   public function createVisita($array)
   {

        $payload = json_encode($array);
        
       // dd(env("MIDDLEWHERE_URL"));
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,env("MIDDLEWHERE_URL")."visita");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);     
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        
        // Set HTTP Header for POST request 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'           
            
            )
        );          


        $server_output = curl_exec($ch);
        return $server_output;
        
    
   }
   
   
   public function getPendingRequest($bl)
   {
       $solicitud = solicitud::getSolicitudByBlBiggerThanToday($bl);
       $response = isset($solicitud->fecha_verificar)? Carbon::parse($solicitud->fecha_verificar)->format("d/m/Y") : null;
       return array("data" => $response );
   }
   
   
   public function sendMail($request,$hash,$mercancia,$isClient)
   {
       $qrCode = $this->generateQrCode($hash);
       $clasificacion = servicio::getById($request->get('clasificacion'));
       $descripcion = isset( $clasificacion->descripcion ) ? $clasificacion->descripcion : "";

       Mail::send(
            'emails.solicitud.template-solicitud-verificacion',
            ['request' => $request,'hash' => $hash,'mercancia' => $mercancia,
             "isClient" => $isClient,"qrCode" => $qrCode, "clasificacion" => $descripcion],
            function ($m) use ($request,$hash,$isClient) {
            //dd($request->containers[0]["LineaNaviera"]);
                if(!$isClient)
                  $m->to(env('EMAIL_BE_BCC_NOTIFIED'));
                //$m->bcc(env('EMAIL_BE_BCC_NOTIFIED'));
                //enviar una copia al cliente en caso de que haya establecido el correo
                
                if(!empty($request->datosGenerales["correo"]) && $isClient)
                $m->to($request->datosGenerales["correo"]);
                
                $m->from(env("EMAIL_BE_NOTIFIED"))->subject(' Solicitud verificación de contentenedores naviera  '.$request->containers[0]["LineaNaviera"]);
            }
        );
        
        
   }
   
   public function sendMailCanceled($solicitud,$email)
   {
       Mail::send(
            'emails.solicitud.template-solicitud-canceled',
            ['solicitud' => $solicitud,"email" => $email],
            function ($m) use ($solicitud,$email) {
            //dd($request->containers[0]["LineaNaviera"]);
                $m->bcc(env('EMAIL_BE_BCC_NOTIFIED'));
                //enviar una copia al cliente en caso de que haya establecido el correo
                if(!empty($email))
                $m->to($email);
                
                $m->from(env("EMAIL_BE_NOTIFIED"))->subject(' Solicitud verificación de contentenedores naviera cancelada '.$solicitud->bl);
            }
        );
   }

   public function sendMailPersonalAcesso($visitors,$representante,$hash,$email)
   {       
      $solicitudId = solicitud::getByHash($hash)->id;

       Mail::send(
            'emails.solicitud.solicitud_personal_acceso_alert',
            ['containers' => $visitors,'hash' => $hash,"email" => $email,
            "representante" => $representante,
            "solicitudId" => $solicitudId],
            function ($m) use ($visitors,$hash,$email,$representante) {
            //dd($request->containers[0]["LineaNaviera"]);

                //$m->bcc(env('EMAIL_BE_BCC_NOTIFIED'));
                //enviar una copia al cliente en caso de que haya establecido el correo
                
              
                $m->to($email);
                
                $m->from(env("EMAIL_BE_NOTIFIED"))->subject(' Solicitud verificación de contentenedores personal acceso ');
            }
        );
        
        
   }


   public function sendMailEdited($solicitud,$email)
   {
       Mail::send(
            'emails.solicitud.template-solicitud-email-condicion',
            ['solicitud' => $solicitud,"email" => $email],
            function ($m) use ($solicitud,$email) {
            //dd($request->containers[0]["LineaNaviera"]);
                $m->bcc(env('EMAIL_BE_BCC_NOTIFIED'));
                //enviar una copia al cliente en caso de que haya establecido el correo
                if(!empty($email))
                $m->to($email);
                
                $m->from(env("EMAIL_BE_NOTIFIED"))->subject(' Solicitud verificación de contentenedores naviera edición condición '.$solicitud->bl);
            }
        );
   }
   

   
   public function cambiarCondicion($hash)
   {
       $solicitud = solicitud::getByHash($hash);

       $available = $this->isAbleToProccee( $solicitud );

       if( Carbon::parse( $solicitud->fecha_verificar)->format("Y-m-d") <= Carbon::now()->format("Y-m-d") )
       return view("solicitud.solicitud_cambio_condicion_outoff_range",["message" =>
        "No se puede realizar cambio de condicíón en la misma fecha o maryo a esta"]);
//dd( date("H") );
      if( ( isset( $available["outoftime"] ) && $available["outoftime"] ) ||  ( isset( $available["hollydate"] ) && $available["hollydate"] ))
        return view("solicitud.solicitud_cambio_condicion_outoff_range",["message" =>
         "le recordamos que las cancelaciones y cambios de condiciones deben ser realizadas de
          lunes a viernes de 8:00 am hasta las 05:00 pm y los sábados de 08:00 am hasta las 11:00 am."]);

       /* if( ( Carbon::now()->dayOfWeek == 6 || Carbon::now()->dayOfWeek == 0 ) && date("H") >= 11 && date("H") >= 7 )
          return view("solicitud.solicitud_cambio_condicion_outoff_range",["message" =>
           "le recordamos que las cancelaciones y cambios de condiciones deben ser realizadas de
            lunes a viernes de 8:00 am hasta las 05:00 pm y los sábados de 08:00 am hasta las 11:00 am."]);
       */
       return view("solicitud.solicitud_cambio_condicion", compact("hash","solicitud")); 
   }
   
   public function storeCambio( \Vanguard\Http\Requests\Solicitud\solicitudCondicionRequest $request )
   {
       $solicitud = solicitud::getByHash( $request->get( "key" ) );   
      // dd($solicitud, $request->all());
       if( $solicitud->entrada == 1 )
       return view( "solicitud.solicitud_entrada_realizada" );

       if( $solicitud->statusId == 6 ||  $solicitud->statusId == 2 || $solicitud->statusId == 3 )
       return view( "solicitud.solicitud_advertencia_cancelada" );

       
      
       if( empty( $request->get("condicion") ) )
       {
           $condicionError = "El campo condición es requerido";
           return Redirect()->back()->withErrors($condicionError)->with(["err" => $condicionError]);
       }
       

       $objIndividuo = individuos::getIndividuoById($solicitud->individuo_id);
       if( isset( $objIndividuo->email ) )
       $this->sendMailEdited($solicitud, $objIndividuo->email);

       solicitud::updateCondicion( $request->get("condicion") , $solicitud->id );
      
       return view("solicitud.solicitud_condicion_updated");
   }


  public function cambiarAccesoZonaVerificacion( $hash )
  {
      $solicitud = solicitud::getByHash($hash);
      
      if( $solicitud->entrada == 1 )
      return view( "solicitud.solicitud_entrada_realizada");

      if( $solicitud->statusId == 6 ||  $solicitud->statusId == 2 || $solicitud->statusId == 3 )
      return view( "solicitud.solicitud_advertencia_cancelada" );

      $available = $this->isAbleToProccee( $solicitud );


      if( ( isset( $available["outoftime"] ) && $available["outoftime"] ) ||  ( isset( $available["hollydate"] ) && $available["hollydate"] ))
       return view("solicitud.solicitud_cambio_condicion_outoff_range",["message" =>
        "le recordamos que las cancelaciones y cambios de condiciones deben ser realizadas de
         lunes a viernes de 8:00 am hasta las 05:00 pm y los sábados de 08:00 am hasta las 11:00 am."]);
         

      $userAgent = \Request::server('HTTP_USER_AGENT');
      //$ip = \Request::ip();
      $session = session::getActiveSessionByUserAgent($userAgent);

     
      if( isset( $session->id ) )
      return redirect()->route("visitante.index",$solicitud->id);

      $contenedores = contenedores::getContenedoresBySolicitudId($solicitud->id);

      $responsable = visitantes::getRepresentanteBySolicitudId($solicitud->id,1)->first();

      $ayudantes = visitantes::getRepresentanteBySolicitudId($solicitud->id,0);
      $ayudantes = $this->agruparArreglo($ayudantes->toArray());


      
      return view("solicitud.solicitud_cambio_acceso_zona_verificacion", 
      compact("hash","solicitud","contenedores","responsable","ayudantes")); 
  }

public function generateQRCode( $hash )
{
    $url = route("solicitud.acceso_zona_verificacion",$hash);
    $renderer = new Png();
    $renderer->setWidth(300);
    $renderer->setHeight(300);

    $writer = new Writer($renderer);
    $qrCode = $writer->writeString($url);

    return $qrCode;
}

  function agruparArreglo( $array )
  {
    // Arreglo agrupado por la clave "contenedor"
    $agrupado = [];
    foreach ($array as $elemento) {
        $contenedor = $elemento["contenedor"];
        if (!isset($agrupado[$contenedor])) {
            $agrupado[$contenedor] = [];
        }
        $agrupado[$contenedor][] = $elemento;
    }

    // Imprimir el arreglo agrupado
    return $agrupado;
  }

  public function saveAccesoZonaVerificacion( Request $request )
  {
    $solicitud = solicitud::getByHash($request->get("hash"));
    $hash = $request->get("hash");
    $visitors = json_decode($request->get("fields"), true );
    $individuo = individuos::getIndividuoById($solicitud->individuo_id);
    $individuoTelefono = individuoTelefonos::getIndividuoTelefonoById($solicitud->individuo_id);

    $fechaVerificar = $solicitud->fecha_verificar;
   
    $dataTransform = $this->transformVisitors($visitors);

    $dataTransform['datosGenerales']["fechaVerificar"] = $fechaVerificar;
    $dataTransform['datosGenerales']["correo"] = $solicitud->email;
    $dataTransform["datosGenerales"]["telefono"] = isset( $individuoTelefono->telefono ) ? $individuoTelefono->telefono : "-";
    $dataTransform["containers"][0]["LineaNaviera"] = "-";
    $dataTransform["containers"][0]["Rnc"] = "1";
    $dataTransform["cedula"] = $individuo->documento;
    $dataTransform["bl"] = $solicitud->bl;
    
   
    visitantes::deleteBySolicitudId($solicitud->id);
    //Agregar representaante 
    $this->saveRepresentante( $visitors["representante"], $solicitud );

    $containers = $visitors["containers"];
    
    
    for( $x = 0; $x< sizeof($containers); $x++)
    {
         $contenedor_id = contenedores::getContenedorIdBySolicitudIdAndContainer($solicitud->id,
         $containers[$x]["contenedor"])->id;

        $ayudantes = $containers[$x]["ayudantes"];

        for( $i = 0; $i < sizeof($ayudantes); $i++ )
        {
            $visitante_id = $this->saveVisitante($ayudantes[$i]);
            $this->createVisitas( $solicitud->id, $visitante_id, $contenedor_id, $ayudantes[$i]["estatus"], 0);
        }
           
     }
        //dd($containers);
        $this->createVisita($dataTransform);

        $this->sendMailPersonalAcesso($containers,$visitors["representante"],$hash,$solicitud->email);
      
        return view("solicitud.solicitud_acceso_zona_verificacion_updated",
        compact("hash"));
  }

  public function transformVisitors( $array )
  {
    $representante = $array["representante"];
    $visitors = ["visitors" => [[
        "nombreVisitante" => $representante["nombre"],
        "cedula" => $representante["documento"],
        "responsable" => 1,
        "visit" => "true"
    ]]];
        

    $containers = $array["containers"];   
    

    for( $x = 0; $x< sizeof($containers); $x++)
    {

        $ayudantes = $containers[$x]["ayudantes"];

        for( $i = 0; $i < sizeof($ayudantes); $i++ )
        {
            $visitors["visitors"][] = [
                "nombreVisitante" => $ayudantes[$i]["nombre"],
                "cedula" => $ayudantes[$i]["documento"],
                "responsable" => 0,
                "visit" => "true"
            ];
        }
           
     }
    

    return $visitors;

  }

 public function saveRepresentante( $representante, $solicitud )
 {
    
    $visitante_id= $this->saveVisitante($representante);
    $this->createVisitas( $solicitud->id, $visitante_id, 0, $representante["estatus"], 1);

 }

  public function createVisitas( $solicitudId, $visitorId, $contenedor_id, $status, $rol)
  {
     return   visitantes::create([
            'solicitud_id'=> $solicitudId,
            'individuo_id'=> $visitorId,
            'contenedor_id'=> $contenedor_id,
            'createdDate'=>date("Y-m-d H:i:s"),
            'statusId'=>1,
            'estatus'=> $status,
            'rol'=> $rol == '1' ? 1 : 0
        ])->id;
  }

}
