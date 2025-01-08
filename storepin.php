<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
include("conn.php");
$token = mt_rand(9999,99999);
$pincodey = mysqli_real_escape_string($conn,$_GET['pincode']);
$me = mysqli_real_escape_string($conn,$_GET['me']);
$pincode = base64_encode($pincodey);
$check = mysqli_query($conn, "SELECT * FROM users WHERE id='$me'") or  die(mysqli_error($conn));
if(mysqli_num_rows($check)>0){
	$in = mysqli_fetch_array($check);
	
	$checkal = mysqli_query($conn, "SELECT pincode FROM users WHERE id='$me' AND pincode=''");
	if(mysqli_num_rows($checkal)>0){
	$upit = mysqli_query($conn, "UPDATE users SET pincode='$pincode' WHERE id='$me' AND pincode=''");
	if($upit){
		$message = 'Your Wallet Pin has been created successfully, if you didnt create this pin click this link to lock your wallet immediately https://blisslegacy.com/lock?agent='.base64_encode($me);

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
	}
	
	print "ok";
	}else{
		echo 'already set';
	}
	}else{
		 print "invalid";
	}
?>