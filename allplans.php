<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
header('content-type: application/json; charset=utf-8');
include_once("conn.php");

$plan = mysqli_real_escape_string($conn,$_GET['plan']);

$go =mysqli_query($conn, "SELECT id,plan_name,amount,plancode,status,type,duration,amt_to_pay,location,plot_size,payplancode FROM payment_plan WHERE plancode='$plan'");
if(mysqli_num_rows($go)>0){
	$plans = mysqli_fetch_array($go);
	echo '{"status":"correct","amount":"'.$plans['amount'].'","plancode":"'.$plans['plancode'].'"}';
}else{
	echo '{"status":"invalid","amount":"0","plancode":""}';
}
?>