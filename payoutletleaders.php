<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
include("conn.php");
$token = mt_rand(9999,99999);
$monthy = date('F');

$check = mysqli_query($conn, "SELECT * FROM users WHERE position='Outlet Leader'");
if(mysqli_num_rows($check)>0){
$count =0;
while($agenty = mysqli_fetch_array($check)){
$count = $count+1;

//create bonus history
$postit = mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$agenty[id]','500','Outlet Leaders Bonus','approved',now(),'bonus','add','$monthy','Outlet Leader','')");

//topup agent Wallet
$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$agenty[id]'");
if(mysqli_num_rows($checkwalet)>0){
$postit = mysqli_query($conn, "UPDATE mybonus SET amount=amount+'500' WHERE owner='$agenty[id]'");
}else{
$postit = mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$agenty[id]','500','bonus','approved',now(),'auto','Outlet Leader')");
}

//notify the agent
mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$agenty[id]','payment','Wallet Credited','Your wallet has been credited with N500 Outlet Leaders bonus','unread',now())");

echo $count.'-'.$agenty['fname'].' '.$agenty['lname'].'<br>';
}

}
?>