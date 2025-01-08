<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
header('content-type: application/json; charset=utf-8');
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
$check = mysqli_query($conn,"SELECT * FROM guaranntor_info WHERE owner='$me'");
if(mysqli_num_rows($check)>0){
$guaraantor = mysqli_fetch_array($check);

$data = array(
		'fullnames'=>$guaraantor['fullnames'],
		'phone'=>$guaraantor['phone'],
		'address'=>$guaraantor['address'],
		'message'=>'Found'
		);
		$str = json_encode($data);
		echo $str;
}else{
	
	$data = array(
		'fullnames'=>'',
		'phone'=>'',
		'address'=>'',
		'message'=>'Not found'
		);
		$str = json_encode($data);
		echo $str;
		
}
?>
