<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_POST['me']);
$sign = mysqli_real_escape_string($conn,$_POST['sign']);
$check = mysqli_query($conn,"SELECT * FROM mysigns WHERE owner_id='$me'");
if(mysqli_num_rows($check)>0){
	$save = mysqli_query($conn, "UPDATE mysigns SET signature='$sign' WHERE owner_id='$me'");
if($save){
	echo 'saved';
}else{
	die(mysqli_error($conn));
}
}else{
$save = mysqli_query($conn, "INSERT INTO mysigns VALUES(NULL,'$me','$sign',now())");
if($save){
	echo 'saved';
}else{
	die(mysqli_error($conn));
}
}
?>
