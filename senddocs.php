<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");
$token = mt_rand(99999,999999);
$me = mysqli_real_escape_string($conn,$_POST['me']);
if(isset($country)){
	$country = mysqli_real_escape_string($conn,$_POST['country']);
}else{
	$country = "";
}

if(isset($_POST['secquest'])){
	$secquest = mysqli_real_escape_string($conn,$_POST['secquest']);
	$secansw = mysqli_real_escape_string($conn,$_POST['secansw']);
	$alreadyanswered = mysqli_real_escape_string($conn,$_POST['alreadyanswered']);
	
	if($alreadyanswered=="no"){
	mysqli_query($conn, "INSERT INTO sec_question VALUES(NULL,'$secquest','$secansw','$me',now())") or die(mysqli_error($conn));
	}else{
		$checkifc = mysqli_query($conn, "SELECT * FROM sec_question WHERE owner='$me' AND answer='$secansw'");
		if(mysqli_num_rows($checkifc)>0){
			
			
			$do = mysqli_query($conn,"UPDATE mybonus SET status='approved' WHERE owner='$me'");
	if($do){
	$message = 'Your wallet has been unlocked, if you didnt request this reset kindly visit https://blisslegacy.com/lock?agent='.base64_encode($me);


$check = mysqli_query($conn, "SELECT phone FROM users WHERE id='$me'");
if(mysqli_num_rows($check)>0){
	$user = mysqli_fetch_array($check);
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
  'to' => $user['phone'],
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
//echo $response;
}
			
			
	}	
			
			
		}
	}
echo 'success';
	
}else{
	

   if(is_array($_FILES)) {
foreach ($_FILES['file']['name'] as $name => $value){
if(is_uploaded_file($_FILES['file']['tmp_name'][$name])) {
$sourcePath = $_FILES['file']['tmp_name'][$name];
$targetPath = "pix/".$_FILES['file']['name'][$name];
$imagename = $_FILES['file']['name'][$name];
$imagen_ext = pathinfo($imagename, PATHINFO_EXTENSION);

if(move_uploaded_file($sourcePath,$targetPath)) {
	$dimage = $imagename;
}else{
	$dimage = '';
}

}else{
	$dimage ='';
}}
}else{
	$dimage = '';
}
	
mysqli_query($conn, "INSERT INTO mydocs VALUES(NULL,'$me','$dimage',now(),'$country')") or die(mysqli_error($conn));
echo 'success';
}
?>