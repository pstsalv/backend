<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");
$smscode = mt_rand(1000,9999);
$token = mt_rand(10000,99999);

$oldpass = mysqli_real_escape_string($conn,$_POST['oldpass']);
$newpass = mysqli_real_escape_string($conn,$_POST['newpass']);
$me = mysqli_real_escape_string($conn,$_POST['me']);

$check = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$me' AND password='$oldpass'");
if(mysqli_num_rows($check)<1){
	echo 'invalid';

}else{
	$in = mysqli_fetch_array($check);
$sent = mysqli_query($conn, "UPDATE users SET password='$newpass' WHERE id='$me'") or die(mysqli_error($conn));

$message = "Your password to access OgaBliss has been changed. If you didnt make this change contact Support via email support@blisslegacy.com.ng.";

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
echo 'ok';


	}
?>