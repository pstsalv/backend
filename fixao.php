<?php
include('conn.php');

$checkprop = mysqli_query($conn, "SELECT * FROM payment WHERE amount>0 AND status='approved' AND collectorid!='' AND YEAR(date_paid) = YEAR(CURDATE())");
if(mysqli_num_rows($checkprop)>0){
	while($user=mysqli_fetch_array($checkprop)){



$seconbonusao = $user['amount']*0.08;


//check if ao second gen user details is available
$checkagentsg = mysqli_query($conn, "SELECT * FROM users WHERE userid='$user[collectorid]' AND leftover_batch1!='stopbonus'");
	if(mysqli_num_rows($checkagentsg)>0){
		$myagentsec = mysqli_fetch_array($checkagentsg);

//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagentsec[id]','$seconbonusao','Second Generation referral bonus','approved','$user[date_paid]','bonus','add','$monthy','$myagentsec[account_type]','$myprop[type]')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$myagentsec[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$seconbonusao',date=now() WHERE owner='$myagentsec[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagentsec[id]','$seconbonusao','bonus','approved',now(),'auto','secondgen')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagentsec[id]','payment','Wallet Credited','Your wallet has been credited with second generation bonus','unread','$user[date_paid]')");

echo $myagentsec['fname'].' '.$myagentsec['position'].' '.$seconbonusao;
}









	}

}
?>