<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
header('content-type: application/json; charset=utf-8');
include_once("conn.php");

$me = mysqli_real_escape_string($conn, $_GET['me']);
mysqli_query($conn,"UPDATE users SET status='deleted',account_type='deleted' WHERE id='$me'");
	echo 'deleted';
?>