<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$address1 = mysqli_real_escape_string($conn,$_POST['address']);
$state = mysqli_real_escape_string($conn,$_POST['state']);
$lga = mysqli_real_escape_string($conn,$_POST['lga']);
$dob = mysqli_real_escape_string($conn,$_POST['dob']);
$gender = mysqli_real_escape_string($conn,$_POST['gender']);
$me = mysqli_real_escape_string($conn,$_POST['me']);

$address = htmlspecialchars($address1);

if($gender=="male"){
	$dpixx = "male.png";
}else{
	$dpixx = "female.png";
}
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


$check = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$me'");
if(mysqli_num_rows($check)>0){
	
mysqli_query($conn, "UPDATE users SET state='$state',region='$lga',address='$address',dob='$dob',gender='$gender',pix='$dimage',otpcode='verified' WHERE id='$me'") or die(mysqli_error($conn));
echo 'success';
}else{
	echo 'error';
}
?>