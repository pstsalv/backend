<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('content-type: application/json; charset=utf-8');
header('Cache-Control: no-cache');
include_once("conn.php");

$ip = getenv('HTTP_CLIENT_IP')?:
getenv('HTTP_X_FORWARDED_FOR')?:
getenv('HTTP_X_FORWARDED')?:
getenv('HTTP_FORWARDED_FOR')?:
getenv('HTTP_FORWARDED')?:
getenv('REMOTE_ADDR');
$token = '0'.mt_rand(10000,99999);

$bankcd	= mysqli_real_escape_string($conn, $_POST['bankcd']);
$account_bank_name = mysqli_real_escape_string($conn, $_POST['account_bank_name']);
$account_number	= mysqli_real_escape_string($conn, $_POST['accno']);
$reference = 'blisspay-'.mt_rand(1000000,9999990);
$me = mysqli_real_escape_string($conn, $_POST['me']);

$account_nm	= mysqli_real_escape_string($conn, $_POST['account_nm']);
$phonenum	= mysqli_real_escape_string($conn, $_POST['phonenum']);
$recipient_code	= mysqli_real_escape_string($conn, $_POST['recipient_code']);

$today = date('d-m-Y');


function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

$myuuid = guidv4();
//check if user entered pincode
	
	 mysqli_query($conn,"INSERT INTO benefitiary VALUES(NULL,'$account_bank_name','$account_number','$account_nm','$me',now(),'$account_bank_name','$recipient_code','$bankcd')") or die(mysqli_error($conn));
		$transid = mysqli_insert_id($conn);
		
	 mysqli_query($conn,"INSERT INTO notification VALUES(NULL,'$me','notification','New Benefitiary Added','$account_nm was added to your benefitiary successfully.','unread',now())");

$message = "$account_nm was added to your benefitiary successfully";

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
  'to' => $phonenum,
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

echo '{"status":"success","message":"Benefitiary added successfully"}';


?>