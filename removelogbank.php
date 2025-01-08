<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
include("conn.php");
$token = mt_rand(9999,99999);
$monthy = date('F');

$check = mysqli_query($conn, "SELECT * FROM logbank WHERE days>6");
while($agenty = mysqli_fetch_array($check)){


mysqli_query($conn, "UPDATE logbank SET days=days-7 WHERE userid='$agenty[userid]'");
//mysqli_query($conn, "UPDATE users SET teamlead'' WHERE userid='$agenty[userid]'");

//notify the agent
mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$agenty[userid]','payment','Subscription Descreased','Your Your daily logistics subscription has been descreased by 7 days because your client has not paid','unread',now())");
echo $agenty['id'].' '.$agenty['userid'].' - '.$agenty['days'].'<br>';
}
?>