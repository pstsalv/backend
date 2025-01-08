<?php
$smscode = mt_rand(1000,9999);
$token = mt_rand(1000000,9999999);
$phone = "07035553729";
$message = "Use this digits $smscode to verify your dashboard login on Admin Dashboard";

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
  'class' => 'LXNBWB05H2',
  'ref_id' => $token),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
