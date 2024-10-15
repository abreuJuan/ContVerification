<?php

namespace Vanguard\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Vanguard\Http\Controllers\Controller;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Input;

class contenedoresController extends Controller
{
 private $token;
    private $http;
    
    public function __construct()
    {
        $this->http = new Client();
    }
    
    public function autoLogin()
    {

        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,env("NAVIS_API_SOLICITUD_CONTENEDORES_URL")."login");
      // dd(env("NAVIS_API_SOLICITUD_CONTENEDORES_URL")."login");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                    "UserName=".env("NAVIS_API_SOLICITUD_CONTENEDORES_USER")."&Password=".env("NAVIS_API_SOLICITUD_CONTENEDORES_PASSWORD"));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);
        
        curl_close ($ch);       
        
        
      /* $options = [
              'headers' => [
              'Content-Type' => 'application/json',
              'Accept' => 'application/json',
              'Access-Control-Allow-Origin'=>"*"],
                'json' => [
                "ÜserName"=>env("NAVIS_API_SOLICITUD_CONTENEDORES_USER"),
                 "Password"=>env("NAVIS_API_SOLICITUD_CONTENEDORES_PASSWORD")
                    ],
                 ];
       
       
       $response=$this->http->post(
               env("NAVIS_API_SOLICITUD_CONTENEDORES_URL")."login",
               $options
               );
       
       dd($response);*/
       
        $this->setToken($server_output);
    }
    
    public function setToken($token)
    {
        $this->token=$token;
    }
    
    public function getToken()
    {        
        return $this->token;
    }
    
    public function getContenedores()
    {
        
        if(empty($this->token))
        {
            $this->autoLogin();
        }
        
       $data = array(
            'UserName' => env("NAVIS_API_SOLICITUD_CONTENEDORES_USER"),
            'TipoReferencia' => env("NAVIS_API_SOLICITUD_CONTENEDORES_REFERENCIA"),
            'Rnc' => '00116270331',
            'Referencia' => Input::get("Referencia")           
        );

        $payload = json_encode($data); 
       // dd(1);
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,env("NAVIS_API_SOLICITUD_CONTENEDORES_URL")."HitServices");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // Set HTTP Header for POST request 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload),
            "Authorization: Bearer ".str_replace('"', "", $this->token)
            )
        );      
           


        $server_output = curl_exec($ch);
        //dd($server_output);
        curl_close ($ch); 
        
        return $server_output;
        
    }

}
