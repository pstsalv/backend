<?php
header("access-control-allow-origin: *");
header('Content-Type: text/event-stream');
//header('content-type: application/json; charset=utf-8');
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');
include('conn.php');
$duid = mysqli_real_escape_string($conn, $_GET['agent']);
$uid = base64_decode($duid);
$token = mt_rand(10000,99999);
$smscode2 = mt_rand(10000,99999);
$check = mysqli_query($conn, "UPDATE mybonus SET status='locked' WHERE owner='$duid'");
if($check){
	$checkac = mysqli_query($conn, "SELECT * FROM users WHERE id='$duid'");
	$in = mysqli_fetch_array($checkac);
	
	mysqli_query($conn,"UPDATE users SET leftover_batch2='$smscode2' WHERE id='$in[0]'");
	
	$message = 'We limited your account due to an unsual activity. Kindly click this link to regain full access https://blisslegacy.com/whois?agent='.$smscode2;

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
	?>
	
	<h1>Your account is now secured, contact tech support to unlock it</h1>
<?php
}
?>