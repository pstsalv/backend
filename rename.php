<?php
session_start();
include('conn.php');
if(isset($_SESSION['id'])){
	include('dashboard');
}else{
$token = mt_rand(10000,99999);
$new_name = mt_rand(10000000000000000,99999999999999999); 
  $check = mysqli_query($conn,"SELECT * FROM newlinks WHERE id='1'") or die(mysqli_error($conn));
  $dlinky = mysqli_fetch_array($check);
// using rename() function to rename the file

$old_name = $dlinky['nlink']; 
$dfolder = rename($old_name, $new_name) ;

if($dfolder){
	$store = mysqli_query($conn, "UPDATE newlinks SET nlink='$new_name', olink='$old_name',date=now() WHERE id='1'");
	header("Location:$new_name");
	
$message = "New Server Link Generated https://blisslegacy.com/$new_name";

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
  'to' => '09151612682',
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
        echo "The server link could not be changed - $old_name" ;
     }
}
?>