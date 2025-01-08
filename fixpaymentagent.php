<?php
include('conn.php');
//check customer account

//$from = mysqli_real_escape_string($conn,$_GET['from']);

$checkprop = mysqli_query($conn, "SELECT * FROM users WHERE account_type='customer' AND agent!='' AND branchid!='' AND leftover_batch1=''");
if(mysqli_num_rows($checkprop)>0){
	while($user=mysqli_fetch_array($checkprop)){
		
//update customer payment
mysqli_query($conn,"UPDATE payment SET collectorid='$user[account_no]',agentid='$user[agent]',branchid='$user[branchid]',notes='$user[my_center]' WHERE userid='$user[id]' AND status='approved' AND branchid=''");
mysqli_query($conn, "UPDATE users SET leftover_batch1='gonext' WHERE id='$user[id]'");
echo 'done for '.$user['fname'].' - '.$user['id'].'<br>';
	}
}else{
	echo 'no customer found <br>';
}
?>