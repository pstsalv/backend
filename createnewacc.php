<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");
$smscode = mt_rand(1000,9999);
$extra = date('dm');
$token = $extra.'0'.mt_rand(10000,99999);


$me = mysqli_real_escape_string($conn,$_POST['me']);
$fname = mysqli_real_escape_string($conn,$_POST['fname']);
$lname = mysqli_real_escape_string($conn,$_POST['lname']);
$email = mysqli_real_escape_string($conn,$_POST['email']);
$password = mysqli_real_escape_string($conn,$_POST['password']);

$branchid = mysqli_real_escape_string($conn,$_POST['branchid']);
$outletid = mysqli_real_escape_string($conn,$_POST['outletid']);
$centerid = mysqli_real_escape_string($conn,$_POST['centerid']);

$phone1 = mysqli_real_escape_string($conn,$_POST['phone']);
$phone = preg_replace("/\s+/", "", $phone1);
$customerType2 = mysqli_real_escape_string($conn,$_POST['acctype']);
$customerType = strtolower($customerType2);

$address1 = mysqli_real_escape_string($conn,$_POST['address']);
$state = mysqli_real_escape_string($conn,$_POST['state']);
$lga = mysqli_real_escape_string($conn,$_POST['lga']);
$dob = mysqli_real_escape_string($conn,$_POST['dob']);
$gender = mysqli_real_escape_string($conn,$_POST['gender']);
if(isset($_POST['zone'])){
	$zone = mysqli_real_escape_string($conn,$_POST['zone']);
}else{
	$zone = "";
}

if($customerType2!="Customer"){

echo '{"appstatus":"exist", "message":"You can no longer add another agent from your agent app, kindly register the agent directly from the agent app"}';
die();
}else{

$refcode = mysqli_real_escape_string($conn,$_POST['refcode']);

$address = nl2br($address1);


if($customerType=="agent"){
	$customerTypenew = "unknown";
}else{
	$customerTypenew = $customerType;
}


if($customerType=="agent"){
	$bonus = "2000";
}else{
	$bonus = "1000";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
 echo 'bademail';
	exit();
}

if($gender=="Male"){
	$dpixx = "male.png";
}else{
	$dpixx = "female.png";
}

$check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
if(mysqli_num_rows($check)>0){
	echo 'exist';
	
}else{
	
	function createThumbnail($sourcePath, $targetPath, $file_type, $thumbWidth, $thumbHeight){
	$max_size = 400;
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


   if(is_array($_FILES)) {
foreach ($_FILES['file']['name'] as $name => $value){
if(is_uploaded_file($_FILES['file']['tmp_name'][$name])) {
$sourcePath = $_FILES['file']['tmp_name'][$name];
$targetPath = "pix/".$_FILES['file']['name'][$name];
$targetPath2 = "pix/thumb/".$_FILES['file']['name'][$name];
$imagename = $_FILES['file']['name'][$name];
$imagen_ext = pathinfo($imagename, PATHINFO_EXTENSION);
if($imagen_ext=="png" || $imagen_ext=="jpg" || $imagen_ext=="jpeg"){
$thumb2 = createThumbnail($sourcePath, $targetPath2, $imagen_ext, 300, 185);
}else{
	move_uploaded_file($sourcePath,$targetPath);
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


if(isset($_POST['refcode']) && $_POST['refcode'] !=""){
	$refcode = mysqli_real_escape_string($conn,$_POST['refcode']);
	$checkagent = mysqli_query($conn, "SELECT * FROM users WHERE userid='$refcode'");
	if(mysqli_num_rows($checkagent)>0){
		$agents = mysqli_fetch_array($checkagent);
		

		$sent = mysqli_query($conn, "INSERT INTO users VALUES(NULL,'$fname','$lname','$email','$phone','$token','$dimage','$state','$lga','$address','$dob','$refcode','$agents[planid]','unverified','$password','$customerTypenew',now(),'$smscode','0','0','','$gender','$refcode','','','','','','','','','$agents[branchid]','$agents[created_from]','0','0','','','','$agents[my_center]','','','','','','')") or die(mysqli_error($conn));
$newid =  mysqli_insert_id($conn);

	mysqli_query($conn, "INSERT INTO referral VALUES(NULL,'$refcode','$newid','$bonus','card','unpaid',now(),'unread')") or die(mysqli_error($conn));

	 mysqli_query($conn,"INSERT INTO notification VALUES(NULL,'$agents[0]','notification','New $customerType registered','$fname just joined using your referral code LEHM-$refcode. Your bonus is withdrawable once $fname makes first payment.','unread',now())");
	}else{
		$sent = mysqli_query($conn, "INSERT INTO users VALUES(NULL,'$fname','$lname','$email','$phone','$token','$dimage','$state','$lga','$address','$dob','','$zone','unverified','$password','$customerTypenew',now(),'$smscode','0','0','','$gender','$refcode','','','','','','','','','','','0','0','','','','','','','','','','')") or die(mysqli_error($conn));
$newid =  mysqli_insert_id($conn);
	}
}

echo 'success';

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


	}
}
?>