<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('content-type: application/json; charset=utf-8');
header('Cache-Control: no-cache');
//include_once("conn.php");

$ip = getenv('HTTP_CLIENT_IP')?:
getenv('HTTP_X_FORWARDED_FOR')?:
getenv('HTTP_X_FORWARDED')?:
getenv('HTTP_FORWARDED_FOR')?:
getenv('HTTP_FORWARDED')?:
getenv('REMOTE_ADDR');

if(!isset($_POST['addedversion'])){

 echo '{"status":"notenough", "message":"You must update your app first before you can continue"}';
}else{
$addedversion	= mysqli_real_escape_string($conn, $_POST['addedversion']);

if(isset($_POST['account_bank'])){
$account_bank = mysqli_real_escape_string($conn, $_POST['account_bank']);
}

if(isset($_POST['updateforce'])){
$updateforce = mysqli_real_escape_string($conn, $_POST['updateforce']);
}

$usertoken	= mysqli_real_escape_string($conn, $_POST['usertoken']);
$bankcd	= mysqli_real_escape_string($conn, $_POST['bankcd']);
$account_bank_name = mysqli_real_escape_string($conn, $_POST['account_bank_name']);
$account_number	= mysqli_real_escape_string($conn, $_POST['accno']);
$reference = 'blisspay-'.mt_rand(1000000,9999990);

if($account_bank=="Mint MFB" || $account_number=="1102503432" || $account_bank_name=="NDENWA ESTHER BOSE" || $account_number=="1100589396" || $account_bank_name=="9mobile 9Payment Service Bank"){
echo '{"status":"notenough", "message":"Your account has been blacklisted due to fraudulent activities"}';
}else{

$me = mysqli_real_escape_string($conn, $_POST['me']);
if(isset($_POST['pincode'])){
$pincodet = mysqli_real_escape_string($conn, $_POST['pincode']);
$pincode = base64_encode($pincodet);
}else{
	$pincode = "";
}
$notes = mysqli_real_escape_string($conn, $_POST['notes']);
$account_nm	= mysqli_real_escape_string($conn, $_POST['account_nm']);
$amountty = mysqli_real_escape_string($conn, $_POST['amount']);
$amountt = floatval(preg_replace('/[^\d.]/', '', $amountty));
//$descrp	= mysqli_real_escape_string($conn, $_POST['descrp']);
$recipient_code	= mysqli_real_escape_string($conn, $_POST['recipient_code']);
//$debit_currency	= mysqli_real_escape_string($conn, $_POST['debit_currency']);
$finalamt = "$amountt"+100;
$amounttfm = number_format($amountt,2);
$today = date('d-m-Y');

function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

$myuuid = guidv4();

if(isset($_POST['updateforce'])){
//check if user entered pincode
$checkpin = mysqli_query($conn, "SELECT * FROM users WHERE id='$me' AND pincode='$pincode' AND pincode!='' AND account_type!='customer' AND pincode!='2222' AND pincode!='1234' AND position!='Customer Care' AND position!='Head Customer Care'");
if(mysqli_num_rows($checkpin)>0){
	$senderinfo = mysqli_fetch_array($checkpin);
	//check if sender has enough money


$sendername = trim($senderinfo['fname'].' '.$senderinfo['lname']);

	$checkamtpp = mysqli_query($conn,"SELECT SUM(amount) AS amttpaid FROM mybonus WHERE (status='approved' OR status='collected' OR status='reversed') AND owner = '$me' AND salary_id != 'suspected'");
$rowpp = mysqli_fetch_assoc($checkamtpp);
$sumpp = $rowpp['amttpaid'];
$totalpayd = "$sumpp";
if($totalpayd!==""){
$mybalp = $totalpayd;
}else{
$mybalp = "0";
};


//user has enough money
if($mybalp >99){
$sender = $mybalp;

if($sender >=$amountt){
$removit = mysqli_query($conn, "UPDATE mybonus SET status='collected',amount=amount-'$amountt' WHERE owner='$me'") or die(mysqli_error($conn));
if($removit){

	 mysqli_query($conn,"INSERT INTO withdrawals VALUES(NULL,'$me','$amountt','successful',now(), '$reference','$account_nm','$account_number','$account_bank_name','Withdrawal Successful','$ip')") or die(mysqli_error($conn));
		$transid = mysqli_insert_id($conn);
		
	 mysqli_query($conn,"INSERT INTO notification VALUES(NULL,'$me','notification','Withdrawal successful','N $amounttfm was withdrwn from your earnings to $account_nm - $account_bank_name successfully.','unread',now())");
  



 $url = "https://api.paystack.co/transfer";
  $fields = [
    'source' => "balance",
    'amount' => $amountt*100,
    "reference" => $myuuid,
    'recipient' => $recipient_code,
    'reason' => $notes.'/ '
  ];

  $fields_string = http_build_query($fields);
  //open connection
  $ch = curl_init();
  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer sk_live_250c0f0d8ca26ff0f78723498fe18e748431c84b",
    "Cache-Control: no-cache",
  ));
  //So that curl_exec returns the contents of the cURL; rather than echoing it

  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

  //execute post
  $result = curl_exec($ch);
 // echo $result;
  if($result){
$results = json_decode($result, true);

if($results['status']==true){
	$checkbalwer = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$me'");
	
$balafter = mysqli_fetch_array($checkbalwer);

	$recipient = $results['data']['recipient'];
	$transfer_code = $results['data']['transfer_code'];
	$reference = $results['data']['reference'];
	$id = $results['data']['id'];
	$request = $results['data']['request'];

 echo '{"status":"success", "message":"Your transfer was successful", "transid":"'.$transid.'"}';
 

}else{
	
	//echo $result;
	echo '{"status":"notenough","message":"Transfer not available at the moment, kindly try after few minutes"}';
 
	$reverseit = mysqli_query($conn, "UPDATE mybonus SET status='reversed',amount=amount+'$amountt' WHERE owner='$me'") or die(mysqli_error($conn));

	 mysqli_query($conn,"INSERT INTO withdrawals VALUES(NULL,'$me','$amountt','reversed',now(), '$reference','$account_nm','$account_number','$account_bank_name','Withdrawal Reversed','$ip')") or die(mysqli_error($conn));
		
	 mysqli_query($conn,"INSERT INTO notification VALUES(NULL,'$me','notification','Withdrawal Reversed','N $amounttfm was reversed due to transfer error.','unread',now())");
  

}
  }else{
echo '{"status":"notenough","message":"We encountered an error while completing transaction"}';
}
 
 

  }else{
	  
echo '{"status":"notenough","message":"We encountered an error while completing transaction"}';
}
}else{
	echo '{"status":"notenough","message":"Insufficient balance for this transaction"}';
}

}else{
	echo '{"status":"notenough","message":"Insufficient balance for this transaction"}';
}

}else{
	echo '{"status":"notenough","message":"You have entered wrong Pin"}';
}

}else{
	echo '{"status":"notenough","message":"Kindly Update your App First"}';
}
}
}
?>