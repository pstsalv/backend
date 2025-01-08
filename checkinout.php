<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_POST['me']);
$state = mysqli_real_escape_string($conn,$_POST['state']);
$attendancecode = mysqli_real_escape_string($conn,$_POST['attendancecode']);
$officename = mysqli_real_escape_string($conn,$_POST['officename']);
$officetype = mysqli_real_escape_string($conn,$_POST['officetype']);
$attendancetype = mysqli_real_escape_string($conn,$_POST['attendancetype']);
$checktype = mysqli_real_escape_string($conn,$_POST['checktype']);
$savedsign = $_POST['savedsign'];
if($checktype=="checkin"){
	$save = mysqli_query($conn, "INSERT INTO attendance VALUE(NULL,'$me','$attendancecode','$state',now(),'$savedsign',now(),'$officename','$attendancetype','$officetype','$checktype')");
}else{
	$save = mysqli_query($conn, "INSERT INTO attendance VALUE(NULL,'$me','$attendancecode','$state',now(),'$savedsign',now(),'$officename','$attendancetype','$officetype','$checktype')");
}
if($save){
	echo 'success';
}else{
	die('something went wrong');
}
?>
