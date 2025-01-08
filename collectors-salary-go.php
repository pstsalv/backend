<?php
include('conn.php');
$monthy = "November";
$dismonth = date('Y-m-d h:i:s',strtotime("$monthy"));

$check = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE (account_type='Collector' OR account_type='Collection Officer')");
if(mysqli_num_rows($check)>0){
	while($agent = mysqli_fetch_array($check)){
		
		
		//check all payment collected by collector
		
		$checksal = mysqli_query($conn, "SELECT id,userid,amount,reason,status,date,type,add_remove,month,clients,hideshow FROM bonuses WHERE userid='$agent[id]' AND type='salary' AND month='$monthy'");
if(mysqli_num_rows($checksal)>0){
	$postit = mysqli_query($conn, "UPDATE bonuses SET amount='40000' WHERE userid='$agent[id]' AND month='$monthy'");
	
}else{
	
		$postit = mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$agent[id]','40000','Collection officers basic salary for $monthy','unapproved','$dismonth','salary','add','$monthy','Collector','')");
}
		echo 'Added for '.$agent['fname'].'<br>';
	}
}
mysqli_close($conn);

//if they have more than 5 paying clients, pay them 40k basic salary
	
	
	//fix all salaries before tomorrow
?>