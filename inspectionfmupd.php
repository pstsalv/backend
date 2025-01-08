<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$method = mysqli_real_escape_string($conn,$_POST['method']);
$custnames = mysqli_real_escape_string($conn,$_POST['custnames']);
$custphone = mysqli_real_escape_string($conn,$_POST['custphone']);
$days = mysqli_real_escape_string($conn,$_POST['days']);
$officer = mysqli_real_escape_string($conn,$_POST['officer']);
$inspstatus = mysqli_real_escape_string($conn,$_POST['inspstatus']);
$me = mysqli_real_escape_string($conn,$_POST['me']);
$notes = mysqli_real_escape_string($conn,$_POST['notes']);

$sent = mysqli_query($conn, "UPDATE book_inspection SET method='$method',days='$days',notes='$notes', insp_officer='$officer', status='$inspstatus' WHERE id='$me'") or die(mysqli_error($conn));

if($sent){	
$message = "Your inspection request has been $inspstatus. Check the Bliss Pay App for more info. Thanks";
$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$me','notification','Inspection Booking','$message','unread',now())");
echo 'booked';
}else{
echo 'error';
}
?>