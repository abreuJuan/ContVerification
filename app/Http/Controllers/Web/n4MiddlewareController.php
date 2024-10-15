<?php


namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use Carbon\Carbon;
use Log;
use Vanguard\Support\Enum\StatusEnum;

class n4MiddlewareController  extends Controller {
    
    
    private $client = "";
    
    public function __construct()
    {
        $this->Connection();        
    }
    
    public function Connection()
    {

        $options = array(
            'login' => env("LOGIN_N4"),
            'password' => env("PASSWORD_N4"),
            'trace'                  => true,
            'connection_timeout'     => 10,
            'default_socket_timeout' => 10,
            "exceptions"             => true,
            'soap_version'           => SOAP_1_1,
            'cache_wsdl'             => WSDL_CACHE_NONE,
            'location'               => env("URL_N4"),
            "stream_context"         => stream_context_create(
                [
                    'ssl' => [
                        'verify_peer'      => false,
                        'verify_peer_name' => false,
                    ]
                ]
            )
        );
        
        $this->client = new \SoapClient(env("URL_N4"),$options);
       // dd(env("URL_MIDDLEWARE_BILLING"));
      
    }

public function releaseHoldOrAddSingle( $container, $holdID, $actionHold )

    {

        Log::info("Llamando metodo releaseHoldOrAddSingle N4.  $holdID $actionHold ". json_encode( $container ));

       

        $containersTag = "<unit-identity id='".$container."' type='CONTAINERIZED' /> ";

 

        if( !$containersTag ) return StatusEnum::LIBERACION_NO_AUTORIZADA;

 

        $xml = "<hpu>

        <entities>

        <units>        

          $containersTag

        </units>

        </entities>

        <flags>

        <flag hold-perm-id='$holdID' action='$actionHold'/>

        </flags>

        </hpu>";

 

       // dd($xml);

        $res = $this->client->genericInvoke(array("scopeCoordinateIdsWsType"=>array(

          "operatorId"=> env("OPERATOR"),

          "complexId"=> env("COMPLEX"),

          "facilityId"=> env("FACILITY"),

              "yardId"=> env("YARD")

          ),"xmlDoc" => $xml));    

 

        Log::info("Repuesta de N4". json_encode($res));

       

        $xml = simplexml_load_string($res->genericInvokeResponse->responsePayLoad) or die("Error: Cannot create object");

 

        return json_decode( json_encode($xml) , true );

    }


    public function CreateShipper( $_CurrentId, $_newId, $_name, $_taxId, $_crediStatus, $_taxGroup, $_currency, $_email)
    {
        Log::info("Llamando metodo createShipper N4. $_CurrentId $_newId $_name $_taxId $_crediStatus $_taxGroup $_currency $_email");
        $_crediStatus = $_crediStatus && !empty($_crediStatus) ? $_crediStatus : "";
        $_taxGroup = $_taxGroup && !empty($_taxGroup) ? $_taxGroup : "";
        $_currency = $_currency && !empty($_currency) ? $_currency : "";
        $_email = $_email && !empty($_email) ? $_email : "";
        
        $xml = '<custom class="HITWsProcessScopeBizUnit" type="extension" transactionId="AP2018052211064023770004" User="DynamicsAx"> 
                    <message> 
                        <Shippers>  
                            <shipper ID="'.$_CurrentId.'"
                                     newId="'.$_newId.'" 
                                     Name="'.$_name.'" 
                                     TaxId="'.$_taxId.'" 
                                     CreditStatus="'.$_crediStatus.'" 
                                     TaxGroup="'.$_taxGroup.'" 
                                     Currency="'.$_currency.'" 
                                     DueDateRule="NoRule" 
                                     eMails="'.$_email.'">
                            </shipper>  
                        </Shippers> 
                    </message>
                </custom>';

        

        $res = $this->client->genericInvoke(array("scopeCoordinateIdsWsType"=>array(
          "operatorId"=> env("OPERATOR"),
          "complexId"=> env("COMPLEX"),
          "facilityId"=> env("FACILITY"),
              "yardId"=> env("YARD")
          ),"xmlDoc" => $xml));     

        Log::info("Repuesta de N4". json_encode($res));


        $xml = simplexml_load_string($res->genericInvokeResponse->responsePayLoad) or die("Error: Cannot create object");

        $json_string = json_encode($xml);  

        return  Array("status" => "0", "Body" => $json_string,"Error" => "");

       
    }
    
    public function fetchContainers($containers,$bl , $allColumns = false, $param_line = false)
    {
        Log::info("Llamando metodo fetchContainers N4. $bl".json_encode($containers));
        $allColumns = $allColumns ? 1 : 0;
        
        $xml = "<groovy class-name=\"FetchContainers\" class-location=\"code-extension\">
                  <parameters>
                      <parameter id=\"containerNo\" value=\"$containers\"/>	 
                      <parameter id=\"bol\" value=\"$bl\"/>	   
                      <parameter id=\"all_columns\" value=\"$allColumns\"/>	     
                  </parameters>
              </groovy>";

        
        $res = $this->client->genericInvoke(array("scopeCoordinateIdsWsType"=>array(
          "operatorId"=> env("OPERATOR"),
          "complexId"=> env("COMPLEX"),
          "facilityId"=> env("FACILITY"),
              "yardId"=> env("YARD")
          ),"xmlDoc" => $xml));     

        Log::info("Repuesta de N4". json_encode($res));
        
        $xml = simplexml_load_string($res->genericInvokeResponse->commonResponse->QueryResults->Result) or die("Error: Cannot create object");

        $json_string = json_encode($xml);    
     
        $result_array = json_decode($json_string, TRUE);
      
        return  $this->translateTagField( $result_array, $param_line );
    }

