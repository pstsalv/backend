<?php
include('conn.php');
$monthy = "November";
$dismonth = date('Y-m-d h:i:s',strtotime("$monthy"));

//check zone leader
$check = mysqli_query($conn, "SELECT id,owner_id,state,zone,zonehead,phone,group_link,date,branchid,marketers,clients,cleared,uncleared FROM allzones WHERE owner_id!=''");
if(mysqli_num_rows($check)>0){
	while($agent = mysqli_fetch_array($check)){
		
		//get full info of leader
		$checkleader = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE userid='$agent[owner_id]'");
		
		if(mysqli_num_rows($checkleader)>0){
			$agentinfo = mysqli_fetch_array($checkleader);
			
			//check revenue of zone
				$checkamtppo = mysqli_query($conn,"SELECT SUM(amount) AS revenue FROM payment WHERE zoneid='$agent[id]'");
$rowppo = mysqli_fetch_assoc($checkamtppo);
$sumppo = $rowppo['revenue'];
$outstand = "$sumppo";
if($outstand!==""){
$zonerevenue = $outstand;
}else{
$zonerevenue = "0";
};
//* If your zone brings 400,000 naira  to 600,000 naira 60,000 will be paid monthly
if($zonerevenue>399999 && $zonerevenue<599999){
	$postit = mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$agentinfo[id]','60000','Zone made $zonerevenue for $monthy','unapproved','$dismonth','salary','add','$monthy','$zonerevenue','')");
	//* If your zone brings 700,000 naira and above 70,000 naira will be paid monthly
}elseif($zonerevenue>699999 && $zonerevenue<2999999){
	$postit = mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$agentinfo[id]','70000','Zone made $zonerevenue for $monthy','unapproved','$dismonth','salary','add','$monthy','$zonerevenue','')");
	//* If your zone brings 3,000,000 naira and above 100,000 naira above will be paid monthly
}elseif($zonerevenue>2999999){
	$postit = mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$agentinfo[id]','100000','Zone made $zonerevenue for $monthy','unapproved','$dismonth','salary','add','$monthy','$zonerevenue','')");
}

			
		}
		
		
		echo 'Zone made '.$zonerevenue.'<br>';
	}
}
mysqli_close($conn);
?>