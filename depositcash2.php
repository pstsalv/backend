<?php
header("Access-Control-Allow-Origin: *");
header('content-type: application/json; charset=utf-8');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$token = mt_rand(99999,99999);
$dis = date('Y-m-d h:i:s');
$payref = strtotime($dis);

$method = mysqli_real_escape_string($conn,$_POST['method']);
$staffid = mysqli_real_escape_string($conn,$_POST['staffid']);
$staffname = mysqli_real_escape_string($conn,$_POST['staffname']);
$custid = mysqli_real_escape_string($conn,$_POST['custid']);
$customername = mysqli_real_escape_string($conn,$_POST['custname']);
$prevamt = mysqli_real_escape_string($conn,$_POST['prevamt']);
$me = mysqli_real_escape_string($conn,$_POST['me']);
$notes = mysqli_real_escape_string($conn,$_POST['notes']);
$phone = mysqli_real_escape_string($conn,$_POST['phone']);
$property = mysqli_real_escape_string($conn,$_POST['property']);
$amount = mysqli_real_escape_string($conn,$_POST['amount']);
$prettyamt = number_format($amount,2);

$checkag = mysqli_query($conn, "SELECT id,fname FROM users WHERE userid='$staffid'");
$agenty = mysqli_fetch_array($checkag);

$checku = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position,branchid FROM users WHERE id='$me'");
$user = mysqli_fetch_array($checku);


$checkprop = mysqli_query($conn, "SELECT id,canpay_installment,type FROM property WHERE id='$property'");
$propdet = mysqli_fetch_array($checkprop);

$sent = mysqli_query($conn, "INSERT INTO payment VALUES(NULL,'$custid','$amount','uncleared','$prevamt','$staffid','no',now(),'$method','$notes','$property','$payref','$user[state]','$user[planid]','$user[agent]','$propdet[canpay_installment]','$user[account_no]','$user[date]','$propdet[type]','$user[branchid]','$user[second_agent]','','','','')") or die(mysqli_error($conn));
$newid =  mysqli_insert_id($conn);

if($sent){
	//customer
	mysqli_query($conn, "UPDATE users SET uncleared=uncleared+$amount WHERE id='$custid'");
	//agent
	mysqli_query($conn, "UPDATE users SET uncleared=uncleared+$amount WHERE userid='$staffid'");
	
	
$message = "Your Cash Deposit of $prettyamt to $staffname has been deposited under uncleared balance. This should be confirmed within the hour. Thanks";

$messageagent = "$customername just gave you cash of ₦$prettyamt, this has been placed under uncleared balance pending when you submit payment to Office. Kindly note that your account will be susspended if you have more than two uncleared payment in your payment history.";
//customer
$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$custid','notification','Cash Deposit','$message','unread',now())");
//agent
$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$agenty[id]','notification','Cash Received','$messageagent','unread',now())");


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
  'to' => $phone,
  'message' => $message,
  'type' => '0',
  'routing' => '4',
  'ref_id' => $token,
  'simserver_token' => '',
  'dlr_timeout' => '1',
  'schedule' => ''),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;

$data = array(
'status'=>'ready',
'payref'=>$newid,
'depoamt'=>$amount,
'depoamtfee'=>'0',
'totaldepo'=>$amount,
'message'=>'Proceed to pay'
);
$str = json_encode($data);
echo $str;

}else{
	$data = array(
'status'=>'error',
'message'=>'Something went wrong, knidly try again.'
);
$str = json_encode($data);
echo $str;
}
?>