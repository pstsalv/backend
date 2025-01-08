<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
header('content-type: application/json; charset=utf-8');
include_once("conn.php");

if(isset($_GET['join'])){
$join = mysqli_real_escape_string($conn, $_GET['join']);
$fullnames = mysqli_real_escape_string($conn, $_GET['fullnames']);
$pix = mysqli_real_escape_string($conn, $_GET['pix']);
$me = mysqli_real_escape_string($conn, $_GET['me']);

$check = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE userid='$join' AND status!='banned'") or die(mysqli_error($conn));
if(mysqli_num_rows($check)>0){
$in = mysqli_fetch_array($check);

mysqli_query($conn, "INSERT INTO scannedcode VALUES(NULL,'$join','$me','$in[0]',now())") or die(mysqli_error($conn));

echo '{"status":"found","pix":"'.$in['pix'].'","phoneno":"'.$in['phone'].'","email":"'.$in['email'].'","receiverid":"'.$in['id'].'","amount":"","fullnames":"'.ucwords($in['fname']).' '.ucwords($in['lname']).'","agentcode":"'.$join.'"}';
}else{
	echo '{"status":"unknown","message":"Not a valid Bliss Legacy Limited Agent ID"}';
}
}else{
	echo '{"status":"unknown","message":"Unknown Referral ID"}';
}
?>
