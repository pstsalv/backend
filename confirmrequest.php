<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$payref = mysqli_real_escape_string($conn,$_GET['payref']);
$me = mysqli_real_escape_string($conn,$_GET['me']);

$sent = mysqli_query($conn, "SELECT id,status FROM payment_request WHERE id='$payref'");
if(mysqli_num_rows($sent)>0){
	$req = mysqli_fetch_array($sent);
	if($req['status']=="accepted"){
		echo 'accepted';
	}elseif($req['status']=="rejected"){
		echo 'rejected';
	}else{
		echo 'pending';
	}

}else{
	echo 'rejected';
}
?>