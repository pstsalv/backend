<?php
include('conn.php');


$dbonusdailsec = 0.15*$giveamt;
$dbonus2dailysec = number_format($dbonusdailsec);


		//check agent of my agent
			if($myagent['agent']!="" || $myagent['agent']!="elevated"){
				
				$checkagent2 = mysqli_query($conn, "SELECT id,fname,state,agent FROM users WHERE userid='$myagent[agent]'");
				if(mysqli_num_rows($checkagent2)>0){
					$myagent2 = mysqli_fetch_array($checkagent2);
						
					//create bonus history
					mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagent2[id]','$dbonusdailsec','Referral bonus for $user[fname] - Lagos','approved',now(),'bonus','add','$monthy','Second Generation','')");
					
					//topup agent Wallet
					$checkwalet = mysqli_query($conn, "SELECT id FROM mybonus WHERE owner='$myagent2[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$dbonusdailsec' WHERE owner='$myagent2[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagent2[id]','$dbonusdailsec','bonus','approved',now(),'auto','')");
}

			//notify the agent
			mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagent2[id]','payment','Wallet Credited','Your wallet has been credited with N$dbonus2dailysec for client outright property','unread',now())");
			
			//notify the system that client has been paid bonus
			mysqli_query($conn, "UPDATE payment SET notes='Bonus Awarded' WHERE status='approved' AND admin_approved='virtual' AND userid='$me' AND paidfor='$property'");
			
					
					
				}
				
			}

?>