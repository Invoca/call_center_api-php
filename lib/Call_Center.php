<?php

/* 
 * PHP wrapper classes to use the Invoca Call Center API
 */
 
 require_once 'Curl_Request.php';
 
 abstract class Invoca_Call_Center{
 	public static $CALL_CENTER_ID;
 	public static $API_VERSION;
 	public static $API_USERNAME;
 	public static $API_PASSWORD;
 
 	public static function config( $data ){
	  $keys = array('CALL_CENTER_ID', 
                  	'API_VERSION', 
                  	'API_USERNAME', 
                  	'API_PASSWORD');

          foreach ($data as $option => $value) {
            if (in_array($option, $keys)) {
                self::$$option = $value;
            }
          }
        }
    
    public static function get_config(){
    	return array('CALL_CENTER_ID' => $this->CALL_CENTER_ID,
                     'API_VERSION'    => $this->API_VERSION,
                     'API_USERNAME'   => $this->API_USERNAME,
                     'API_PASSWORD'   => $this->API_PASSWORD);
    }
	
 }

 
class Invoca_Call_Center_Call
{
	protected $call_attributes;
	protected $api_num;
	public    $httpRequest;
	public 	  $PORT = 80;
	
	public function __construct( $vals ) {
            $this->call_attributes = array();
            $this->attributes($vals);
            $this->api_num = rand(0, 1);
            $this->httpRequest = new CurlRequest();
            if (isset($_SERVER['argv'][1]))
            	$this->PORT = $_SERVER['argv'][1]; 
	}

	public function get_attributes(){
            return $this->call_attributes;
	}
	
	public function save(){
            return $this->request('PUT');
	}
	
	public function attributes( $data = array() ){
            foreach($data as $key => $value){
                $this->call_attributes[$key] = $value;
            }
	}
	
	public function get_api_num(){
            return $this->api_num;
	}

	protected function generate_api_url(){
			$url = ($this->PORT == 80) ? "https://" : "";
            return $url . "api" . $this->api_num . ".invoca.com:" . $this->PORT . "/api/" . Invoca_Call_Center::$API_VERSION . "/calls/" . Invoca_Call_Center::$CALL_CENTER_ID . ".xml";
	}
	
	public function generate_attributes(){
	   $query = '';
	   $attrs = $this->get_attributes();
	   $keys = array_keys($attrs);
	   sort($keys);
  	   foreach($keys as $key){
    		if(is_array($attrs[$key])){
    			foreach($attrs[$key] as $array_val){
    				$query = $query . '&' . http_build_query(array($key => $array_val) );
    			}
    		}
    		else
    			$query = $query . '&' . http_build_query(array($key => $attrs[$key]) );
  	   }
       return substr($query, 1);
	}

	protected function request($method){	
	$this->httpRequest->init($this->generate_api_url());
        
	$this->httpRequest->setOption(CURLOPT_URL, $this->generate_api_url());    
    
    $this->httpRequest->setOption(CURLOPT_USERPWD, Invoca_Call_Center::$API_USERNAME . ":" . Invoca_Call_Center::$API_PASSWORD );
        
	$this->httpRequest->setOption(CURLOPT_POSTFIELDS, $this->generate_attributes() );
	$this->httpRequest->setOption(CURLOPT_FAILONERROR, false);
	$this->httpRequest->setOption(CURLOPT_RETURNTRANSFER, 1);
	
	switch($method) {
            case 'PUT':
                $this->httpRequest->setOption(CURLOPT_CUSTOMREQUEST, 'PUT');
		break;
	}
	
	$res = $this->httpRequest->execute();
	
	$statusCode = $this->httpRequest->getinfo(CURLINFO_HTTP_CODE);
	
	return array('status_code' => $statusCode, 'response_body' => $res);
	
	}

  }
?>
