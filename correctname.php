<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
include("conn.php");
$token = mt_rand(9999,99999);
$monthy = date('F');

$check = mysqli_query($conn, "SELECT * FROM benefitiary WHERE owner_id!='' AND acc_name!=''");
if(mysqli_num_rows($check)>0){
while($agenty = mysqli_fetch_array($check)){

$fullname = $agenty['acc_name'];
if(stripos($fullname, ' ')==false){
	$fname = $fullname;
	$lname = '';
}else{
list($fnamed, $lnamed) = explode(' ', $fullname,2);

$fname = $fnamed;
$lname = $lnamed;
}


$postit = mysqli_query($conn, "UPDATE users SET fname='$fname', lname='$lname' WHERE id='$agenty[owner_id]'");

//notify the agent
mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$agenty[owner_id]','notification','Full Name Updated','Your Full Name has been updated on the blisspay App as $fullname to match your $agenty[bank_nane], kindly use $agenty[bank_nane] to withdraw','unread',now())");

echo $fullname.' '.$agenty['owner_id'].'<br>';
}
}
?>