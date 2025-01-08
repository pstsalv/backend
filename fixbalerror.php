<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
include("conn.php");
$token = mt_rand(9999,99999);
$monthy = date('F');

$check = mysqli_query($conn, "SELECT * FROM myteams");
while($agenty = mysqli_fetch_array($check)){

//check if already issued
$checktoday = mysqli_query($conn, "SELECT * FROM bonuses WHERE userid='$agenty[leader_id]' AND amount='500' AND DATE(date) = CURDATE()");
$totaltoday = mysqli_num_rows($checktoday);
if($totaltoday<1){
//create bonus history
$postit = mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$agenty[leader_id]','500','Team Lead Bonus','approved',now(),'bonus','add','$monthy','Team Lead','')");

//topup agent Wallet
$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$agenty[leader_id]'");
if(mysqli_num_rows($checkwalet)>0){
$postit = mysqli_query($conn, "UPDATE mybonus SET amount=amount+'500' WHERE owner='$agenty[leader_id]'");
}else{
$postit = mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$agenty[leader_id]','500','bonus','approved',now(),'auto','team lead')");
}

//notify the agent
mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$agenty[id]','payment','Wallet Credited','Your wallet has been credited with N500 Team Lead bonus','unread',now())");


echo $agenty['leader_id'].' '.$agenty['branchname'].' - '.$agenty['leader_id'].'<br>';
}
}
?>