<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');
include_once("conn.php");

$type = mysqli_real_escape_string($conn, $_POST['type']);
$me = mysqli_real_escape_string($conn, $_POST['me']);
$uid = mysqli_real_escape_string($conn, $_POST['uid']);
$subject = mysqli_real_escape_string($conn, $_POST['subject']);
$fullnames = mysqli_real_escape_string($conn, $_POST['fullnames']);
$issuedate = mysqli_real_escape_string($conn, $_POST['issuedate']);
$ticketno = mt_rand(100000,999999);
$message = nl2br(urlencode($_POST['descr']));

	$go = mysqli_query($conn, "INSERT INTO tickets VALUES(NULL,'$uid','Tech Support','$type','','New','$subject',now(),'','High','$ticketno','$message','$fullnames')");
	if($go){
		echo 'good';
	}else{
		echo 'bad';
	}
?>