<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include("conn.php");
$payamt = mysqli_real_escape_string($conn,$_POST['payamt']);
$plancode = mysqli_real_escape_string($conn,$_POST['plancode']);
$property = mysqli_real_escape_string($conn,$_POST['property']);
$notes = mysqli_real_escape_string($conn,$_POST['notes']);
$me = mysqli_real_escape_string($conn,$_POST['me']);
$prevamt = mysqli_real_escape_string($conn,$_POST['prevamt']);
$email = mysqli_real_escape_string($conn,$_POST['email']);
$phone = mysqli_real_escape_string($conn,$_POST['phone']);


  $url = "https://api.paystack.co/transaction/initialize";
  $fields = [
    'email' => $email,
    'amount' => $payamt,
    'plan' => $plancode
  ];

 $fields_string = http_build_query($fields);
  //open connection
  $ch = curl_init();
  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer $payapi",
    "Cache-Control: no-cache",
  ));
 
  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
  
  //execute post
  $result = curl_exec($ch);
  echo $result;
  $results = json_decode($result, true);
  if($results['status']==true){
  $acccode = $results['data']['access_code'];
  $authurl = $results['data']['authorization_url'];
  $reff = $results['data']['reference'];
  
  $save = mysqli_query($conn,"INSERT INTO recurring VALUES(NULL,'$me','$payamt','$plancode','$property','$notes','$prevamt','$email','$acccode','$authurl','$reff',now())");
  }
?>