<?php
include('conn.php');
$duid = mysqli_real_escape_string($conn, $_GET['userid']);
$token = mt_rand(99999,999999);
$smscode2 = mt_rand(10000,99999);
$check = mysqli_query($conn, "SELECT * FROM users WHERE id='$duid'");
if(mysqli_num_rows($check)>0){
	$user = mysqli_fetch_array($check);
	$do = mysqli_query($conn,"UPDATE users SET pincode='' WHERE id='$user[0]'");
	mysqli_query($conn,"UPDATE mybonus SET status='approved' WHERE owner='$user[0]'");
	if($do){
	echo 'Your Pincode has been reset';
	
	$message = 'Your Profile has been reactivated, if you didnt request this reactivationkindly visit https://blisslegacy.com/lock?agent='.base64_encode($user['id']);

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

}else{
		echo 'Something went wrong, kindly retry';
	}
	
}
?>