<?php
header("access-control-allow-origin: *");
header('Content-Type: application/json');
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');
$typed = $_GET['type'];
date_default_timezone_set('Africa/Lagos');
$login = 'fasthostinc@gmail.com';
$password = 'Gbenga@123';

$today = date('Ymdhis');

$request_id = $today.'YUs83meikd';


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://sandbox.vtpass.com/api/service-variations?serviceID='.$typed,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic YWRtaW5AYmtjYXNoLm5ldDouODlRZiNGLkx5WTZpUSM'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
