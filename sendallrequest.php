<?php
header("Access-Control-Allow-Origin: *");
header('content-type: application/json; charset=utf-8');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$token = mt_rand(99999,99999);
$dis = date('Y-m-d h:i:s');
$payref = strtotime($dis);

$property = mysqli_real_escape_string($conn,$_POST['property']);
$customer = mysqli_real_escape_string($conn,$_POST['customer']);
$agentid = mysqli_real_escape_string($conn,$_POST['agentid']);
$custname = mysqli_real_escape_string($conn,$_POST['custname']);
$amount = mysqli_real_escape_string($conn,$_POST['amount']);
$notes = mysqli_real_escape_string($conn,$_POST['notes']);
$paymethod = mysqli_real_escape_string($conn,$_POST['paymethod']);
$prettyamt = number_format($amount,2);

$checkag = mysqli_query($conn, "SELECT id,fname,phone FROM users WHERE userid='$agentid'");
$agenty = mysqli_fetch_array($checkag);

$checku = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$customer'");
$user = mysqli_fetch_array($checku);

$sent = mysqli_query($conn, "INSERT INTO payment_request VALUES(NULL,'$property','$customer','$agentid','$custname','$amount','$notes','$paymethod','pending',now(),'unread')") or die(mysqli_error($conn));


$newid =  mysqli_insert_id($conn);
if($sent){
$messageagent = "$custname is requesting to deposit a cash of ₦$prettyamt to you for the property (#$property), Click Accept button to receive the cash.";
//customer
$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$customer','notification','Cash Deposit Request','DO NOT give your cash to any Agent/Staff unless they accept your deposit request.','unread',now())");
//agent
$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$agentid','notification','Cash Deposit Request','$messageagent','unread',now())");


$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://smartsmssolutions.com/io/client/v1/voiceotp/send/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('token' => 'G1tisaOnfDfmsz0XbHEg1p05PUDn2JJCYMHeVJpt03PpL1GU7M','phone' => $phone,'otp' => $smscode,'class' => 'AEYBPV3VKA','ref_id' => $token),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;

$data = array(
'status'=>'sent',
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