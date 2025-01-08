<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$type = mysqli_real_escape_string($conn,$_GET['type']);
$payid = mysqli_real_escape_string($conn,$_GET['payid']);
$me = mysqli_real_escape_string($conn,$_GET['me']);

$sent = mysqli_query($conn, "UPDATE payment_request SET status='$type' WHERE id='$payid'");
if($sent){
echo 'done';
}else{
	echo 'rejected';
}
?>