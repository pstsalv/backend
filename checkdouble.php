<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
include("conn.php");
$token = mt_rand(9999,99999);
$monthy = date('F');

$check = mysqli_query($conn, "SELECT * FROM logbank WHERE days>0");
while($agenty = mysqli_fetch_array($check)){

$checkwalet = mysqli_query($conn, "SELECT * FROM users WHERE id='$agenty[userid]'");
$userdet = mysqli_fetch_array($checkwalet);

$alldb = mysqli_query($conn, "SELECT * FROM users WHERE agent='$userdet[userid]'");
$alldacc = mysqli_num_rows($alldb);

echo $userdet['fname'].' '.$userdet['lname'].' - ['.$userdet['userid'].'] - '.$alldacc.'<br>';
}
?>