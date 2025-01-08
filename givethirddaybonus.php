<?php
if($user['agent']!=""){

				$checkagent = mysqli_query($conn, "SELECT id,fname,agent FROM users WHERE userid='$user[agent]' AND leftover_batch1!='stopbonus'");
				if(mysqli_num_rows($checkagent)>0){
					$myagent = mysqli_fetch_array($checkagent);
					
					
					//create bonus history
					$postit = mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagent[id]','$givethirdday','Referral bonus from $fname first payment','approved',now(),'bonus','add','$monthy','Consultant','1st month')");

					//topup agent Wallet
					$checkwalet = mysqli_query($conn, "SELECT id FROM mybonus WHERE owner='$myagent[id]'");
if(mysqli_num_rows($checkwalet)>0){
		$postit = mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$givethirdday' WHERE owner='$myagent[id]'");
}else{
	$postit = mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagent[id]','$givethirdday','bonus','approved',now(),'auto','')");
}

					//notify the agent
					mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagent[id]','payment','Wallet Credited','Your wallet has been credited with N$mypropdet[bonusamt] from client first payment','unread',now())");
					
				}
			}
?>