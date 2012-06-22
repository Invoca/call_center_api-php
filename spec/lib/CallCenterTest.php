<?php

require_once realpath(dirname(__FILE__)) . "/Call_CenterMock.php";
require_once realpath(dirname(__FILE__)) . "/Curl_RequestMock.php";
require_once 'Call_Center.php';

  
class RingRevenue_Call_CenterTest extends PHPUnit_Framework_TestCase
{
  
  public function setUp(){
  	RingRevenue_Call_Center::config( array( 'CALL_CENTER_ID' => 1,
 						'API_VERSION'	 => '2012-01-10',
 						'API_USERNAME'   => 'test@ringrevenue.com',
 						'API_PASSWORD'   => 'password') );
  }
  
  public function testCallAttributesUpdate(){
  	$call = new RingRevenue_Call_Center_Call( array( 'start_time_t' => '134124231' ) );  	
  	$data = array( 'start_time_t'         => '12341231',
                       'call_center_call_id'  => '1',
                       'duration_in_sections' => '200',
                       'reason_code'          => 'S',
                       'opt_in_SMS'           => '1');
  	$call->attributes( $data );
  	$this->assertEquals( $data, $call->get_attributes() );
  	
  }
  public function testSaveShouldUseCurlCorrectly(){
    $this->expectOutputString('Success!\n\n');
  	$call = new RingRevenue_Call_Center_Call( array( 'start_time_t' => '134124231' ) );
  	$call->httpRequest = new CurlRequestMock();
  	$response = $call->save();
  	
  	$expected_options = array( CURLOPT_URL => "https://api" . $call->get_api_num() . ".ringrevenue.com:80/api/" . RingRevenue_Call_Center::$API_VERSION . "/calls/" . RingRevenue_Call_Center::$CALL_CENTER_ID . ".xml",
                                   CURLOPT_USERPWD        => RingRevenue_Call_Center::$API_USERNAME . ":" . RingRevenue_Call_Center::$API_PASSWORD,
                                   CURLOPT_POSTFIELDS 	  => 'start_time_t=134124231',
                                   CURLOPT_FAILONERROR    => false,
                                   CURLOPT_RETURNTRANSFER => 1,
                                   CURLOPT_CUSTOMREQUEST  => 'PUT');
  	
  	$this->assertEquals(true, $call->httpRequest->called_init);
  	$this->assertEquals(true, $call->httpRequest->called_execute);
  	$this->assertEquals($expected_options, $call->httpRequest->options);
  	if($response['status_code'] >= 200 && $response['status_code'] < 300)
            echo 'Success!\n\n';
  }
  
  public function testGenerateAttributesCorrectly(){
        $call = new RingRevenue_Call_Center_Call( array( 'start_time_t' => '134124231' ) );
        $data = array( 'start_time_t'         => '12341231',
                       'call_center_call_id'  => '1',
                       'sku_list[]'           => array('one'),
                       'duration_in_sections' => '200',
                       'reason_code'          => 'S',
                       'opt_in_SMS'           => '1');
  	    $call->attributes( $data );
  	    $this->assertEquals("call_center_call_id=1&duration_in_sections=200&opt_in_SMS=1&reason_code=S&sku_list%5B%5D=one&start_time_t=12341231", $call->generate_attributes());
  }
  	
  public function testSaveShouldSuccessOn200s(){
    $this->expectOutputString('Success!\n\n');
    $call = new RingRevenue_Call_Center_CallMock( array( 'start_time_t' => '134124231' ) );
    $response = $call->save();
    if($response['status_code'] >= 200 && $response['status_code'] < 300)
        echo 'Success!\n\n';
  }
  
  
}

?>