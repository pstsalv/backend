<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);

$go =mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$me'");
if(mysqli_num_rows($go)>0){
	$in = mysqli_fetch_array($go);
	
	mysqli_query($conn, "UPDATE users SET status='active', otpcode='skipped' WHERE id='$me'");
	echo 'done';
}else{
	echo 'Unknown error';
}
?>