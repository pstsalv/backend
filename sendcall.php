<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");
$smscode = mt_rand(1000,9999);
$token = mt_rand(10000,99999);
$me = mysqli_real_escape_string($conn,$_GET['me']);

$go =mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$me'");
if(mysqli_num_rows($go)>0){
	$in = mysqli_fetch_array($go);
	
	mysqli_query($conn, "UPDATE users SET otpcode='$smscode' WHERE id='$me'");

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
echo $response;

//echo 'done';
}else{
	echo 'Unknown error';
}
?>