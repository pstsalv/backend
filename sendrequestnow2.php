<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");
$customer = mysqli_real_escape_string($conn,$_GET['id']);

$do =  mysqli_query($conn, "INSERT INTO manage_request VALUES(NULL,'','$customer','pending',now())") or die(mysqli_error($conn));
if($do){
	mysqli_query($conn, "UPDATE users SET agent='' WHERE id='$customer'");
	echo 'done';
}
?>