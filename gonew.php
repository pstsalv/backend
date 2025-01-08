<?php
header("Access-Control-Allow-Origin: *");
header('content-type: application/json; charset=utf-8');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");
$ipaddress = getenv('HTTP_CLIENT_IP')?:
getenv('HTTP_X_FORWARDED_FOR')?:
getenv('HTTP_X_FORWARDED')?:
getenv('HTTP_FORWARDED_FOR')?:
getenv('HTTP_FORWARDED')?:
getenv('REMOTE_ADDR');

$smscode = mt_rand(1000,9999);
$extra = date('dm');
$token = $extra.'0'.mt_rand(10000,99999);
if(isset($_POST['fullnames'])){
$fullname = mysqli_real_escape_string($conn,$_POST['fullnames']);
if(stripos($fullname, ' ')==false){
	$fname = ucwords($fullname);
	$lname = '';
}else{
list($fname, $lname) = explode(' ', $fullname,2);

$fname = ucwords($fname);
$lname = ucwords($lname);
}
}else{
	$fname = mysqli_real_escape_string($conn,$_POST['fname']);
	$lname = mysqli_real_escape_string($conn,$_POST['lname']);
}

$nickname = mysqli_real_escape_string($conn,$_POST['nickname']);

$phone1 = mysqli_real_escape_string($conn,$_POST['phone']);
$phone = preg_replace('/\s+/', '', $phone1);

$ophone = mysqli_real_escape_string($conn,$_POST['ophone']);
$wphone = mysqli_real_escape_string($conn,$_POST['wphone']);

$email = mysqli_real_escape_string($conn,$_POST['email']);

$password = mysqli_real_escape_string($conn,$_POST['password']);
$address1 = mysqli_real_escape_string($conn,$_POST['address']);
$address = htmlspecialchars($address1);
$oaddress = mysqli_real_escape_string($conn,$_POST['oaddress']);
$caddress = mysqli_real_escape_string($conn,$_POST['caddress']);
$landmark = mysqli_real_escape_string($conn,$_POST['landmark']);
$state = mysqli_real_escape_string($conn,$_POST['state']);
$lga = mysqli_real_escape_string($conn,$_POST['lga']);
$dob = mysqli_real_escape_string($conn,$_POST['dob']);
$gender = mysqli_real_escape_string($conn,$_POST['gender']);

$customerType = mysqli_real_escape_string($conn,$_POST['acctype']);

$agent = mysqli_real_escape_string($conn,$_POST['refcode']);
if(isset($_POST['zone'])){
$zone = mysqli_real_escape_string($conn, $_POST['zone']);
}else{
$zone = '';
}

if(isset($_POST['branch'])){
$branch = mysqli_real_escape_string($conn, $_POST['branch']);
}else{
$branch = '';
}

if($zone=="Branch"){
$branchidy =$branch;
$planid = '';
$my_center = '';

}elseif($zone=="Outlet"){
$check = mysqli_query($conn, "SELECT * FROM allzones WHERE id='$branch'");
if(mysqli_num_rows($check)>0){
$allout = mysqli_fetch_array($check);
$branchidy = $allout['branchid'];
$planid = $branch;
$my_center = '';
}else{
$branchidy =$branch;
$planid = '';
$my_center = '';
}
}elseif($zone=="Center"){
$check = mysqli_query($conn, "SELECT * FROM centers WHERE id='$branch'");
if(mysqli_num_rows($check)>0){
$allout = mysqli_fetch_array($check);

$branchidy = $allout['branchid'];
$planid = $allout['outlet_id'];
$my_center = $branch;
}else{
$branchidy =$branch;
$planid = '';
$my_center = '';
}
}


