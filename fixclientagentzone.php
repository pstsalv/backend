<?php
include('conn.php');
//check customer account and retrieve the agent code
$checkprop = mysqli_query($conn, "SELECT * FROM users WHERE account_type='customer' AND agent!=''");
if(mysqli_num_rows($checkprop)>0){
	while($user=mysqli_fetch_array($checkprop)){
		//check agent details using the code retrieved
		$checkagentdet = mysqli_query($conn, "SELECT * FROM users WHERE userid='$user[agent]' AND account_type!='customer'");
		if(mysqli_num_rows($checkagentdet)>0){
		$agentdet = mysqli_fetch_array($checkagentdet);
//update customer payment info using planid with retireved details
mysqli_query($conn,"UPDATE users SET planid='$agentdet[planid]',branchid='$agentdet[branchid]',my_center='$agentdet[my_center]' WHERE id='$user[id]' AND account_type='customer'");
echo 'done for '.$user['fname'].' with '.$agentdet['planid'].'<br>';
		}
	}
}
?>