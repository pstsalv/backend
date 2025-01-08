<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
$gender = mysqli_real_escape_string($conn,$_GET['gender']);
if($gender=="Male"){
$dpixx ="male.png";
}else{
	$dpixx = "female.png";
}

$check = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$me'");
if(mysqli_num_rows($check)>0){
	
mysqli_query($conn, "UPDATE users SET pix='$dpixx' WHERE id='$me'") or die(mysqli_error($conn));
echo 'success';
}else{
	echo 'error';
}
?>