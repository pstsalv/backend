<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
$propid = mysqli_real_escape_string($conn,$_GET['propid']);
$delit = mysqli_query($conn, "DELETE FROM myproperty WHERE id='$propid' AND amt_paid='0' OR payment_email=''");
if($delit){
	echo 'done';
}else{
	echo 'error';
}
?>