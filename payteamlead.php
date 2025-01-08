<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
include("conn.php");
$token = mt_rand(9999,99999);
$monthy = date('F');

$check = mysqli_query($conn, "SELECT * FROM myteams WHERE whtsapp_link>'9'");
if(mysqli_num_rows($check)>0){
$count =0;
while($agenty = mysqli_fetch_array($check)){
$count = $count+1;

$checkuser = mysqli_query($conn, "SELECT * FROM users WHERE userid='$agenty[leader_id]'");
$duser = mysqli_fetch_array($checkuser);

//create bonus history
$postit = mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$duser[id]','500','Team Lead Bonus','approved',now(),'bonus','add','$monthy','Team Lead','')");

//create bonus history
$postit = mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$duser[id]','1000','Daily Logistics Bonus','approved',now(),'bonus','add','$monthy','Team Lead','')");

//topup agent Wallet
$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$duser[id]'");
if(mysqli_num_rows($checkwalet)>0){
$postit = mysqli_query($conn, "UPDATE mybonus SET amount=amount+'1500' WHERE owner='$duser[id]'");
}else{
$postit = mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$duser[id]','1500','bonus','approved',now(),'auto','team lead')");
}

//notify the agent
mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$duser[id]','payment','Wallet Credited','Your wallet has been credited with N500 Team Lead bonus','unread',now())");

//notify the agent
mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$duser[id]','payment','Wallet Credited','Your wallet has been credited with N1,000 Daily Logistics bonus','unread',now())");

echo $count.'-'.$duser['fname'].' '.$duser['lname'].'<br>';
}

}else{
echo 'No team has more than 9 members';
}
?>