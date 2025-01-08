<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
include("conn.php");

$check = mysqli_query($conn, "SELECT * FROM myteams");
if(mysqli_num_rows($check)>0){
while($agenty = mysqli_fetch_array($check)){
$chk = mysqli_query($conn, "SELECT * FROM users WHERE leftover_batch1='$agenty[id]'");
$totalit = mysqli_num_rows($chk);

$postit = mysqli_query($conn, "UPDATE myteams SET whtsapp_link='$totalit' WHERE id='$agenty[id]'");
echo $agenty['branchname'].' '.$agenty['whtsapp_link'].'<br>';
}
}
?>