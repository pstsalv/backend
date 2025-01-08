<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
header('content-type: application/json; charset=utf-8');
include_once("conn.php");

$zone = mysqli_real_escape_string($conn, $_GET['zone']);
$loco = mysqli_real_escape_string($conn, $_GET['loco']);
$heckpr = mysqli_query($conn, "SELECT id,owner_id,state,zone,zonehead,phone,group_link,date,branchid,marketers,clients,cleared,uncleared FROM allzones WHERE id='$zone' LIMIT 1");
if(mysqli_num_rows($heckpr)>0){
	$zones = mysqli_fetch_array($heckpr);

$responses = '{"zonehead":"'.$zones['zonehead'].'", "zcontact":"'.$zones['phone'].'", "group":"'.$zones['group_link'].'", "zoneid":"'.$zones['id'].'", "message":"Details found"}';
	echo $responses;
}else{
	echo 'error';
}
?>
