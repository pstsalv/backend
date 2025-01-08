<?php
include('conn.php');
//check through all zones
$checkprop = mysqli_query($conn, "SELECT id,state, branchid FROM users WHERE branchid!='null'");
	while($user=mysqli_fetch_array($checkprop)){

//check zone to collect branch idate
$checkzone = mysqli_query($conn,"SELECT id, branch_rand FROM branches WHERE state='$user[state]' ORDER BY RAND()");
if(mysqli_num_rows($checkzone)>0){
$branchidd = mysqli_fetch_array($checkzone);

mysqli_query($conn, "UPDATE users SET branchid='$branchidd[branch_rand]' WHERE id='$user[id]'");
echo 'done <br>';
}else{
	echo 'not found <br>';
}
	}
?>