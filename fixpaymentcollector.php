<?php
include('conn.php');
//check customer account
$checkprop = mysqli_query($conn, "SELECT * FROM users WHERE account_type='customer' AND agent!='' AND leftover_batch3!='agented' LIMIT 1000") or die(mysqli_error($conn));
if(mysqli_num_rows($checkprop)>0){
	while($user=mysqli_fetch_array($checkprop)){
		
//update customer myproperty
mysqli_query($conn,"UPDATE myproperty SET collectorid='$user[account_no]',logistics='$user[agent]' WHERE userid='$user[id]'");

mysqli_query($conn,"UPDATE users SET leftover_batch3='agented' WHERE id='$user[id]'");

echo 'done for '.$user['fname'].' - '.$user['id'].'<br>';
	}
}else{
	echo 'no customer found <br>';
}
?>