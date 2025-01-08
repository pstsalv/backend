<?php
header("Access-Control-Allow-Origin: *");
header('content-type: application/json; charset=utf-8');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");
$smscode = mt_rand(1000,9999);
$extra = date('dm');
$token = $extra.'0'.mt_rand(10000,99999);

$fullname = mysqli_real_escape_string($conn,$_POST['fullnames']);
$email = mysqli_real_escape_string($conn,$_POST['email']);

$password = mysqli_real_escape_string($conn,$_POST['password']);
$phone1 = mysqli_real_escape_string($conn,$_POST['phone']);
$phone = preg_replace('/\s+/', '', $phone1);
if(isset($_POST['acctype']) && $_POST['acctype'] !=""){
	$customerType = mysqli_real_escape_string($conn,$_POST['acctype']);
}else{
	$customerType = "customer";
}
if($customerType=="agent"){
	$bonus = "2000";
}else{
	$bonus = "1000";
}
if(isset($_POST['refcode']) && $_POST['refcode'] !=""){
	$agent = mysqli_real_escape_string($conn,$_POST['refcode']);
}else{
	$agent = "";
}
if(stripos($fullname, ' ')==false){
	$fname = ucwords($fullname);
	$lname = '';
}else{
list($fname, $lname) = explode(' ', $fullname,2);

$fname = ucwords($fname);
$lname = ucwords($lname);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
 echo '{"appstatus":"bademail", "fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "message":"Invalid email supplied"}';
	exit();
}