if($ipaddress=="102.89.33.217"){
echo '{"appstatus":"exist", "message":"You have been banned from the system kindly contact tech support"}';
}else{
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
 echo '{"appstatus":"bademail", "fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "message":"Invalid email supplied"}';
	die();
}else{
	
$check = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,account_type,date FROM users WHERE email='$email' AND account_type='$customerType'");
if(mysqli_num_rows($check)>0){
	$wrong = mysqli_fetch_array($check);
	$acctypy = $wrong['account_type'];
	if($acctypy=="customer"){
		$apptype = "customer";
	}else{
		$apptype = "agent";
	}
	$regdate = date('d',strtotime($wrong['date']));
		echo '{"appstatus":"exist", "message":"An account with this email '.$email.' and phone '.$phone.' already registered as '.ucwords($acctypy).' using the '.$apptype.' app. Change your email and phone number to continue registration or proceed to login"}';

}else{
	
		if($gender=="male"){
	$dpixx = "male.png";
}else{
	$dpixx = "female.png";
}
function createThumbnail($sourcePath, $targetPath, $file_type, $thumbWidth, $thumbHeight){
	$max_size = 300;
$size     = getimagesize($sourcePath);
$ratio    = $size[0] / $size[1];
if ($ratio >= 1) {
    $widthy  = $max_size;
    $heighty = round($max_size / $ratio);
} else {
    $widthy  = round($max_size * $ratio);
    $heighty = $max_size;
}

    if ($file_type == "png") {
        $source = imagecreatefrompng($sourcePath);
    } else {
        $source = imagecreatefromjpeg($sourcePath);
    }
    $width = imagesx($source);
    $height = imagesy($source);

    $tnumbImage = imagecreatetruecolor($widthy, $heighty);

    imagecopyresampled($tnumbImage, $source, 0, 0, 0, 0, $widthy, $heighty, $width, $height);

    if (imagejpeg($tnumbImage, $targetPath, 90)) {
        imagedestroy($tnumbImage);
        imagedestroy($source);
        return TRUE;
    } else {
        return FALSE;
    }
}

if($_FILES['file']!=""){
   if(is_array($_FILES)) {
foreach ($_FILES['file']['name'] as $name => $value){
if(is_uploaded_file($_FILES['file']['tmp_name'][$name])) {
$sourcePath = $_FILES['file']['tmp_name'][$name];
$targetPath = "pix/".$_FILES['file']['name'][$name];
$targetPath2 = "pix/thumb/".$_FILES['file']['name'][$name];
$imagename = $_FILES['file']['name'][$name];
$imagen_ext = pathinfo($imagename, PATHINFO_EXTENSION);
if($imagen_ext=="png" || $imagen_ext=="jpg" || $imagen_ext=="jpeg" || $imagen_ext=="JPG"){
$thumb2 = createThumbnail($sourcePath, $targetPath2, $imagen_ext, 300, 185);
}else{
	die('error');
	exit();
}

if(move_uploaded_file($sourcePath,$targetPath)) {
	$dimage = $imagename;
}else{
	$dimage = $dpixx;
}

}else{
	$dimage =$dpixx;
}}
}else{
	$dimage = $dpixx;
}

}else{
	$dimage = $dpixx;
}

if($_POST['refcode'] !=""){
	$refcode = mysqli_real_escape_string($conn,$_POST['refcode']);
	$checkagent = mysqli_query($conn, "SELECT id,fname,userid,planid FROM users WHERE userid='$refcode'");
	if(mysqli_num_rows($checkagent)>0){
		$agents = mysqli_fetch_array($checkagent);
		$agentid = $agents['userid'];
		$branchoutlet = $agents['planid'];

//increase logistics days
	 mysqli_query($conn,"INSERT INTO notification VALUES(NULL,'$agents[id]','notification','New $customerType registered','Hi $agents[fname], $fname just joined using your referral code LEHM-$refcode. Your bonus is withdrawable once $fname makes first payment.','unread',now())");
	}else{
		$agentid = "";
		$branchoutlet = "";
	}
}else{
		$agentid = "";
		$branchoutlet = "";
	}
$sent = mysqli_query($conn, "INSERT INTO users VALUES(NULL,'$fname','$lname','$email','$phone','$token','$dimage','$state','$lga','$address','$dob','$refcode','$planid','unverified','$password','$customerType',now(),'$smscode','0','0','','$gender','$agentid','unpaid','$oaddress','$caddress','$nickname','$ophone','$wphone','$landmark','','$branchidy','','0','0','','','','$my_center','','','','','','')") or die(mysqli_error($conn));
$newid =  mysqli_insert_id($conn);


if($_POST['mytoken']!=""){
$mytoken = $_POST['mytoken'];

$check = mysqli_query($conn, "SELECT id,owner,token,date,type,owner_token,name FROM tokens WHERE owner='$newid'");
if($check && mysqli_num_rows($check)<1){
mysqli_query($conn, "INSERT INTO tokens VALUES(NULL,'$newid','$mytoken',now(),'Fresh login token','$newid','$fname')") or die(mysqli_error($conn));
}else{
	mysqli_query($conn, "UPDATE tokens SET token='$mytoken',type='Refreshd login token',date=now(),name='$fname' WHERE owner='$newid'");
}
}
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
  CURLOPT_POSTFIELDS => array('token' => 'G1tisaOnfDfmsz0XbHEg1p05PUDn2JJCYMHeVJpt03PpL1GU7M','phone' => $phone,'otp' => $smscode,'class' => 'AEYBPV3VKA','ref_id' => $token),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;

echo '{"appstatus":"done", "user_id":"'.$newid.'", "user_token":"'.$token.'", "phone":"'.$phone.'", "email":"'.$email.'","url":"/otp/'.$phone.'/'.$newid.'/","pincode":"", "walletbal":"0.00", "uncleared":"0.00", "pix":"male.png", "address":"Unset","bonus":"0.00"}';
	}
}
}
?>