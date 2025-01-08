<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$method = mysqli_real_escape_string($conn,$_POST['method']);
$property = mysqli_real_escape_string($conn,$_POST['property']);
$custnames = mysqli_real_escape_string($conn,$_POST['custnames']);
$custphone = mysqli_real_escape_string($conn,$_POST['custphone']);
$days = mysqli_real_escape_string($conn,$_POST['days']);
$me = mysqli_real_escape_string($conn,$_POST['me']);
$notes = mysqli_real_escape_string($conn,$_POST['notes']);

$sent = mysqli_query($conn, "INSERT INTO  book_inspection VALUES(NULL,'$method','$property','$custnames','$custphone','$days','$me','$notes','pending',now(),'unassign')") or die(mysqli_error($conn));
$newid =  mysqli_insert_id($conn);

if($sent){	
$message = "Your Inspection request has been submited. When the status changes, we will notify you. Thanks";
$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$me','notification','Inspection Booking','$message','unread',now())");
echo 'booked';
}else{
echo 'error';
}
?>