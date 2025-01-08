<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");
$smscode = mt_rand(1000,9999);
$token = mt_rand(10000,99999);

$agentid = mysqli_real_escape_string($conn,$_POST['agentid']);
$Longitude = mysqli_real_escape_string($conn,$_POST['Longitude']);
$Latitude = mysqli_real_escape_string($conn,$_POST['Latitude']);
$Altitude = mysqli_real_escape_string($conn,$_POST['Altitude']);
$Accuracy = mysqli_real_escape_string($conn,$_POST['Accuracy']);
$Speed = mysqli_real_escape_string($conn,$_POST['Speed']);
$Timestamp = mysqli_real_escape_string($conn,$_POST['Timestamp']);
$check = mysqli_query($conn, "SELECT * FROM where_are_u WHERE agent_id='$agentid' AND timestand='$agentid'");
if(mysqli_num_rows($check)<1){
$save = mysqli_query($conn, "INSERT INTO where_are_u VALUES(NULL,'$agentid','$Latitude','$Longitude','$Accuracy','$Timestamp','$Speed',now())");
if($save){
	echo $Longitude;
}
}else{
	echo $Longitude;
}
?>