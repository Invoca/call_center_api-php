<?php
/* 
 * An example of how to use the PHP wrapper for RingRevenue Call Center API
 */
 require_once "Call_Center.php";
 
 // Set the information for your specific call center
 RingRevenue_Call_Center::config( array( 'CALL_CENTER_ID' => 1,
 					 'API_VERSION'	  => '2012-01-10',
 					 'API_USERNAME'   => 'test@ringrevenue.com',
 					 'API_PASSWORD'   => 'ringrevenue') );
  
  
  	// Sample Call Data
 $call_data = array(
		array('start_time_t'        => 1339289018, // Required Field
	
                      'call_center_call_id' => 91234567,
                      'duration_in_seconds' => 200,
                      'reason_code'         => 'S',
                      'sale_currency'       => 'USD',
                      'sale_amount'         => 1.01 ),
		array('start_time_t'        => 1339721018, // Required Field

                      'call_center_call_id' => 91234568,
                      'duration_in_seconds' => 200,
                      'reason_code'         => "S",
                      'sale_currency'       => "USD",
                      'sale_amount'         => 1.12,
                      'email_address'       => "john@doe.com",
                      'sku_list'            => array('dvd', 'apple'),
                      'quantity_list'       => array('5', '10') ),
		array('start_time_t'        => 1340153017, // Required Field

                      'call_center_call_id' => 91234569,
                      'duration_in_seconds' => 200,
                      'reason_code'         => 'S',
                      'sale_currency'       => 'USD',
                      'sale_amount'         => 2.02,
                      'use_http_status'     => 1 ) );
  
  // Loop through each set of call attributes to save them
 foreach( $call_data as $attributes){
 
    // Create a new call object and save
    $call = new RingRevenue_Call_Center_Call( $attributes );
    $response = $call->save();
    
    // Follow-up code to output response
    if($response['status_code'] >= 200 && $response['status_code'] < 300)
    	echo 'Success!\n\n';
    else
    	echo 'Error ' . $response['status_code'] . ':\n' . $response['response_body'] . '\n';
 }

 
 ?>