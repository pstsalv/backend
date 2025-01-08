<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('content-type: application/json; charset=utf-8');
header('Cache-Control: no-cache'); 
include_once("conn.php");
$smscode = mt_rand(1000,9999);
$token = mt_rand(9999,99999);
if($_REQUEST['phone']=='' || $_REQUEST['password']==''){
	$responses = json_encode('{"fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":"empty", "message":"Empty login details"}');
	echo $responses;
	exit();
}else{
$myusername = mysqli_real_escape_string($conn, $_REQUEST['phone']);
$acctype = mysqli_real_escape_string($conn, $_REQUEST['acctype']);
$mypassword1 = mysqli_real_escape_string($conn, $_REQUEST['password']);
$mypassword = preg_replace('/\s+/', '', $mypassword1);
$result = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE phone='$myusername' AND password='$mypassword' AND account_type='superadmin' AND status!='deleted' LIMIT 1") or die(mysqli_error($conn));
if($result){
if(mysqli_num_rows($result)<1){
	$responses = json_encode('{"fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":"invalid", "message":"Invalid login details"}');
	echo $responses;
	exit();
}else{
	$in = mysqli_fetch_array($result);
if($in['status'] =='suspended' || $in['status'] =='banned'){
	$responses = json_encode('{"appstatus":"suspended", "fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":"suspended", "message":"Your account has been suspended"}');
	echo $responses;
	
	exit();
}elseif($in['status'] =='reset' || $in['status'] ==''){
	
$responses = json_encode('{"user_id":"'.$in[0].'", "fname":"'.ucwords($in['fname']).'", "lname":"'.ucfirst($in['lname']).'", "email":"'.$in['email'].'", "phone":"'.$in['phone'].'", "foto":"'.rawurlencode($in['pix']).'", "gender":"'.$in['gender'].'", "status":"reset", "region":"'.$in['region'].'", "state":"'.$in['state'].'", "acc_type":"'.$in['account_type'].'", "walletbal":"'.$in['wallet_bal'].'", "uncleared":"'.$in['uncleared'].'", "user_token":"'.$in['userid'].'", "myaccno":"'.$in['account_no'].'", "address":"'.$in['address'].'", "dob":"'.$in['dob'].'", "bonus":"'.$mybalp.'"}');

	echo $responses;
	
	exit();
}elseif($in['status'] =='unverified' || $in['status'] =='incomplete'){
	
$message = "Use this digits $smscode to verify your account on Bliss Pay - Agent App";

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://app.smartsmssolutions.com/io/api/client/v1/voiceotp/send/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('token' => 'kr2Wm1k2bncwP3yWYy18nXbkgDvAqPmYFLNBPRDZ1wCPBWZF3M',
  'phone' => $in['phone'],
  'otp' => $smscode,
  'class' => 'AEYBPV3VKA',
  'ref_id' => $token),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;

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
  'to' => $in['phone'],
  'message' => $message,
  'type' => '0',
  'routing' => '4',
  'ref_id' => $token,
  'simserver_token' => '',
  'dlr_timeout' => '1',
  'schedule' => ''),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;

$to = $in['email'];
	$headers = "From: no-reply@blisslegacy.com.ng" . "\r\n" .
	$subject = "Verification Code";
	$message = "Use this digits $smscode to complete your verify your account on OgaBliss";
	//mail($to,$subject,$message,$headers);
	
mysqli_query($conn,"UPDATE users SET otpcode='$smscode' WHERE id='$in[0]'");
	
	$responses = json_encode('{"status":"unverified", "url":"/otp/'.$in['phone'].'/'.$in['id'].'", "message":"Your account is unverified"}');
	echo $responses;
	
	exit();
}elseif($in['status'] =='active' || $in['status']=='incomplete' || $in['status']=='verified'){

if(isset($_POST['mytoken']) && $_POST['mytoken']!=""){
$mytoken = $_POST['mytoken'];

$check = mysqli_query($conn, "SELECT id,owner,token,date,type,owner_token,name FROM tokens WHERE owner='$in[id]'");
if($check && mysqli_num_rows($check)<1){
mysqli_query($conn, "INSERT INTO tokens VALUES(NULL,'$in[id]','$mytoken',now(),'Fresh login token','$in[id]','$in[fname]')") or die(mysqli_error($conn));
}else{
	mysqli_query($conn, "UPDATE tokens SET token='$mytoken',type='Refreshd login token',date=now(),name='$in[fname]' WHERE owner='$in[id]'");
}


}


  $checkamtpp = mysqli_query($conn,"SELECT SUM(amount) AS amttpaid FROM  referral WHERE pay_status='unpaid' AND agentid='$in[0]'");
  
$rowpp = mysqli_fetch_assoc($checkamtpp);
$sumpp = $rowpp['amttpaid'];
$totalpayd = "$sumpp";
if($totalpayd!==""){
$mybalp = number_format($totalpayd,2);
}else{
$mybalp = "0.00";
};


$responses = json_encode('{"user_id":"'.$in[0].'", "fname":"'.ucwords($in['fname']).'", "lname":"'.ucfirst($in['lname']).'", "email":"'.$in['email'].'", "phone":"'.$in['phone'].'", "foto":"'.rawurlencode($in['pix']).'", "gender":"'.$in['gender'].'", "status":"'.$in['status'].'", "region":"'.$in['region'].'", "state":"'.$in['state'].'", "acc_type":"'.$in['account_type'].'", "walletbal":"'.$in['wallet_bal'].'", "uncleared":"'.$in['uncleared'].'", "user_token":"'.$in['userid'].'", "myaccno":"'.$in['account_no'].'", "address":"'.$in['address'].'", "dob":"'.$in['dob'].'", "bonus":"'.$mybalp.'"}');

	echo $responses;
	
exit();
}
}
}else{
	$responses = json_encode('{"appstatus":"unknown", "fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":""unknown, "message":"Unknown error occured"}');
	echo $responses;
}
}
?>