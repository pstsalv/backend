<?php
include('conn.php');
//check through all zones
$checkprop = mysqli_query($conn, "SELECT id,owner_id,state,zone,zonehead,phone,group_link,date,branchid,marketers,clients,cleared,uncleared FROM allzones");
	while($dzones=mysqli_fetch_array($checkprop)){

//check all zone members - customers
$checkallzmc = mysqli_query($conn,"SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE planid='$dzones[id]' AND account_type='customer'");
$allzonecustomers = mysqli_num_rows($checkallzmc);

//check all zone members - agents
$checkallzmca = mysqli_query($conn,"SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE planid='$dzones[id]' AND account_type!='customer'");
$allzoneagents = mysqli_num_rows($checkallzmca);

//check all zone cleared amount
	  $checkamtppu2 = mysqli_query($conn,"SELECT SUM(uncleared) AS amttpaidu2 FROM users WHERE planid='$dzones[id]' AND account_type='agent'");
$rowppu2 = mysqli_fetch_assoc($checkamtppu2);
$sumppmu2 = $rowppu2['amttpaidu2'];
$totalpaydu2 = "$sumppmu2";
if($totalpaydu2!=""){
$alluncleared = $totalpaydu2;
}else{
	$alluncleared = 0;
}




//calculate ll payment collected by agent uncleared
  $checkamtppu = mysqli_query($conn,"SELECT SUM(wallet_bal) AS amttpaidu FROM users WHERE planid='$dzones[id]' AND account_type='agent'");
$rowppu = mysqli_fetch_assoc($checkamtppu);
$sumppmu = $rowppu['amttpaidu'];
$totalpaydu = "$sumppmu";
if($totalpaydu!=""){
$allcleared = $totalpaydu;
}else{
	$allcleared = 0;
}


mysqli_query($conn,"UPDATE allzones SET clients='$allzonecustomers',marketers='$allzoneagents',cleared='$allcleared',uncleared='$alluncleared' WHERE id='$dzones[id]'");
echo 'done for '.$dzones['zone'].'<br>';
	}
?>