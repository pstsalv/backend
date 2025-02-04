<?php
include_once("conn.php");
// only a post with paystack signature header gets our attention
if ((strtoupper($_SERVER['REQUEST_METHOD']) != 'POST' ) || !array_key_exists('x-paystack-signature', $_SERVER) ) 
    exit();

// Retrieve the request's body
$input = @file_get_contents("php://input");
define('PAYSTACK_SECRET_KEY',$payapi);

// validate event do all at once to avoid timing attack
if($_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac('HMAC SHA512', $input, PAYSTACK_SECRET_KEY))
    exit();
    
    

http_response_code(200);

// parse event (which is json string) as object
// Do something - that will not take long - with $event
$event = json_decode($input);
$reference = $event->data->reference;
$email = $event->data->customer->email;
$eventMessage = $event->event;
$amount = $event->data->amount;

if($eventMessage=="charge.success"){
	
}
?>