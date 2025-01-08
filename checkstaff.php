<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
header('content-type: application/json; charset=utf-8');
include_once("conn.php");

$staffid = mysqli_real_escape_string($conn,$_GET['staffid']);
$heckpr = mysqli_query($conn, "SELECT id,fname,lname,userid,pix,account_type FROM users WHERE userid='$staffid' AND account_type!='customer'");
if(mysqli_num_rows($heckpr)>0){
$staff = mysqli_fetch_array($heckpr);
$data = array(
'status'=>'correct',
'staffname'=>$staff['fname'].' '.$staff['lname'],
'staffid'=>$staff['userid'],
'staffpix'=>$staff['pix'],
'message'=>$staff['fname'].' '.$staff['lname'].' is a valid Bliss Legacy '.ucwords($staff['account_type'])
);
$str = json_encode($data);
echo $str;
}else{
	$data = array(
'status'=>'incorrect',
'staffname'=>'Invalid Staff ID',
'message'=>'Not a Valid Bliss Legacy Staff or Consultant'
);
$str = json_encode($data);
echo $str;
}
