<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');
include_once("conn.php");
$me = mysqli_real_escape_string($conn,$_POST['me']);
$fname = mysqli_real_escape_string($conn,$_POST['fname']);
$lname = mysqli_real_escape_string($conn,$_POST['lname']);

$phone = mysqli_real_escape_string($conn,$_POST['phone']);

$checku = mysqli_query($conn, "SELECT email FROM users WHERE id='$me'");
$user = mysqli_fetch_array($checku);
$email = $user['email'];

  $url = "https://api.paystack.co/customer";

  $fields = [
    "email" => $email,
    "first_name" => $fname,
    "last_name" => $lname,
    "phone" => $phone
  ];

  $fields_string = http_build_query($fields);

  //open connection
  $ch = curl_init();
  
  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer ".$payapi,
    "Cache-Control: no-cache",
  ));
  
  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
  
  //execute post
  $result = curl_exec($ch);
 //echo $result;
  
  $response = json_decode($result, true);
    if ($response['status'] === true) {
		
		$customercode = $response['data']['id'];
		$customer_code = $response['data']['customer_code'];
				//echo $customercode;
				
				
				

  $url = "https://api.paystack.co/dedicated_account";

  $fields = [
    "customer" => $customercode,
    "phone" => $phone,
    "preferred_bank" => "wema-bank"
  ];

  $fields_string = http_build_query($fields);

  //open connection
  $ch = curl_init();
  
  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer ".$payapi,
    "Cache-Control: no-cache",
  ));
  
  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
  
  //execute post
  $responsed = curl_exec($ch);

if ($responsed === false) {
    echo 'Error: ' .curl_error($ch);
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
        echo 'success';
    } else {
        echo 'Error creating virtual account: ' . $respons['message'];
    }
}


		
	}
?>