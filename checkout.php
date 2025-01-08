<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
$attid = mysqli_real_escape_string($conn,$_GET['attid']);

$CHECK = mysqli_query($conn, "SELECT * FROM attendance WHERE id='$attid'");

	$save = mysqli_query($conn, "UPDATE attendance VALUE(NULL,'$me','$attendancecode','$state',now(),'$savedsign',now(),'$officename','$attendancetype','$officetype','$checktype')");

if($save){
	echo 'success';
}else{
	die('something went wrong');
}
?>
