<?php
include('conn.php');
//check agent account
$checkprop = mysqli_query($conn, "SELECT * FROM users WHERE account_type='customer' AND wallet_bal>0 AND leftover_batch3!='passed'");
if(mysqli_num_rows($checkprop)>0){
	while($user=mysqli_fetch_array($checkprop)){
		
//update agent account
mysqli_query($conn,"UPDATE payment SET collectorid='$user[account_no]',branchid='$user[branchid]',zoneid='$user[planid]',notes='$user[my_center]' WHERE userid='$user[id]'");
mysqli_query($conn, "UPDATE users SET leftover_batch3='passed' WHERE id='$user[id]'");
echo 'done for '.$user['fname'].'<br>';
	}
}
?>