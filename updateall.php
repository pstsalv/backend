<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
header('content-type: application/json; charset=utf-8');
include_once("conn.php");

$agentid = mysqli_real_escape_string($conn, $_GET['agentid']);
$checkallagent = mysqli_query($conn,"SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE agent='$agentid'");
if(mysqli_num_rows($checkallagent)>0){
	while($users = mysqli_fetch_array($checkallagent)){

  $checkamtpp = mysqli_query($conn,"SELECT SUM(amount) AS amttpaid FROM  payment WHERE status='approved' AND userid='$users[id]'");
  
$rowpp = mysqli_fetch_assoc($checkamtpp);
$sumpp = $rowpp['amttpaid'];
$totalpayd = "$sumpp";
if($totalpayd!==""){
$mybalp = $totalpayd;

mysqli_query($conn,"UPDATE users SET wallet_bal='$mybalp' WHERE userid='$agentid' AND account_type='agent'");

}
}
}


$checkallagent2 = mysqli_query($conn,"SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE agent='$agentid'");
if(mysqli_num_rows($checkallagent2)>0){
	while($users2 = mysqli_fetch_array($checkallagent2)){

  $checkamtpp2 = mysqli_query($conn,"SELECT SUM(amount) AS amttpaid FROM  payment WHERE status!='approved' AND userid='$users2[id]'");
  
$rowpp2 = mysqli_fetch_assoc($checkamtpp2);
$sumpp2 = $rowpp2['amttpaid'];
$totalpayd2 = "$sumpp2";
if($totalpayd2!==""){
$mybalp2 = $totalpayd2;

mysqli_query($conn,"UPDATE users SET uncleared='$mybalp2' WHERE userid='$agentid' AND account_type='agent'");

}
}
}
