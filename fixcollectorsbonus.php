<?php
include('conn.php');


if(isset($_GET['month'])){
	$filtermonth = mysqli_real_escape_string($conn, $_GET['month']);
$dismonth = date('Y-m-d h:i:s',strtotime("$filtermonth"));

	if($filtermonth=="January"){
		$dmonth = 1;
	}elseif($filtermonth=="February"){
		$dmonth = 2;
	}elseif($filtermonth=="March"){
		$dmonth = 3;
	}elseif($filtermonth=="April"){
		$dmonth = 4;
	}elseif($filtermonth=="May"){
		$dmonth = 5;
	}elseif($filtermonth=="June"){
		$dmonth = 6;
	}elseif($filtermonth=="July"){
		$dmonth = 7;
	}elseif($filtermonth=="August"){
		$dmonth = 8;
	}elseif($filtermonth=="September"){
		$dmonth = 9;
	}elseif($filtermonth=="October"){
		$dmonth = 10;
	}elseif($filtermonth=="November"){
		$dmonth = 11;
	}elseif($filtermonth=="December"){
		$dmonth = 12;
	}else{
		$dmonth = 'MONTH(CURDATE())';
	}
}else{
$dmonth = 'MONTH(CURDATE())';
$dismonth = 'now()';
}




//search for collectors
$check = mysqli_query($conn, "SELECT id,fname,email,phone,userid,account_no,wallet_bal,agent FROM users WHERE (account_type='Collector' OR account_type='Collection Officer')");
if(mysqli_num_rows($check)>0){
	//if found
	while($agent = mysqli_fetch_array($check)){
		//import the profile
		
		//search collectors customers
		
		$checkbn = mysqli_query($conn,"SELECT SUM(amount) AS bonusamt FROM payment WHERE status='approved' AND collectorid='$agent[userid]' AND MONTH(date_paid)=$dmonth");
$rowbn = mysqli_fetch_assoc($checkbn);
$sumpp = $rowbn['bonusamt'];
$totalbn= "$sumpp";
if($totalbn!=""){
$mybalbn = $totalbn;
}else{
$mybalbn = "0.00";
};
//calculate bonus
$collbonus = "$mybalbn"*0.06;
//create bonus history
	//salary
		$postit = mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$agent[id]','30000','Collection officers basic salary for $filtermonth','unapproved','$dismonth','salary','add','$filtermonth','Collector','')");
		//bonus
		$postit = mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$agent[id]','$collbonus','Account officers bonus for $filtermonth','unapproved','$dismonth','bonus','add','$filtermonth','Collector','')");

		echo 'Added for '.$agent['fname'].'<br>';
	}
	}

mysqli_close($conn);
?>