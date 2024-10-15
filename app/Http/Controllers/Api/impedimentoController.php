<?php

namespace Vanguard\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Vanguard\Http\Controllers\Controller;
use Vanguard\visitaAllow;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Input;

class impedimentoController extends Controller
{

   private $token;
    private $http;
    
    public function __construct()
    {
        $this->http = new Client();
    }
    
    
    public function AutoLogin()
    {

        $response = $this->http->post(env("BIOSTAR_API_URL")."/oauth/token", [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => env("BIOSTAR_API_CLIENT_ID"),
                'client_secret' => env("BIOSTAR_CLIENT_SECRET"),
                'username' => env("BIOSTAR_API_USER"),
                'password' => env("BIOSTAR_API_PASSWORD"),
                'scope' => '',
            ],
        ]);

        $thisUsersTokens = json_decode((string) $response->getBody(), true);    

        $this->setToken($thisUsersTokens["access_token"]);

    }    
    
  
    public function setToken($token)
    {
        $this->token=$token;
    }
    
    public function getToken()
    {        
        return $this->token;
    }
    
    
    public function impedimentos($cedula)
    {
        
        if(empty($this->token))
        {
            $this->autoLogin();
        }        
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,env("BIOSTAR_API_URL")."api/impedimento/".$cedula);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, false);     

        // Set HTTP Header for POST request 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',           
            "Authorization: Bearer ".str_replace('"', "", $this->token)
            )
        );          


        $server_output = curl_exec($ch);
        return $server_output;
        

    }
    
    
    public function createMeeting(Request $request)
    {
        
        if(empty($this->token))
        {
            $this->autoLogin();
        }
        
        $data = $request->all();

        $payload = json_encode($data); 
        
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,env("BIOSTAR_API_URL")."api/visita");
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
        curl_close ($ch); 
        
        return $server_output;
    }
    
}
