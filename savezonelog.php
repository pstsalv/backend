<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_POST['me']);
$teams = mysqli_real_escape_string($conn,$_POST['teams']);

$check = mysqli_query($conn, "SELECT id FROM myteams WHERE id='$teams' AND whtsapp_link<25");
if(mysqli_num_rows($check)>0){
	$save = mysqli_query($conn, "UPDATE users SET leftover_batch1='$teams' WHERE id='$me'");
mysqli_query($conn, "UPDATE myteams SET whtsapp_link=whtsapp_link+1 WHERE id='$teams'");

echo 'ok';
}else{
echo 'This team is full join another one';
}
?>
