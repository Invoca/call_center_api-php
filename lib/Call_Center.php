<?php

/* 
 * PHP wrapper classes to use the RingRevenue Call Center API
 */
 
 require_once 'Curl_Request.php';
 
 abstract class RingRevenue_Call_Center{
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
    				 'API_VERSION'	  => $this->API_VERSION,
    				 'API_USERNAME'	  => $this->API_USERNAME,
    				 'API_PASSWORD'	  => $this->API_PASSWORD);
    }
	
 }

 
class RingRevenue_Call_Center_Call
{
	protected $call_attributes;
	protected $api_num;
	public    $httpRequest;

	public function __construct( $vals ) {
		$this->call_attributes = array();
		$this->attributes($vals);
		$this->api_num = rand(0, 1);
		$this->httpRequest = new CurlRequest();
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
		return "https://api" . $this->api_num . ".ringrevenue.com/api/" . RingRevenue_Call_Center::$API_VERSION . "/calls/" . RingRevenue_Call_Center::$CALL_CENTER_ID . ".xml";
	}

	protected function request($method){
    $this->httpRequest->init($this->generate_api_url());
        
    $this->httpRequest->setOption(CURLOPT_URL, $this->generate_api_url());    
    
    $this->httpRequest->setOption(CURLOPT_USERPWD, RingRevenue_Call_Center::$API_USERNAME . ":" . RingRevenue_Call_Center::$API_PASSWORD );
	$this->httpRequest->setOption(CURLOPT_POSTFIELDS, http_build_query($this->get_attributes()) );
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