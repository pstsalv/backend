<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('content-type: application/json; charset=utf-8');
header('Cache-Control: no-cache'); 
$ipaddress = getenv('HTTP_CLIENT_IP')?:
getenv('HTTP_X_FORWARDED_FOR')?:
getenv('HTTP_X_FORWARDED')?:
getenv('HTTP_FORWARDED_FOR')?:
getenv('HTTP_FORWARDED')?:
getenv('REMOTE_ADDR');

include_once("conn.php");
$smscode = mt_rand(1000,9999);
$smscode2 = mt_rand(10000,99999);
$token = mt_rand(9999,99999);

if($ipaddress=="197.210.226.155"){

echo '{"appstatus":"exist", "message":"You have been banned from the system kindly contact tech support"}';
}else{
if($_REQUEST['phone']=='' || $_REQUEST['password']==''){
	$responses = json_encode('{"fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":"empty", "message":"Empty login details"}');
	echo $responses;
	exit();
}else{
$myusername = mysqli_real_escape_string($conn, $_REQUEST['phone']);
$mypassword1 = mysqli_real_escape_string($conn, $_REQUEST['password']);
$deviceid = mysqli_real_escape_string($conn, $_REQUEST['deviceid']);
$mypassword = preg_replace('/\s+/', '', $mypassword1);

$checkid = mysqli_query($conn, "SELECT id FROM users WHERE (phoneid='$deviceid' OR phoneid='') AND phone='$myusername'");


$result = mysqli_query($conn, "SELECT * FROM users WHERE phone='$myusername' AND password='$mypassword' AND account_type!='customer' AND status!='deleted' LIMIT 1") or die(mysqli_error($conn));
if($result){
if(mysqli_num_rows($result)<1){
	$responses = json_encode('{"fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":"invalid", "message":"Invalid login details"}');
	echo $responses;
	exit();
}else{
	$in = mysqli_fetch_array($result);
	
if($in['phoneid'] !=""){


if($in['phoneid'] !=$deviceid){

$str = json_encode('{"fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":"invalid", "message":"You are not allowed to login to someone else account"}');
mysqli_query($conn, "UPDATE users SET phoneid='' WHERE id='$in[0]'");

}else{

if($in['ipaddress']==""){
mysqli_query($conn,"UPDATE users SET ipaddress='$ipaddress' WHERE id='$in[0]'");
}

	// create new otp and lock wallet
mysqli_query($conn,"UPDATE mybonus SET status='locked' WHERE owner='$in[0]'");
mysqli_query($conn,"UPDATE users SET leftover_batch2='$smscode2', phoneid='$deviceid' WHERE id='$in[0]'");

	//notify owner 
	$logdate = date('d-m-Y, h:i:s A');
	$sendnoty =  mysqli_query($conn,"INSERT INTO notification VALUES(NULL,'$in[id]','login','Login Notification','Your account was successfully logged into today at $logdate via IP Address: $ipaddress, inorder to secure your account, we have limited your account and sent a link to reactivate the account to your registered phone number. if this was not you, kindly change your password immediately','unread',now())");


$message = 'We limited your blisspay profile due to an unsual activity. visit https://blisslegacy.com/whois?agent='.$smscode2.' to regain access';

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
  CURLOPT_POSTFIELDS => array('token' => 'G1tisaOnfDfmsz0XbHEg1p05PUDn2JJCYMHeVJpt03PpL1GU7M',
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




if($in['status'] =='suspended' || $in['status'] =='banned'){
	$responses = json_encode('{"appstatus":"suspended", "fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":"suspended", "message":"Your account has been suspended"}');
	echo $responses;
	
	exit();
}elseif($in['status'] =='reset' || $in['status'] ==''){
	$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://smartsmssolutions.com/io/client/v1/voiceotp/send/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('token' => 'G1tisaOnfDfmsz0XbHEg1p05PUDn2JJCYMHeVJpt03PpL1GU7M','phone' => $phone,'otp' => $smscode,'class' => 'AEYBPV3VKA','ref_id' => $token),
));

$response = curl_exec($curl);

curl_close($curl);
	
$responses = json_encode('{"user_id":"'.$in[0].'", "fname":"'.ucwords($in['fname']).'", "lname":"'.ucfirst($in['lname']).'", "email":"'.$in['email'].'", "phone":"'.$in['phone'].'", "foto":"'.rawurlencode($in['pix']).'", "gender":"'.$in['gender'].'", "status":"reset", "region":"'.$in['region'].'", "state":"'.$in['state'].'", "acc_type":"'.$in['account_type'].'", "walletbal":"'.$in['wallet_bal'].'", "uncleared":"'.$in['uncleared'].'", "user_token":"'.$in['userid'].'", "myaccno":"'.$in['account_no'].'", "address":"'.$in['address'].'", "dob":"'.$in['dob'].'", "bonus":"'.$mybalp.'"}');

	echo $responses;
	
	exit();
}elseif($in['status'] =='unverified' || $in['status'] =='incomplete'){
$curl = curl_init();
	curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://smartsmssolutions.com/io/client/v1/voiceotp/send/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('token' => 'G1tisaOnfDfmsz0XbHEg1p05PUDn2JJCYMHeVJpt03PpL1GU7M','phone' => $phone,'otp' => $smscode,'class' => 'AEYBPV3VKA','ref_id' => $token),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;

$to = $in['email'];
	$headers = "From: info@blisslegacy.com" . "\r\n" .
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


$responses = json_encode('{"user_id":"'.$in[0].'", "fname":"'.$in['fname'].'", "lname":"'.$in['lname'].'", "email":"'.$in['email'].'", "phone":"'.$in['phone'].'", "foto":"'.rawurlencode($in['pix']).'", "gender":"'.$in['gender'].'", "status":"'.$in['status'].'", "region":"'.$in['region'].'", "state":"'.$in['state'].'", "acc_type":"'.$in['account_type'].'", "walletbal":"'.$in['wallet_bal'].'", "uncleared":"'.$in['uncleared'].'", "user_token":"'.$in['userid'].'", "myaccno":"'.$in['account_no'].'", "address":"'.$in['address'].'", "dob":"'.$in['dob'].'", "bonus":"'.$mybalp.'"}');

	echo $responses;
	
exit();
}





}

}else{

if($in['ipaddress']==""){
mysqli_query($conn,"UPDATE users SET ipaddress='$ipaddress' WHERE id='$in[0]'");
}

	// create new otp and lock wallet
mysqli_query($conn,"UPDATE mybonus SET status='locked' WHERE owner='$in[0]'");
mysqli_query($conn,"UPDATE users SET leftover_batch2='$smscode2', phoneid='$deviceid' WHERE id='$in[0]'");

	//notify owner 
	$logdate = date('d-m-Y, h:i:s A');
	$sendnoty =  mysqli_query($conn,"INSERT INTO notification VALUES(NULL,'$in[id]','login','Login Notification','Your account was successfully logged into today at $logdate via IP Address: $ipaddress, inorder to secure your account, we have limited your account and sent a link to reactivate the account to your registered phone number. if this was not you, kindly change your password immediately','unread',now())");

if($in['status'] =='suspended' || $in['status'] =='banned'){
	$responses = json_encode('{"appstatus":"suspended", "fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":"suspended", "message":"Your account has been suspended"}');
	echo $responses;
	
	exit();
}elseif($in['status'] =='reset' || $in['status'] ==''){
	
		
$message = 'We limited your blisspay profile due to an unsual activity. visit https://blisslegacy.com/whois?agent='.$smscode2.' to regain access';

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
  CURLOPT_POSTFIELDS => array('token' => 'G1tisaOnfDfmsz0XbHEg1p05PUDn2JJCYMHeVJpt03PpL1GU7M',
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


	
$responses = json_encode('{"user_id":"'.$in[0].'", "fname":"'.ucwords($in['fname']).'", "lname":"'.ucfirst($in['lname']).'", "email":"'.$in['email'].'", "phone":"'.$in['phone'].'", "foto":"'.rawurlencode($in['pix']).'", "gender":"'.$in['gender'].'", "status":"reset", "region":"'.$in['region'].'", "state":"'.$in['state'].'", "acc_type":"'.$in['account_type'].'", "walletbal":"'.$in['wallet_bal'].'", "uncleared":"'.$in['uncleared'].'", "user_token":"'.$in['userid'].'", "myaccno":"'.$in['account_no'].'", "address":"'.$in['address'].'", "dob":"'.$in['dob'].'", "bonus":"'.$mybalp.'"}');

	echo $responses;
	
	exit();
}elseif($in['status'] =='unverified' || $in['status'] =='incomplete'){
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://smartsmssolutions.com/io/client/v1/voiceotp/send/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('token' => 'G1tisaOnfDfmsz0XbHEg1p05PUDn2JJCYMHeVJpt03PpL1GU7M','phone' => $phone,'otp' => $smscode,'class' => 'AEYBPV3VKA','ref_id' => $token),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;

//echo $response;

$to = $in['email'];
	$headers = "From: info@blisslegacy.com" . "\r\n" .
	$subject = "Verification Code";
	$message = "Use this digits $smscode to complete your verify your account on BlissPay";
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
}else{
$responses = json_encode('{"appstatus":"unknown", "fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":"'.$in['status'].'", "message":"Unknown error occured"}');
	echo $responses;
}
}
}
}else{
	$responses = json_encode('{"appstatus":"unknown", "fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":"unknown", "message":"Unknown error occured"}');
	echo $responses;
}

}
}
?>