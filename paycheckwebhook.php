<?php
$smscode = mt_rand(1000,9999);
$token = mt_rand(10000,99999);
include_once("conn.php");
$monthy = date('F');
//Check to be sure that its a POST method
if((strtoupper($_SERVER['REQUEST_METHOD'] != 'POST'))){
    exit();
}
$paymentDetails = @file_get_contents("php://input");
$headers = getallheaders();
$headers = json_encode($headers);
file_put_contents("file.html", "<pre>" . $paymentDetails . "</pre>");
file_put_contents("file2.html", "<pre>" . $headers . "</pre>");

define('PAYSTACK_SECRET_KEY', $payapi);
if($_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac('sha512', $paymentDetails, PAYSTACK_SECRET_KEY))
exit();
http_response_code(200);

//Events from Paystack
$event = json_decode($paymentDetails);
$chargeEvent = $event->event;


//Insert Data Into Db

if ($chargeEvent == 'charge.success') {
		$reference = $event->data->reference;
$amount = $event->data->amount / 100;
$transamt = $event->data->amount / 100;
$damt = number_format($transamt,2);
$paystatus = $event->data->status;
$trans_id = $event->data->id;
$first_name = $event->data->customer->first_name;
$last_name = $event->data->customer->last_name;
$transemail = $event->data->customer->email;
$customer_code = $event->data->customer->customer_code;
$gateway_response = $event->data->gateway_response;
	//check to collect the user by email
	//look for the user
	$checku = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE email='$transemail'");
	if(mysqli_num_rows($checku)>0){
		//user is found
			$user = mysqli_fetch_array($checku);
			$officer = $user['account_no'];
			$myemail = strtolower($user['email']);
			$me = $user['id'];
			$payref = $reference;
			
			
			
	//check if user selected a property
	
			$checkpid = mysqli_query($conn, "SELECT id,userid,propertyid,amount,status,date,planid,propuid,payduration,initial_deposit,amt_due,propamount,amt_paid,amt_remain FROM myproperty WHERE userid='$me'");
		if(mysqli_num_rows($checkpid)>0){
			//user selected property
			
		$dprop = mysqli_fetch_array($checkpid);
		$property = $dprop['propertyid'];
		$propuid = $dprop['propuid'];
		
		
		//check payment plan selected
		$checkplan = mysqli_query($conn, "SELECT id,plan_name,amount,plancode,status,type,duration,amt_to_pay,location,plot_size,payplancode FROM payment_plan WHERE id='$dprop[planid]'");
		if(mysqli_num_rows($checkplan)>0){
		    $myplan = mysqli_fetch_array($checkplan);
		    $yourplan = $myplan['plan_name'];
		}else{
		    $yourplan = $dprop['planid'];
		}
		
		
		}
			
	}
	
exit();
mysqli_close($conn);