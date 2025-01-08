<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
include("conn.php");
$token = mt_rand(9999,99999);
$monthy = date('F');

$check = mysqli_query($conn, "SELECT * FROM mybonus WHERE amount>='0'");
if(mysqli_num_rows($check)>0){
while($agenty = mysqli_fetch_array($check)){



//successful withdrawals
$checkrev = mysqli_query($conn,"SELECT SUM(amount) AS revenue FROM  withdrawals WHERE status='successful' AND userid='$agenty[owner]' AND DATE(date) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)  GROUP BY payref");

$rowpp = mysqli_fetch_assoc($checkrev);
$sumpp = $rowpp['revenue'];
$totalpayd = "$sumpp";
if($totalpayd!==""){
$allrev = $totalpayd;
}else{
$allrev = "0.00";
};

//reversals
$checkrevwa = mysqli_query($conn,"SELECT SUM(amount) AS approvw FROM  withdrawals WHERE status='reversed' AND userid='$agenty[owner]' AND DATE(date) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) GROUP BY payref");
$rowppwa = mysqli_fetch_assoc($checkrevwa);
$sumppwa = $rowppwa['approvw'];
$totalpaydwa = "$sumppwa";
if($totalpaydwa!==""){
$allrevwa = $totalpaydwa;
}else{
$allrevwa = "0.00";
};


$balance = $allrev-"$allrevwa";

$postit = mysqli_query($conn, "UPDATE mybonus SET amount=amount-'$balance' WHERE owner='$agenty[owner]'");

echo $balance.'<br>';
}
}
?>