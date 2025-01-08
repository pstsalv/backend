<?php
header("access-control-allow-origin: *");
header('content-type: application/json; charset=utf-8');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");
$smscode = mt_rand(1000,9999);
$token = mt_rand(10000,99999);
$me = mysqli_real_escape_string($conn,$_POST['me']);
$payref = mysqli_real_escape_string($conn,$_POST['payref']);
$property = mysqli_real_escape_string($conn,$_POST['property']);


  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/$payref",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Authorization: Bearer $payapi",
      "Cache-Control: no-cache",
    ),
  ));
  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);
  if ($err) {
   $responses = '{"status":false, "message":"Something went terribly wrong, kindly retry"}';
	echo $responses;
  } else {
   // echo $response;
	
	 $respons = json_decode($response, true);
	
    if ($respons['status'] === true) {
    if ($respons['data']['status'] == "success") {
		$paystatus = $respons['data']['status'];
		$transamt = $respons['data']['amount']/100;
		$trans_id = $respons['data']['id'];
		$transemail = $respons['data']['customer']['email'];
		$damt = number_format($transamt,2);
		$gateway_response = $respons['data']['gateway_response'];
		$paid_at = $respons['data']['paid_at'];
		//check to collect property id
		$checkpid = mysqli_query($conn, "SELECT id,propuid,planid,plot_size FROM myproperty WHERE propertyid='$property' AND userid='$me'");
		$dprop = mysqli_fetch_array($checkpid);
		
		$checkplan = mysqli_query($conn, "SELECT id,plan_name,amount,plancode,status,type,duration,amt_to_pay,location,plot_size,payplancode FROM payment_plan WHERE id='$dprop[planid]'");
		if(mysqli_num_rows($checkplan)>0){
		    $myplan = mysqli_fetch_array($checkplan);
		    $yourplan = $myplan['plan_name'];
		}else{
		    $yourplan = $dprop['planid'];
		}
		//check if payment already exist
		$checkp = mysqli_query($conn, "SELECT * FROM paystack_pay WHERE reference='$payref'");
		if(mysqli_num_rows($checkp)<1){
			
			//credit the user
			$checku = mysqli_query($conn, "SELECT * FROM users WHERE id='$me'");
			$user = mysqli_fetch_array($checku);
	
	if($user['email']==$respons['data']['customer']['email']){
			$upd = mysqli_query($conn, "INSERT INTO payment VALUES(NULL,'$me','$transamt','approved','$user[wallet_bal]','$user[userid]','virtual','$paid_at','Paystack','$gateway_response','$property','$payref','$user[state]','$user[planid]','$user[agent]','','$user[account_no]','$user[date]','$dprop[plot_size]','$user[branchid]','$user[second_agent]','$dprop[prop_category]','','','')");
if($upd){
	
	$sent = mysqli_query($conn, "UPDATE users SET wallet_bal=wallet_bal+$transamt WHERE id='$me'");
	mysqli_query($conn, "UPDATE myproperty SET amt_paid=amt_paid+$transamt,amt_remain=amt_remain-$transamt WHERE userid='$me' AND propertyid='$property'");
	
$messagen = "You are now subscribed to $yourplan payment plan. Your payment of N $damt for #$dprop[propuid] with Bliss Legacy Limited is successful. For enquiry call 07000325477. Thanks. Payment Reference #$payref";

$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$me','notification','Payment sucessful','$messagen','unread',now())");

$stackk = mysqli_query($conn, "INSERT INTO paystack_pay VALUES(NULL,'$me','$transamt','$property','$gateway_response','$payref','$paystatus','$trans_id',now(),'app')");


if($sent){

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://app.smartsmssolutions.com/io/api/client/v1/sms/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('token' => 'kr2Wm1k2bncwP3yWYy18nXbkgDvAqPmYFLNBPRDZ1wCPBWZF3M',
  'sender' => 'BlissPay',
  'to' => $user['phone'],
  'message' => $messagen,
  'type' => '0',
  'routing' => '4',
  'ref_id' => $token,
  'simserver_token' => '',
  'dlr_timeout' => '1',
  'schedule' => ''),
));

$response = curl_exec($curl);

curl_close($curl);
$responses = '{"status": true, "message": "Verification successful", "data": {"id": "'.$trans_id.'", "domain": "live", "status": "'.$paystatus.'", "reference": "'.$payref.'", "amount": "'.$transamt.'", "currency": "NGN"}}';
echo $responses;
}else{
die(mysqli_error($conn));
	
}


}
		}else{
$demail = $respons['data']['customer']['email'];
$checkmyprop = mysqli_query($conn, "SELECT * FROM myproperty WHERE payment_email='$demail' LIMIT 1");
	if(mysqli_num_rows($checkmyprop)>0){
		$mypropdet = mysqli_fetch_array($checkmyprop);


$upd = mysqli_query($conn, "INSERT INTO payment VALUES(NULL,'$me','$transamt','approved','$user[wallet_bal]','$user[userid]','virtual','$paid_at','Paystack','$gateway_response','$property','$payref','$user[state]','$user[planid]','$user[agent]','','$user[account_no]','$user[date]','$dprop[plot_size]','$user[branchid]','$user[second_agent]','','','','')");
if($upd){
	
	$sent = mysqli_query($conn, "UPDATE users SET wallet_bal=wallet_bal+$transamt WHERE id='$me'");
	mysqli_query($conn, "UPDATE myproperty SET amt_paid=amt_paid+$transamt,amt_remain=amt_remain-$transamt WHERE userid='$me' AND propertyid='$property'");
	
$messagen = "You are now subscribed to $yourplan payment plan. Your payment of N $damt for #$dprop[propuid] with Bliss Legacy Limited is successful. For enquiry call 07000325477. Thanks. Payment Reference #$payref";

$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$me','notification','Payment sucessful','$messagen','unread',now())");

$stackk = mysqli_query($conn, "INSERT INTO paystack_pay VALUES(NULL,'$me','$transamt','$property','$gateway_response','$payref','$paystatus','$trans_id',now(),'app')");


if($sent){

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://app.smartsmssolutions.com/io/api/client/v1/sms/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('token' => 'kr2Wm1k2bncwP3yWYy18nXbkgDvAqPmYFLNBPRDZ1wCPBWZF3M',
  'sender' => 'BlissPay',
  'to' => $user['phone'],
  'message' => $messagen,
  'type' => '0',
  'routing' => '4',
  'ref_id' => $token,
  'simserver_token' => '',
  'dlr_timeout' => '1',
  'schedule' => ''),
));

$response = curl_exec($curl);

curl_close($curl);
$responses = '{"status": true, "message": "Verification successful", "data": {"id": "'.$trans_id.'", "domain": "live", "status": "'.$paystatus.'", "reference": "'.$payref.'", "amount": "'.$transamt.'", "currency": "NGN"}}';
echo $responses;
}else{
die(mysqli_error($conn));
	
}


}


}


		}
		}else{
$dpayer = mysqli_fetch_array($checkp);

$checkwho = mysqli_query($conn, "SELECT id, fname,lname FROM users WHERE id='$dpayer[payer]'");
$payowner = mysqli_fetch_array($checkwho);
$dfullname = htmlentities($payowner['fname'])." ".htmlentities($payowner['lname']);
			$responses = '{"status":"alreadydone", "message":"This payment was already added to '.$dfullname.'\'s payment history"}';
	echo $responses;
		}
	//echo json_encode($respons);
	}else{
		echo json_encode($respons);
		
	}
  }else{



//check for house


  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/$payref",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Authorization: Bearer sk_live_7303e6ad47bf4ef9d00c598b7a3091ddbb4ca8c0",
      "Cache-Control: no-cache",
    ),
  ));
  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);
  if ($err) {
   $responses = '{"status":false, "message":"Something went terribly wrong, kindly retry"}';
	echo $responses;
  } else {
   // echo $response;
	
	 $respons = json_decode($response, true);
	
    if ($respons['status'] === true) {
    if ($respons['data']['status'] == "success") {
		$paystatus = $respons['data']['status'];
		$transamt = $respons['data']['amount']/100;
		$trans_id = $respons['data']['id'];
		$transemail = $respons['data']['customer']['email'];
		$damt = number_format($transamt,2);
		$gateway_response = $respons['data']['gateway_response'];
		$paid_at = $respons['data']['paid_at'];
		//check to collect property id
		$checkpid = mysqli_query($conn, "SELECT id,propuid,planid,plot_size FROM myproperty WHERE propertyid='$property' AND userid='$me'");
		$dprop = mysqli_fetch_array($checkpid);
		
		$checkplan = mysqli_query($conn, "SELECT id,plan_name,amount,plancode,status,type,duration,amt_to_pay,location,plot_size,payplancode FROM payment_plan WHERE id='$dprop[planid]'");
		if(mysqli_num_rows($checkplan)>0){
		    $myplan = mysqli_fetch_array($checkplan);
		    $yourplan = $myplan['plan_name'];
		}else{
		    $yourplan = $dprop['planid'];
		}
		//check if payment already exist
		$checkp = mysqli_query($conn, "SELECT * FROM paystack_pay WHERE reference='$payref'");
		if(mysqli_num_rows($checkp)<1){
			
			//credit the user
			$checku = mysqli_query($conn, "SELECT * FROM users WHERE id='$me'");
			$user = mysqli_fetch_array($checku);
	
	if($user['email']==$respons['data']['customer']['email']){
			$upd = mysqli_query($conn, "INSERT INTO payment VALUES(NULL,'$me','$transamt','approved','$user[wallet_bal]','$user[userid]','virtual','$paid_at','Paystack','$gateway_response','$property','$payref','$user[state]','$user[planid]','$user[agent]','','$user[account_no]','$user[date]','$dprop[plot_size]','$user[branchid]','$user[second_agent]','','','','')");
if($upd){
	
	$sent = mysqli_query($conn, "UPDATE users SET wallet_bal=wallet_bal+$transamt WHERE id='$me'");
	mysqli_query($conn, "UPDATE myproperty SET amt_paid=amt_paid+$transamt,amt_remain=amt_remain-$transamt WHERE userid='$me' AND propertyid='$property'");
	
$messagen = "You are now subscribed to $yourplan payment plan. Your payment of N $damt for #$dprop[propuid] with Bliss Legacy Limited is successful. For enquiry call 07000325477. Thanks. Payment Reference #$payref";

$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$me','notification','Payment sucessful','$messagen','unread',now())");

$stackk = mysqli_query($conn, "INSERT INTO paystack_pay VALUES(NULL,'$me','$transamt','$property','$gateway_response','$payref','$paystatus','$trans_id',now(),'app')");


if($sent){

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://app.smartsmssolutions.com/io/api/client/v1/sms/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('token' => 'kr2Wm1k2bncwP3yWYy18nXbkgDvAqPmYFLNBPRDZ1wCPBWZF3M',
  'sender' => 'BlissPay',
  'to' => $user['phone'],
  'message' => $messagen,
  'type' => '0',
  'routing' => '4',
  'ref_id' => $token,
  'simserver_token' => '',
  'dlr_timeout' => '1',
  'schedule' => ''),
));

$response = curl_exec($curl);

curl_close($curl);
$responses = '{"status": true, "message": "Verification successful", "data": {"id": "'.$trans_id.'", "domain": "live", "status": "'.$paystatus.'", "reference": "'.$payref.'", "amount": "'.$transamt.'", "currency": "NGN"}}';
echo $responses;
}else{
die(mysqli_error($conn));
	
}


}
		}else{
$demail = $respons['data']['customer']['email'];
$checkmyprop = mysqli_query($conn, "SELECT * FROM myproperty WHERE payment_email='$demail' LIMIT 1");
	if(mysqli_num_rows($checkmyprop)>0){
		$mypropdet = mysqli_fetch_array($checkmyprop);


$upd = mysqli_query($conn, "INSERT INTO payment VALUES(NULL,'$me','$transamt','approved','$user[wallet_bal]','$user[userid]','virtual','$paid_at','Paystack','$gateway_response','$property','$payref','$user[state]','$user[planid]','$user[agent]','','$user[account_no]','$user[date]','$dprop[plot_size]','$user[branchid]','$user[second_agent]','','','','')");
if($upd){
	
	$sent = mysqli_query($conn, "UPDATE users SET wallet_bal=wallet_bal+$transamt WHERE id='$me'");
	mysqli_query($conn, "UPDATE myproperty SET amt_paid=amt_paid+$transamt,amt_remain=amt_remain-$transamt WHERE userid='$me' AND propertyid='$property'");
	
$messagen = "You are now subscribed to $yourplan payment plan. Your payment of N $damt for #$dprop[propuid] with Bliss Legacy Limited is successful. For enquiry call 07000325477. Thanks. Payment Reference #$payref";

$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$me','notification','Payment sucessful','$messagen','unread',now())");

$stackk = mysqli_query($conn, "INSERT INTO paystack_pay VALUES(NULL,'$me','$transamt','$property','$gateway_response','$payref','$paystatus','$trans_id',now(),'app')");


if($sent){

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://app.smartsmssolutions.com/io/api/client/v1/sms/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('token' => 'kr2Wm1k2bncwP3yWYy18nXbkgDvAqPmYFLNBPRDZ1wCPBWZF3M',
  'sender' => 'BlissPay',
  'to' => $user['phone'],
  'message' => $messagen,
  'type' => '0',
  'routing' => '4',
  'ref_id' => $token,
  'simserver_token' => '',
  'dlr_timeout' => '1',
  'schedule' => ''),
));

$response = curl_exec($curl);

curl_close($curl);
$responses = '{"status": true, "message": "Verification successful", "data": {"id": "'.$trans_id.'", "domain": "live", "status": "'.$paystatus.'", "reference": "'.$payref.'", "amount": "'.$transamt.'", "currency": "NGN"}}';
echo $responses;
}else{
die(mysqli_error($conn));
	
}


}


}


		}
		}else{
$dpayer = mysqli_fetch_array($checkp);

$checkwho = mysqli_query($conn, "SELECT id, fname,lname FROM users WHERE id='$dpayer[payer]'");
$payowner = mysqli_fetch_array($checkwho);
$dfullname = htmlentities($payowner['fname'])." ".htmlentities($payowner['lname']);
			$responses = '{"status":"alreadydone", "message":"This payment was already added to '.$dfullname.'\'s payment history"}';
	echo $responses;
		}
	//echo json_encode($respons);
	}else{
		echo json_encode($respons);
		
	}
  }else{

		echo json_encode($respons);
		
	}
  }




		//echo json_encode($respons);
		
	}
  }
?>