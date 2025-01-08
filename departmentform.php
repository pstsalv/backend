<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");
$smscode = mt_rand(1000,9999);
$token = mt_rand(10000,99999);

$department = mysqli_real_escape_string($conn,$_POST['department']);

if($department=="Admin Staff"){
	$position2 = mysqli_real_escape_string($conn,$_POST['position2']);
}else{
	$position1 = mysqli_real_escape_string($conn,$_POST['position1']);
}
$me = mysqli_real_escape_string($conn,$_POST['me']);

$check = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$me'");
if(mysqli_num_rows($check)<1){
	echo 'Account Not registered, Register it first';

}else{
	mysqli_query($conn, "UPDATE users SET account_type='$department', position='$position2$position1' WHERE id='$me'");
	echo 'success';
}
?>