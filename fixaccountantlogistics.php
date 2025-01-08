<?php
//include('conn.php');
$monthy = 'January';
if(isset($_GET['days'])){
$dday = mysqli_real_escape_string($conn, $_GET['days']);

//check property details

$checkacc = mysqli_query($conn, "SELECT * FROM myproperty WHERE bonusamt!='' AND logistics!='given' AND DATE(date) = DATE_SUB(CURDATE(), INTERVAL $dday DAY)");
while($userz=mysqli_fetch_array($checkacc)){

$giveamt = str_replace(',', '', $userz['bonusamt']);
$forcoll = $giveamt*3;

if($userz['amt_paid'] >= $forcoll){
	$givedisamt = $giveamt*2;

//check users details and collect branch or zone info
$checkbr = mysqli_query($conn, "SELECT id,fname,account_no,agent,branchid FROM users WHERE id='$userz[userid]'");
$customer = mysqli_fetch_array($checkbr);

//check accountant info
$checkaccinfo = mysqli_query($conn, "SELECT id, fname FROM users WHERE branchid='$customer[branchid]' AND account_type='Admin Staff' AND (position='Accountant' OR position='General Accountant')");
if(mysqli_num_rows($checkaccinfo)>0){
$accountantinfo = mysqli_fetch_array($checkaccinfo);
$accounty = $accountantinfo['id'];
}else{
	$accounty = 10001;
}
//create bonus history for branch manager
$postit = mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$accounty','$givedisamt','Logistics bonus from $customer[fname] 3rd & 5th payment','approved',now(),'bonus','add','$monthy','Accountant','$customer[branchid]')");

//topup accountant Wallet
$checkwalet = mysqli_query($conn, "SELECT id FROM mybonus WHERE owner='$accounty'");
if(mysqli_num_rows($checkwalet)>0){
$postit = mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$givedisamt' WHERE owner='$accounty'");
}else{

$postit = mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$accounty','$givedisamt','bonus','approved',now(),'auto','0001')");
}

//notify the accountant
mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$accounty','payment','Wallet Credited','Your wallet has been credited with N$givedisamt Logistics bonus','unread',now())");

mysqli_query($conn, "UPDATE myproperty SET logistics='given' WHERE id='$userz[id]'");
echo 'done <br>';
}

}					

}
?>