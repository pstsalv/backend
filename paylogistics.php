<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
include("conn.php");
$token = mt_rand(9999,99999);
$monthy = date('F');

$check = mysqli_query($conn, "SELECT * FROM logbank WHERE days>'0' GROUP BY userid");
if(mysqli_num_rows($check)>0){
$count =0;
while($agenty = mysqli_fetch_array($check)){

$count = $count+1;
//create bonus history
$postit = mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$agenty[userid]','1000','Daily Logistics Bonus','approved',now(),'bonus','add','$monthy','logistics','')");

//topup agent Wallet
$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$agenty[userid]'");
if(mysqli_num_rows($checkwalet)>0){
$postit = mysqli_query($conn, "UPDATE mybonus SET amount=amount+'1000' WHERE owner='$agenty[userid]'");
}else{
$postit = mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$agenty[userid]','1000','bonus','approved',now(),'auto','logistics')");
}

//notify the agent
mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$agenty[userid]','payment','Wallet Credited','Your wallet has been credited with N1,000 logistics bonus','unread',now())");


mysqli_query($conn, "UPDATE logbank SET days=days-1 WHERE userid='$agenty[userid]'");

echo $count.'-'.$agenty['userid'].' '.$agenty['days'].'<br>';
}
}
?>