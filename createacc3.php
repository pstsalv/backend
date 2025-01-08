<?php
header("access-control-allow-origin: *");
header('content-type: application/json; charset=utf-8');
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');
include_once("conn.php");
$me = mysqli_real_escape_string($conn,$_POST['me']);
$fname = mysqli_real_escape_string($conn,$_POST['fname']);
$lname = mysqli_real_escape_string($conn,$_POST['lname']);
$email = mysqli_real_escape_string($conn,$_POST['email']);
$phone = mysqli_real_escape_string($conn,$_POST['phone']);
if(isset($_POST['prefbank'])){
$dbankk = mysqli_real_escape_string($conn,$_POST['prefbank']);
}else{
	$dbankk = 'wema-bank';
}

  $url = "https://api.paystack.co/customer";

  $fields = [
    "email" => $email,
    "first_name" => $fname,
    "last_name" => $lname,
    "phone" => $phone
  ];

  $fields_string = http_build_query($fields);

  $ch = curl_init();
  
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer ".$payapi,
    "Cache-Control: no-cache",
  ));
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
  $result = curl_exec($ch);
  $response = json_decode($result, true);
    if ($response['status'] === true) {
		
		$customercode = $response['data']['id'];
		$customer_code = $response['data']['customer_code'];
  $url = "https://api.paystack.co/dedicated_account";

  $fields = [
    "customer" => $customercode,
    "phone" => $phone,
    "preferred_bank" => $dbankk
  ];

  $fields_string = http_build_query($fields);

  $ch = curl_init();
  
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer ".$payapi,
    "Cache-Control: no-cache",
  ));
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
  
  $responsed = curl_exec($ch);

if ($responsed === false) {
	
	$responses = '{"status":"error", "message":"'.curl_error($ch).'"}';
	echo $responses;
	
} else {
    $respons = json_decode($responsed, true);
	
    if ($respons['status'] === true) {
		
		$accname = $respons['data']['account_name'];
		$accno = $respons['data']['account_number'];
		$banknm = $respons['data']['bank']['name'];
		
		$checkacc = mysqli_query($conn,"SELECT id,accname,accno,bankname,userid,date,customer_code,customer_id FROM banks WHERE userid='$me' AND accno='$accno'");
		if(mysqli_num_rows($checkacc)<1){
		mysqli_query($conn, "INSERT INTO banks VALUES(NULL,'$accname','$accno','$banknm','$me',now(),'$customer_code','$customercode','$email','')");
		}
		
		$responses = '{"status":"success", "id":"'.$me.'","message":"Account created successfully"}';
	echo $responses;
	
    }else {
		$responses = '{"status":"error", "message":"'.$respons['message'].'"}';
	echo $responses;
	
    }
}


		
	}
?>