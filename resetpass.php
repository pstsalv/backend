<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
header('content-type: application/json; charset=utf-8');
include_once("conn.php");
$smscode = mt_rand(1000,9999);
$token = mt_rand(10000,99999);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
if(isset($_POST['apptype'])){
$apptype = mysqli_real_escape_string($conn, $_POST['apptype']);
}else{
	$apptype = "agent";
}
$result = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE account_type='$apptype' AND phone='$phone'");
if (mysqli_num_rows($result)>0){
	$user = mysqli_fetch_array($result);
	
	mysqli_query($conn,"UPDATE users SET otpcode='$smscode' WHERE id='$user[0]'");
	
$message = "Use this digits $smscode to complete your password change on BlissPay";

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
  'phone' => $phone,
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

if ($response === false) {
    //echo 'Error: ' .curl_error($ch);
} else {
    $respons = json_decode($response, true);

	 if (isset($respons['successful'])){
		 mysqli_query($conn, "INSERT INTO smscharges VALUES(NULL,'$user[id]','otp','BlissPay','$message','$respons[comment]','$respons[units_calculated]','7',now(),'$respons[successful]','$respons[sms_pages]')");
	 }
	
}

	$to = $user['email'];
	$headers = "From: no-reply@blisslegacy.com.ng" . "\r\n" .
	$subject = "Password Reset Code";
	$message = "You recently requested to change your OgaBliss App password. Here is your code: $smscode. DO not share";
	mail($to,$subject,$message,$headers);
	

$data = array(
		'status'=>'good', 
		'userid'=>$user[0], 
		'email'=>$user['email'], 
		'phone'=>$user['phone'], 
		'message'=>'Account Found'
		);
$str = json_encode($data);
echo $str;

}else{
echo '{"status":"invalid","message":"No user found with that email"}';
}
?>