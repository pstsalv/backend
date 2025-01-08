<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
include("conn.php");
$token = mt_rand(9999,99999);
$monthy = date('F');

$check = mysqli_query($conn, "SELECT * FROM users WHERE teamlead='Team Lead'");
if(mysqli_num_rows($check)>0){
while($agenty = mysqli_fetch_array($check)){


$postit = mysqli_query($conn, "UPDATE mybonus SET amount=amount-'500' WHERE owner='$agenty[id]'");
mysqli_query($conn, "DELETE FROM bonuses WHERE userid='$agenty[id]' AND amount='500' AND DATE(date) = CURDATE()");
echo $agenty['fname'].'<br>';
}
}
?>