<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache'); 

include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
$lat = mysqli_real_escape_string($conn,$_GET['lat']);
$lng = mysqli_real_escape_string($conn,$_GET['lng']);
$cust = mysqli_real_escape_string($conn,$_GET['cust']);

mysqli_query($conn, "INSERT INTO footsteps VALUES(NULL, '$me', '$lat', '$lng', '$cust', now())");
echo 'done';
?>