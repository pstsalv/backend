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
		//check to collect property id
		$checkpid = mysqli_query($conn, "SELECT id,title FROM property WHERE id='$property'");
		$dprop = mysqli_fetch_array($checkpid);
		
		//check if payment already exist
		$checkp = mysqli_query($conn, "SELECT * FROM paystack_pay WHERE reference='$payref'");
		if(mysqli_num_rows($checkp)<1){
			
			//credit the user
			$checku = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position,branchid FROM users WHERE id='$me'");
			$user = mysqli_fetch_array($checku);
	
	if($user['email']==$respons['data']['customer']['email']){
			$upd = mysqli_query($conn, "INSERT INTO payment VALUES(NULL,'$me','$transamt','approved','$user[wallet_bal]','$user[userid]','virtual',now(),'Paystack','$gateway_response','$dprop[id]','$payref','','$user[date]','$user[branchid]')");
if($upd){
	
	$sent = mysqli_query($conn, "UPDATE users SET wallet_bal=wallet_bal+$transamt WHERE id='$me'");
	mysqli_query($conn, "UPDATE myproperty SET amt_paid=amt_paid+$transamt,amt_remain=amt_remain-$transamt WHERE userid='$me' AND propuid='$dprop[id]'");
	
$messagen = "Your payment of $damt is successful. This should now reflect on your available payment balance. Regards. Payment Reference #$payref";

$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$me','notification','Payment sucessful','$messagen','unread',now())");

$stackk = mysqli_query($conn, "INSERT INTO paystack_pay VALUES(NULL,'$me','$transamt','$dprop[id]','$gateway_response','$payref','$paystatus','$trans_id',now(),'app')");


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
			$responses = '{"status":"wrongcustomer", "message":"The payment was successful but this payment belongs to another bliss legacy customer - '.$transemail.'"}';
	echo $responses;
		}
		}else{
			$responses = '{"status":"alreadydone", "message":"This payment was already added to customer payment history"}';
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
?>