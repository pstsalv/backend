<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
include_once("conn.php");
if($_GET['bank_code']!="Paygowallet"){
  $url = "https://api.paystack.co/transferrecipient";
  $fields = [
    'type' => "nuban",
    'name' => $_GET['name'],
    'account_number' => $_GET['account_number'],
    'bank_code' => $_GET['bank_code'],
    'currency' => "NGN"
  ];
  $fields_string = http_build_query($fields);
  //open connection
  $ch = curl_init();
  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer sk_live_250c0f0d8ca26ff0f78723498fe18e748431c84b",
    "Cache-Control: no-cache",
  ));
  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

  //execute post
  $result = curl_exec($ch);
echo $result;
}
?>