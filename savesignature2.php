<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_POST['me']);
$fullnames = mysqli_real_escape_string($conn,$_POST['fullnames']);
$phone = mysqli_real_escape_string($conn,$_POST['phone']);
$address1 = mysqli_real_escape_string($conn,$_POST['address']);

$address = htmlspecialchars($address1);


$sign = mysqli_real_escape_string($conn,$_POST['sign']);
$check = mysqli_query($conn,"SELECT * FROM guaranntor_info WHERE owner='$me'");
if(mysqli_num_rows($check)>0){
	$save = mysqli_query($conn, "UPDATE guaranntor_info SET signature='$sign',fullnames='$fullnames',phone='$phone',address='$address' WHERE owner='$me'");
if($save){
	echo 'saved';
}else{
	die(mysqli_error($conn));
}
}else{
$save = mysqli_query($conn, "INSERT INTO guaranntor_info VALUES(NULL,'$me','$fullnames','$phone','$address','$sign',now())");
if($save){
	echo 'saved';
}else{
	die(mysqli_error($conn));
}
}
?>
