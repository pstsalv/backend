<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$howgot = mysqli_real_escape_string($conn,$_POST['howgot']);
$me = mysqli_real_escape_string($conn,$_POST['me']);
if(isset($_POST['outletid'])){
$outletid = mysqli_real_escape_string($conn,$_POST['outletid']);
}else{
$outletid = "";
}
if(isset($_POST['branchid'])){
$branchid = mysqli_real_escape_string($conn,$_POST['branchid']);
}else{
$branchid = "";
}
if(isset($_POST['centerid'])){
$centerid = mysqli_real_escape_string($conn,$_POST['centerid']);
}else{
$centerid = "";
}

$checkbranch = mysqli_query($conn, "SELECT * FROM users WHERE id='$me'");
if(mysqli_num_rows($checkbranch)>0){
if($howgot=="branch"){
	$save = mysqli_query($conn, "UPDATE users SET branchid='$branchid',planid='',my_center='' WHERE id='$me'");
}elseif($howgot=="outlet"){
	$save = mysqli_query($conn, "UPDATE users SET planid='$outletid', branchid='$branchid',my_center='' WHERE id='$me'");
}elseif($howgot=="center"){
	$save = mysqli_query($conn, "UPDATE users SET  planid='$outletid', branchid='$branchid',my_center='$centerid' WHERE id='$me'");
}
}else{
	$save = mysqli_query($conn, "UPDATE users SET branchid='$branchid',planid='',my_center='' WHERE id='$me'");
}
echo 'ok';
?>
