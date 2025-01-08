<?php
include('conn.php');
//check agent account
$checkprop = mysqli_query($conn, "SELECT * FROM users WHERE account_type!='customer' AND state!='' AND region!=''");
if(mysqli_num_rows($checkprop)>0){
	while($user=mysqli_fetch_array($checkprop)){
		
mysqli_query($conn, "UPDATE users SET state='$user[state]', region='$user[region]' WHERE agent='$user[userid]'");
echo 'done for '.$user['fname'].' - '.$user['state'].'<br>';
	}
}
?>