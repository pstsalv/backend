<?php
header("Access-Control-Allow-Origin: *");
//header('content-type: application/json; charset=utf-8');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");
$dis = date('Y-m-d h:i:s');
$token = mt_rand(10000,99999);
//$payref = strtotime($dis);
$trans_id = mysqli_real_escape_string($conn,$_REQUEST['trans_id']);
$amt_paid = mysqli_real_escape_string($conn,$_REQUEST['amt_paid']);
$message = mysqli_real_escape_string($conn,$_REQUEST['message']);
$status = mysqli_real_escape_string($conn,$_REQUEST['status']);
$prevamt = mysqli_real_escape_string($conn,$_REQUEST['prevamt']);
$usertoken = mysqli_real_escape_string($conn,$_REQUEST['usertoken']);

$owner_id = mysqli_real_escape_string($conn,$_REQUEST['owner_id']);
$propid = mysqli_real_escape_string($conn,$_REQUEST['propid']);
$reference = mysqli_real_escape_string($conn,$_REQUEST['reference']);
$payref = mysqli_real_escape_string($conn,$_REQUEST['reference']);
$damt = number_format($amt_paid,2);
if($status=="success"){
	$checku = mysqli_query($conn, "SELECT * FROM users WHERE id='$owner_id'");
	$user = mysqli_fetch_array($checku);
	
	
$checkprop = mysqli_query($conn, "SELECT id,canpay_installment,type FROM property WHERE id='$propid'");
$propdet = mysqli_fetch_array($checkprop);

$upd = mysqli_query($conn, "INSERT INTO payment VALUES(NULL,'$owner_id','$amt_paid','approved','$prevamt','$usertoken','auto',now(),'Paystack','$message','$propid','$payref','$user[state]','$user[planid]','$user[agent]','$propdet[canpay_installment]','$user[account_no]','$user[date]','$propdet[type]','$user[branchid]','$user[second_agent]')");
if($upd){
	
	$sent = mysqli_query($conn, "UPDATE users SET wallet_bal=wallet_bal+$amt_paid WHERE id='$owner_id'");
	mysqli_query($conn, "UPDATE myproperty SET amt_paid=amt_paid+'$amt_paid',amt_remain=amt_remain-'$amt_paid' WHERE userid='$owner_id' AND propuid='$propid'");
	
$messagen = "Your payment of $damt is successful. This should now reflect on your available payment balance. Regards. Payment Reference #$payref";

$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$owner_id','notification','Payment sucessful','$messagen','unread',now())");

$stackk = mysqli_query($conn, "INSERT INTO paystack_pay VALUES(NULL,'$owner_id','$amt_paid','$propid','$message','$reference','$status','$trans_id',now(),'app')");

if($sent){

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://app.smartsmssolutions.com/io/api/client/v1/sms/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('token' => 'kr2Wm1k2bncwP3yWYy18nXbkgDvAqPmYFLNBPRDZ1wCPBWZF3M',
  'sender' => 'BlissPay',
  'to' => $user['phone'],
  'message' => $messagen,
  'type' => '0',
  'routing' => '4',
  'ref_id' => $token,
  'simserver_token' => '',
  'dlr_timeout' => '1',
  'schedule' => ''),
));

$response = curl_exec($curl);

curl_close($curl);

echo 'ok';
}else{
die(mysqli_error($conn));
	
}
}else{
	echo '{"status":"failed","message":"'.$status.'"}';
}
}else{
	echo '{"status":"failed","message":"'.$status.'"}';
}
?>