if($phone=="09169984785" || $phone=="08122260147" || $phone=="08052135281"){
	
	
	

$check = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE phone='$phone' AND account_type='$customerType' LIMIT 1");
if(mysqli_num_rows($check)>0){
	$wrong = mysqli_fetch_array($check);
	$acctypy = $wrong['account_type'];
	
	$removeit = mysqli_query($conn,"DELETE FROM users WHERE phone='$phone'");
	if($removeit){
		$sent = mysqli_query($conn, "INSERT INTO users VALUES(NULL,'$fname','$lname','$email','$phone','$token','avatar.png','','','','','','','unverified','$password','$customerType',now(),'$smscode','0','0','','','$agent','','')") or die(mysqli_error($conn));
$newid =  mysqli_insert_id($conn);

if(isset($_POST['refcode']) && $_POST['refcode'] !=""){
	$refcode = mysqli_real_escape_string($conn,$_POST['refcode']);
	$checkagent = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE userid='$refcode'");
	if(mysqli_num_rows($checkagent)>0){
		$agents = mysqli_fetch_array($checkagent);
	mysqli_query($conn, "INSERT INTO referral VALUES(NULL,'$refcode','$newid','$bonus','card','unpaid',now(),'unread')") or die(mysqli_error($conn));
	
	 mysqli_query($conn,"INSERT INTO notification VALUES(NULL,'$agents[0]','notification','New $customerType registered','$fname just joined using your referral code LEHM-$refcode. Your bonus is withdrawable once $fname makes first payment.','unread',now())");
	}
}
if(isset($_POST['mytoken']) && $_POST['mytoken']!=""){
$mytoken = $_POST['mytoken'];

$check = mysqli_query($conn, "SELECT id,owner,token,date,type,owner_token,name FROM tokens WHERE owner='$newid'");
if($check && mysqli_num_rows($check)<1){
mysqli_query($conn, "INSERT INTO tokens VALUES(NULL,'$newid','$mytoken',now(),'Fresh login token','$newid','$fname')") or die(mysqli_error($conn));
}else{
	mysqli_query($conn, "UPDATE tokens SET token='$mytoken',type='Refreshd login token',date=now(),name='$fname' WHERE owner='$newid'");
}
}

$message = "Use this digits $smscode to complete your registration on Bliss Pay - $customerType app";

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
  'to' => $phone,
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
echo '{"appstatus":"done", "user_id":"'.$newid.'", "user_token":"'.$token.'", "phone":"'.$phone.'", "email":"'.$email.'","url":"/otp/'.$phone.'/'.$newid.'/","pincode":"", "walletbal":"0.00", "uncleared":"0.00", "pix":"male.png", "address":"Unset","bonus":"0.00"}';

	}else{
		
		
		
		$sent = mysqli_query($conn, "INSERT INTO users VALUES(NULL,'$fname','$lname','$email','$phone','$token','avatar.png','','','','','','','unverified','$password','$customerType',now(),'$smscode','0','0','','','$agent','','')") or die(mysqli_error($conn));
$newid =  mysqli_insert_id($conn);

if(isset($_POST['refcode']) && $_POST['refcode'] !=""){
	$refcode = mysqli_real_escape_string($conn,$_POST['refcode']);
	$checkagent = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE userid='$refcode'");
	if(mysqli_num_rows($checkagent)>0){
		$agents = mysqli_fetch_array($checkagent);
	mysqli_query($conn, "INSERT INTO referral VALUES(NULL,'$refcode','$newid','$bonus','card','unpaid',now(),'unread')") or die(mysqli_error($conn));
	
	 mysqli_query($conn,"INSERT INTO notification VALUES(NULL,'$agents[0]','notification','New $customerType registered','$fname just joined using your referral code LEHM-$refcode. Your bonus is withdrawable once $fname makes first payment.','unread',now())");
	}
}
if(isset($_POST['mytoken']) && $_POST['mytoken']!=""){
$mytoken = $_POST['mytoken'];

$check = mysqli_query($conn, "SELECT id,owner,token,date,type,owner_token,name FROM tokens WHERE owner='$newid'");
if($check && mysqli_num_rows($check)<1){
mysqli_query($conn, "INSERT INTO tokens VALUES(NULL,'$newid','$mytoken',now(),'Fresh login token','$newid','$fname')") or die(mysqli_error($conn));
}else{
	mysqli_query($conn, "UPDATE tokens SET token='$mytoken',type='Refreshd login token',date=now(),name='$fname' WHERE owner='$newid'");
}
}

$message = "Use this digits $smscode to complete your registration on Bliss Pay - $customerType app";

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
  'to' => $phone,
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
echo '{"appstatus":"done", "user_id":"'.$newid.'", "user_token":"'.$token.'", "phone":"'.$phone.'", "email":"'.$email.'","url":"/otp/'.$phone.'/'.$newid.'/","pincode":"", "walletbal":"0.00", "uncleared":"0.00", "pix":"male.png", "address":"Unset","bonus":"0.00"}';




	}

}else{
$sent = mysqli_query($conn, "INSERT INTO users VALUES(NULL,'$fname','$lname','$email','$phone','$token','avatar.png','','','','','','','unverified','$password','$customerType',now(),'$smscode','0','0','','','$agent','','')") or die(mysqli_error($conn));
$newid =  mysqli_insert_id($conn);

if(isset($_POST['refcode']) && $_POST['refcode'] !=""){
	$refcode = mysqli_real_escape_string($conn,$_POST['refcode']);
	$checkagent = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE userid='$refcode'");
	if(mysqli_num_rows($checkagent)>0){
		$agents = mysqli_fetch_array($checkagent);
	mysqli_query($conn, "INSERT INTO referral VALUES(NULL,'$refcode','$newid','$bonus','card','unpaid',now(),'unread')") or die(mysqli_error($conn));

	 mysqli_query($conn,"INSERT INTO notification VALUES(NULL,'$agents[0]','notification','New $customerType registered','$fname just joined using your referral code LEHM-$refcode. Your bonus is withdrawable once $fname makes first payment.','unread',now())");
	}
}
if(isset($_POST['mytoken']) && $_POST['mytoken']!=""){
$mytoken = $_POST['mytoken'];

$check = mysqli_query($conn, "SELECT id,owner,token,date,type,owner_token,name FROM tokens WHERE owner='$newid'");
if($check && mysqli_num_rows($check)<1){
mysqli_query($conn, "INSERT INTO tokens VALUES(NULL,'$newid','$mytoken',now(),'Fresh login token','$newid','$fname')") or die(mysqli_error($conn));
}else{
	mysqli_query($conn, "UPDATE tokens SET token='$mytoken',type='Refreshd login token',date=now(),name='$fname' WHERE owner='$newid'");
}
}

$message = "Use this digits $smscode to complete your registration on Bliss Pay - $customerType app";

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
  'to' => $phone,
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
echo '{"appstatus":"done", "user_id":"'.$newid.'", "user_token":"'.$token.'", "phone":"'.$phone.'", "email":"'.$email.'","url":"/otp/'.$phone.'/'.$newid.'/","pincode":"", "walletbal":"0.00", "uncleared":"0.00", "pix":"male.png", "address":"Unset","bonus":"0.00"}';



	}
	
	
	
	
	
}else{

$check = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE email='$email' AND account_type='$customerType'  AND status!='deleted' LIMIT 1");
if(mysqli_num_rows($check)>0){
	$wrong = mysqli_fetch_array($check);
	$acctypy = $wrong['account_type'];
	$regdate = date('d',strtotime($wrong['date']));

	$today = date('d');
	if($wrong['wallet_bal']==0 && $regdate=$today){
	$removeit = mysqli_query($conn,"UPDATE users SET status='deleted' WHERE id='$wrong[id]'");
	if($removeit){
		
		
		$sent = mysqli_query($conn, "INSERT INTO users VALUES(NULL,'$fname','$lname','$email','$phone','$token','avatar.png','','','','','','','unverified','$password','$customerType',now(),'$smscode','0','0','','','$agent','','')") or die(mysqli_error($conn));
$newid =  mysqli_insert_id($conn);

if(isset($_POST['refcode']) && $_POST['refcode'] !=""){
	$refcode = mysqli_real_escape_string($conn,$_POST['refcode']);
	$checkagent = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE userid='$refcode'");
	if(mysqli_num_rows($checkagent)>0){
		$agents = mysqli_fetch_array($checkagent);
	mysqli_query($conn, "INSERT INTO referral VALUES(NULL,'$refcode','$newid','$bonus','card','unpaid',now(),'unread')") or die(mysqli_error($conn));

	 mysqli_query($conn,"INSERT INTO notification VALUES(NULL,'$agents[0]','notification','New $customerType registered','$fname just joined using your referral code LEHM-$refcode. Your bonus is withdrawable once $fname makes first payment.','unread',now())");
	}
}
if(isset($_POST['mytoken']) && $_POST['mytoken']!=""){
$mytoken = $_POST['mytoken'];

$check = mysqli_query($conn, "SELECT id,owner,token,date,type,owner_token,name FROM tokens WHERE owner='$newid'");
if($check && mysqli_num_rows($check)<1){
mysqli_query($conn, "INSERT INTO tokens VALUES(NULL,'$newid','$mytoken',now(),'Fresh login token','$newid','$fname')") or die(mysqli_error($conn));
}else{
	mysqli_query($conn, "UPDATE tokens SET token='$mytoken',type='Refreshd login token',date=now(),name='$fname' WHERE owner='$newid'");
}
}

$message = "Use this digits $smscode to complete your registration on Bliss Pay - $customerType app";

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
  'to' => $phone,
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
echo '{"appstatus":"done", "user_id":"'.$newid.'", "user_token":"'.$token.'", "phone":"'.$phone.'", "email":"'.$email.'","url":"/otp/'.$phone.'/'.$newid.'/","pincode":"", "walletbal":"0.00", "uncleared":"0.00", "pix":"male.png", "address":"Unset","bonus":"0.00"}';

		
	}
	
	}else{
		echo '{"appstatus":"exist", "message":"An account with the email '.$email.' already registered as '.ucwords($acctypy).' using the '.$acctypy.' app. Change your email to continue registration"}';
	}

}else{
$sent = mysqli_query($conn, "INSERT INTO users VALUES(NULL,'$fname','$lname','$email','$phone','$token','avatar.png','','','','','','','unverified','$password','$customerType',now(),'$smscode','0','0','','','$agent','','')") or die(mysqli_error($conn));
$newid =  mysqli_insert_id($conn);

if(isset($_POST['refcode']) && $_POST['refcode'] !=""){
	$refcode = mysqli_real_escape_string($conn,$_POST['refcode']);
	$checkagent = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE userid='$refcode'");
	if(mysqli_num_rows($checkagent)>0){
		$agents = mysqli_fetch_array($checkagent);
	mysqli_query($conn, "INSERT INTO referral VALUES(NULL,'$refcode','$newid','$bonus','card','unpaid',now(),'unread')") or die(mysqli_error($conn));

	 mysqli_query($conn,"INSERT INTO notification VALUES(NULL,'$agents[0]','notification','New $customerType registered','$fname just joined using your referral code LEHM-$refcode. Your bonus is withdrawable once $fname makes first payment.','unread',now())");
	}
}
if(isset($_POST['mytoken']) && $_POST['mytoken']!=""){
$mytoken = $_POST['mytoken'];

$check = mysqli_query($conn, "SELECT id,owner,token,date,type,owner_token,name FROM tokens WHERE owner='$newid'");
if($check && mysqli_num_rows($check)<1){
mysqli_query($conn, "INSERT INTO tokens VALUES(NULL,'$newid','$mytoken',now(),'Fresh login token','$newid','$fname')") or die(mysqli_error($conn));
}else{
	mysqli_query($conn, "UPDATE tokens SET token='$mytoken',type='Refreshd login token',date=now(),name='$fname' WHERE owner='$newid'");
}
}

$message = "Use this digits $smscode to complete your registration on Bliss Pay - $customerType app";

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
  'to' => $phone,
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
echo '{"appstatus":"done", "user_id":"'.$newid.'", "user_token":"'.$token.'", "phone":"'.$phone.'", "email":"'.$email.'","url":"/otp/'.$phone.'/'.$newid.'/","pincode":"", "walletbal":"0.00", "uncleared":"0.00", "pix":"male.png", "address":"Unset","bonus":"0.00"}';



	}
	
	
}
	
?>