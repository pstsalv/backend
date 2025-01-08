<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");
$check = mysqli_query($conn, "SELECT id,versionno,status,date,available FROM versioned WHERE status='active' AND versionno!=''");
if(mysqli_num_rows($check)>0){
	$ver=mysqli_fetch_array($check);
	echo $ver[1];
}else{
	echo '2';
	}
?>