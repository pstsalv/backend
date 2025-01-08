<?php
header("Access-Control-Allow-Origin: *");
header('content-type: application/json; charset=utf-8');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include("conn.php");

$ipaddress = getenv('HTTP_CLIENT_IP')?:
getenv('HTTP_X_FORWARDED_FOR')?:
getenv('HTTP_X_FORWARDED')?:
getenv('HTTP_FORWARDED_FOR')?:
getenv('HTTP_FORWARDED')?:
getenv('REMOTE_ADDR');


$smscode = mt_rand(1000,9999);
$extra = date('dm');
$token = $extra.'0'.mt_rand(10000,99999);

$branch = mysqli_real_escape_string($conn,$_POST['branch']);
$fullname = mysqli_real_escape_string($conn,$_POST['fullnames']);
$email = mysqli_real_escape_string($conn,$_POST['email']);
if(isset($_POST['password'])){
	$password = mysqli_real_escape_string($conn,$_POST['password']);
}else{
	//echo '{"appstatus":"bademail", "fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "message":"Invalid password supplied"}';
	//exit();
	$password = "1234567";
}

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


$zone = mysqli_real_escape_string($conn, $_POST['zone']);
$branch = mysqli_real_escape_string($conn, $_POST['branch']);
if($zone=="Branch"){
$branchidy =$branch;
$planid = '';
$my_center = '';

}elseif($zone=="Outlet"){
$check = mysqli_query($conn, "SELECT * FROM allzones WHERE id='$branch'");
$allout = mysqli_fetch_array($check);
$branchidy = $allout['branchid'];
$planid = $branch;
$my_center = '';

}elseif($zone=="Center"){
$check = mysqli_query($conn, "SELECT * FROM centers WHERE id='$branch'");
$allout = mysqli_fetch_array($check);

$branchidy = $allout['branchid'];
$planid = $allout['outlet_id'];
$my_center = $branch;
}else{
$check = mysqli_query($conn, "SELECT * FROM centers WHERE id='$branch'");
$allout = mysqli_fetch_array($check);

$branchidy = $allout['branchid'];
$planid = "";
$my_center = "";
}

//caution
if($ipaddress=="197.210.226.155"){
echo '{"appstatus":"exist", "message":"You have been banned from the system kindly contact tech support"}';
}else{


$check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
if(mysqli_num_rows($check)>0){
	$wrong = mysqli_fetch_array($check);
	$acctypy = $wrong['account_type'];
	$regdate = date('d',strtotime($wrong['date']));
		echo '{"appstatus":"exist", "message":"An account with the email '.$email.' already registered as '.ucwords($acctypy).' using the '.$acctypy.' app. Change your email to continue registration"}';

}else{


if(isset($_POST['refcode']) && $_POST['refcode'] !=""){
	$refcode = mysqli_real_escape_string($conn,$_POST['refcode']);
	$checkagent = mysqli_query($conn, "SELECT * FROM users WHERE userid='$refcode'");
	if(mysqli_num_rows($checkagent)>0){
		$agents = mysqli_fetch_array($checkagent);
		


		$sent = mysqli_query($conn, "INSERT INTO users VALUES(NULL,'$fname','$lname','$email','$phone','$token','avatar.png','','','','','$refcode','$planid','unverified','$password','$customerType',now(),'$smscode','0','0','','','$agent','unpaid','','','','','','','','$branchidy','$agents[created_from]','0','0','','','','$my_center','$ipaddress','','','','','')") or die(mysqli_error($conn));
$newid =  mysqli_insert_id($conn);


	mysqli_query($conn, "INSERT INTO referral VALUES(NULL,'$refcode','$newid','$bonus','card','unpaid',now(),'unread')") or die(mysqli_error($conn));

	 mysqli_query($conn,"INSERT INTO notification VALUES(NULL,'$agents[0]','notification','New $customerType registered','$fname just joined using your referral code LEHM-$refcode. Your bonus is withdrawable once $fname makes payment.','unread',now())");
	}
}else{
	$sent = mysqli_query($conn, "INSERT INTO users VALUES(NULL,'$fname','$lname','$email','$phone','$token','avatar.png','','','','','','$planid','unverified','$password','$customerType',now(),'$smscode','0','0','','','$agent','unpaid','','','','','','','','$branchidy','','0','0','','','','$my_center','$ipaddress','','','','','')") or die(mysqli_error($conn));
$newid =  mysqli_insert_id($conn);

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

echo '{"appstatus":"done", "user_id":"'.$newid.'", "user_token":"'.$token.'", "phone":"'.$phone.'", "email":"'.$email.'","url":"/otp/'.$phone.'/'.$newid.'/","pincode":"", "walletbal":"0.00", "uncleared":"0.00", "pix":"male.png", "address":"Unset","bonus":"0.00"}';

}

	}
?>