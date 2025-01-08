<?php
include('conn.php');
//check agent account
$checkprop = mysqli_query($conn, "SELECT * FROM users WHERE account_type!='customer'");
if(mysqli_num_rows($checkprop)>0){
	while($user=mysqli_fetch_array($checkprop)){
		
//update agent account
//mysqli_query($conn,"UPDATE payment SET collectorid='$user[account_no]',branchid='$user[branchid]',zoneid='$user[planid]',notes='$user[my_center]' WHERE userid='$user[id]'");
mysqli_query($conn, "UPDATE users SET branchid='$user[branchid]', planid='$user[planid]', my_center='$user[my_center]' WHERE agent='$user[userid]'");
echo 'done for '.$user['fname'].'<br>';
	}
}
?>