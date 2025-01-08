<?php
include('conn.php');
$agentcode = mysqli_real_escape_string($conn, $_GET['agentcode']);

$checkprop = mysqli_query($conn, "SELECT id,owner_id,state,zone,zonehead,phone,group_link,date,branchid,marketers,clients,cleared,uncleared FROM allzones WHERE owner_id='$agentcode'");
if(mysqli_num_rows($checkprop)>0){
	while($user=mysqli_fetch_array($checkprop)){

mysqli_query($conn,"UPDATE users SET account_type='Zonal Leader',planid='$user[id]' WHERE userid='$agentcode'");
echo 'done for '.$user['zone'].'<br>';
	}
}
?>