<?php
header("access-control-allow-origin: *");
header('Content-Type: text/event-stream');
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');
include('conn.php');

$me = mysqli_real_escape_string($conn, $_GET['me']);
$phone = mysqli_real_escape_string($conn, $_GET['phone']);

$token = mt_rand(10000, 99999);
$smscode2 = mt_rand(10000, 99999);

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
  CURLOPT_POSTFIELDS => array(
    'token' => 'G1tisaOnfDfmsz0XbHEg1p05PUDn2JJCYMHeVJpt03PpL1GU7M',
    'phone' => $phone,
    'otp' => $smscode2,
    'class' => 'AEYBPV3VKA',
    'ref_id' => $token
  ),
));

$response = curl_exec($curl);
curl_close($curl);

if ($response === false) {
    echo 'Error: ' . curl_error($curl);
} else {
    $respons = json_decode($response, true);
    if ($respons) {
        if (isset($respons['successful'])) {
            mysqli_query($conn, "INSERT INTO smscharges VALUES(NULL, '{$me}', 'payment', 'BlissPay', '', '{$respons['comment']}', '{$respons['units_calculated']}', '25', now(), '{$respons['successful']}', '{$respons['sms_pages']}')");
        }
    }
}

echo $smscode2;
?>