    public function getContainersHLUTag( $containers )
    {
       if( !is_array( $containers ) ) return false;

       $containers = isset( $containers["containers"] ) ? $containers["containers"] : [];
       if( sizeof( $containers ) < 1 ) return false;

       $containertsTag = "";

       $containers = !isset( $containers[0] ) && isset( $containers["containerNo"] ) ? Array($containers) : $containers;

       for( $i = 0; $i < sizeof( $containers ) ; $i++ )
       {
          $containertsTag .= "<unit-identity id='".$containers[$i]["containerNo"]."' type='CONTAINERIZED' /> " ;
       }

       return $containertsTag;
    }

    public function releaseHoldOrAdd( $containers, $holdID, $actionHold )
    {
        Log::info("Llamando metodo releaseHoldOrAdd N4.  $holdID $actionHold ". json_encode( $containers ));
        
        $containersTag = $this->getContainersHLUTag( $containers );

        if( !$containersTag ) return StatusEnum::LIBERACION_NO_AUTORIZADA;

        $xml = "<hpu>
        <entities>
        <units>        
          $containersTag
        </units>
        </entities>
        <flags>
        <flag hold-perm-id='$holdID' action='$actionHold'/>
        </flags>
        </hpu>";

       // dd($xml);
        $res = $this->client->genericInvoke(array("scopeCoordinateIdsWsType"=>array(
          "operatorId"=> env("OPERATOR"),
          "complexId"=> env("COMPLEX"),
          "facilityId"=> env("FACILITY"),
              "yardId"=> env("YARD")
          ),"xmlDoc" => $xml));     

        Log::info("Repuesta de N4". json_encode($res));
        
        $xml = simplexml_load_string($res->genericInvokeResponse->responsePayLoad) or die("Error: Cannot create object");

        return StatusEnum::LIBERACION_EXISTOSA; 
    }
    
    public function validateLines($value, $lines)
    {
        
        if( empty($lines) ) return true;
        $arrLines = explode(",",$lines);
        
        if( !is_array($arrLines) ) return $value == $arrLines;

        if( is_array($arrLines) && sizeof($arrLines) == 1 ){            
            return $value == $arrLines[0];
        } 

        for( $i = 0; $i < sizeof($arrLines); $i++ )
        {
            if( $value == $arrLines[$i] )
              return true;

        }

        return false;
    }
    
    public function translateTagField( $array, $lines )
    {
        
        $newArray = Array("containers" => []);
        
        if( is_array($array) && sizeof($array) < 2 )
        return null;
        
        foreach($array["containers"] AS $key => $values)
        {
            $itemsArray = [];  
          if( !isset($array["containers"][0]) )
          {

              if( $this->validateLines($array["containers"]["lineCode"] ,$lines) )
              $newArray["containers"][$key] = $this->validateEmptyValue($values);              
            

           // if( sizeof($itemsArray) > 0 )
          //  $newArray["containers"][] = $itemsArray;
           // $array["containers"][$key] = $this->validateEmptyValue($values);
         /*  $newArray["containers"][$key] = $this->validateEmptyValue($values);
           dd($array["containers"]);*/
          }
          else
          {
            
            foreach( $values AS $key1 => $value )
            {
              if( $this->validateLines($values["lineCode"],$lines) )
              $itemsArray[$key1] = $this->validateEmptyValue($value);
            }

            if( sizeof($itemsArray) > 0 )
            $newArray["containers"][] = $itemsArray;
         /*   if( $this->validateLines($newArray["lineCode"],$lines) )
            {                
                $array["containers"][$key] = $newArray;
            }
            */
         }

          
        }

        $newArray["Error"] = "";
        return $newArray;
    }

    public function validateEmptyValue( $value )
    {
        
        if( is_array($value) && sizeof($value) < 1 )
        { 
          return "";
        }
        else
        {
         return $value;
        }
        
    }

    public function applyEvent($containers, $event, $bl)
    {
        Log::info("Llamando metodo applyEvent N4. $bl $event".json_encode($containers));
       
        
        $xml = "<groovy class-name=\"HITGNoticesApplyEvent\" class-location=\"code-extension\">
                  <parameters>
                      <parameter id=\"containerNo\" value=\"$containers\"/>	 
                      <parameter id=\"event\" value=\"$event\"/>  
                      <parameter id=\"bl\" value=\"$bl\"/>                         
                  </parameters>
              </groovy>";

        
        $res = $this->client->genericInvoke(array("scopeCoordinateIdsWsType"=>array(
          "operatorId"=> env("OPERATOR"),
          "complexId"=> env("COMPLEX"),
          "facilityId"=> env("FACILITY"),
              "yardId"=> env("YARD")
          ),"xmlDoc" => $xml));     

        Log::info("Repuesta de N4". json_encode($res));
        
        $rusult = isset($res->genericInvokeResponse->commonResponse->QueryResults->Result) ? $res->genericInvokeResponse->commonResponse->QueryResults->Result: "";
      
        return  $rusult;
    }
      
}
