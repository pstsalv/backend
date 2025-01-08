<?php
include('conn.php');
$checku = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE wallet_bal>0 AND account_type='customer'");
while($user = mysqli_fetch_array($checku)){
$go = mysqli_query($conn, "UPDATE payment SET state='$user[state]',zoneid='$user[planid]' WHERE userid='$user[id]' AND zoneid=''");
if($go){
	echo 'done for '.$user['fname'].'<br>';
}
}
?>