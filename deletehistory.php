<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
include("conn.php");
$token = mt_rand(9999,99999);
$monthy = date('F');

$check = mysqli_query($conn, "DELETE FROM bonuses WHERE amount='1000' AND DATE(date) = CURDATE()");

?>