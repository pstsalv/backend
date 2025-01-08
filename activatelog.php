<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
include("conn.php");
$token = mt_rand(9999,99999);
$monthy = date('F');
$me = mysqli_real_escape_string($conn,$_GET['me']);

$check = mysqli_query($conn, "SELECT * FROM users WHERE id='$me' AND logistics=''") or  die(mysqli_error($conn));
if(mysqli_num_rows($check)>0){

$agenty = mysqli_fetch_array($check);

	$upit = mysqli_query($conn, "UPDATE users SET logistics='activated' WHERE id='$me'");

//create bonus history
					//$postit = mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$agenty[id]','1000','Daily Logistics Bonus','pending',now(),'bonus','add','$monthy','logistics','')");
					
					//topup agent Wallet
					$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$agenty[id]'");
if(mysqli_num_rows($checkwalet)>0){
		//$postit = mysqli_query($conn, "UPDATE mybonus SET amount=amount+'1000' WHERE owner='$agenty[id]'");
}else{
	//$postit = mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$agenty[id]','1000','bonus','approved',now(),'auto','logistics')");
}


//deposit to bank

mysqli_query($conn, "INSERT INTO logbank VALUES(NULL,'$me','0','0',now())");

					//notify the agent
					//mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$agenty[id]','payment','Wallet Credited','Your wallet has been credited with N1,000 logistics bonus','unread',now())");


	echo 'Activated';
	}else{
		 print "Already active";
	}
?>