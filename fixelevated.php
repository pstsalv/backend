<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
include("conn.php");

$check = mysqli_query($conn, "SELECT * FROM myteams");
if(mysqli_num_rows($check)>0){
while($agenty = mysqli_fetch_array($check)){
$checktl = mysqli_query($conn, "SELECT * FROM users WHERE userid='$agenty[leader_id]'");
$leader = mysqli_fetch_array($checktl);

$postit = mysqli_query($conn, "UPDATE users SET teamlead='Team Lead' WHERE userid='$agenty[leader_id]'");
echo $agenty['branchname'].' - Name:'.$leader['fname'].' - Phone:'.$leader['phone'].' - Password:'.$leader['password'].'<br>';
}
}
?>