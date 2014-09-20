<?php
/* 
 * An example of how to use the PHP wrapper for Invoca Call Center API
 */
 require_once "Call_Center.php";
 
 // Set the information for your specific call center
 Invoca_Call_Center::config( array( 'CALL_CENTER_ID' => 1,
 					 'API_VERSION'	  => '2010-04-22',
 					 'API_USERNAME'   => 'test@invoca.com',
 					 'API_PASSWORD'   => 'invoca') );
  
  
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
                      'sku_list[]'            => array('DVD', 'cleaner'),
                      'quantity_list[]'       => array('2', '1') ),
		array('start_time_t'        => 1340153017, // Required Field

                      'call_center_call_id'  => 91234569,
                      'duration_in_seconds'  => 200,
                      'reason_code'          => 'S',
                      'sale_currency'        => 'USD',
                      'sale_amount'          => 2.02,
                      'called_phone_number'  => '+1 8888665440',
                      'calling_phone_number' => '+1 8056801218' ) );
  
  // Loop through each set of call attributes to save them
 foreach( $call_data as $attributes){
 
    // Create a new call object and save
    $call = new Invoca_Call_Center_Call( $attributes );
    $response = $call->save();
    
    // Follow-up code to output response
    $params = $call->get_attributes();
    if($response['status_code'] >= 200 && $response['status_code'] < 300)
    	echo "Success on call " . $params['call_center_call_id'] . "!\n\n";
    else
    	echo $response['status_code'] . " Error on call " . $params['call_center_call_id'] . ":\n" . $response['response_body'] . "\n";
 }

 
 ?>
