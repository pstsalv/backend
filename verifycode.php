<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
header('content-type: application/json; charset=utf-8');
include_once("conn.php");
$a = mysqli_real_escape_string($conn,$_POST['a']);
$b = mysqli_real_escape_string($conn,$_POST['b']);
$c = mysqli_real_escape_string($conn,$_POST['c']);
$d = mysqli_real_escape_string($conn,$_POST['d']);
$code = $a.''.$b.''.$c.''.$d;
$me = mysqli_real_escape_string($conn,$_POST['me']);

$go =mysqli_query($conn, "SELECT * FROM users WHERE id='$me' AND otpcode='$code'");
if(mysqli_num_rows($go)>0){
	$in = mysqli_fetch_array($go);
	
	mysqli_query($conn, "UPDATE users SET status='active', otpcode='verified' WHERE id='$me' AND otpcode='$code'");
	echo '{"status":"verified","me":"'.$in[0].'","dcode":"'.$code.'"}';
}else{
	echo '{"status":"invalid","me":"'.$me.'","dcode":"'.$code.'"}';
}
?>