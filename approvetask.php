<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$id = mysqli_real_escape_string($conn,$_GET['id']);
$status = mysqli_real_escape_string($conn,$_GET['status']);
$userid = mysqli_real_escape_string($conn,$_GET['userid']);

$sent = mysqli_query($conn, "UPDATE book_inspection SET status='$status' WHERE id='$id'") or die(mysqli_error($conn));

if($sent){	
$message = "Your inspection request has been $status. Check the Bliss Pay App for more info. Thanks";
$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$userid','notification','Inspection Booking','$message','unread',now())");
echo 'booked';
}else{
echo 'error';
}
?>