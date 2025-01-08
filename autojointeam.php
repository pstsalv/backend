<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");


$check = mysqli_query($conn, "SELECT * FROM myteams WHERE whtsapp_link<20");
if(mysqli_num_rows($check)>0){
while($teams = mysqli_fetch_array($check)){
	$save = mysqli_query($conn, "UPDATE users SET leftover_batch1='$teams[id]', logistics='activated', ipaddress='pump3' WHERE account_type='agent' AND pincode!='' AND logistics!='activated'");

mysqli_query($conn, "UPDATE myteams SET whtsapp_link=whtsapp_link+1 WHERE id='$teams[id]'");

echo $teams['branchname'].' '.$teams['whtsapp_link'].'<br>';
}
}else{
echo 'This team is full join another one';
}
?>
