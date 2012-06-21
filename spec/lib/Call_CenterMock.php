<?php

require_once 'Call_Center.php';

class RingRevenue_Call_Center_CallMock extends RingRevenue_Call_Center_Call
{
  
public function __construct( $data) {
	parent::__construct( $data);
}

protected function request($method){
	return array('status_code' => '200', 'response_body' => '');
}

public function get_api_url(){
	return $this->generate_api_url();
}


}