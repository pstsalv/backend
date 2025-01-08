<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");
$attcode = strtotime(date('Y-m-d h:i:sa'));
$officer = mysqli_real_escape_string($conn, $_GET['me']);
$check = mysqli_query($conn, "SELECT * FROM attendance_code WHERE userid='$officer'");
if(mysqli_num_rows($check)>0){
	mysqli_query($conn, "UPDATE attendance_code SET code='$attcode' WHERE userid='$officer'");
}else{
	mysqli_query($conn, "INSERT INTO attendance_code VALUES(NULL,'$attcode','$officer',now())");
}
echo $attcode;