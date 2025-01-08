<?php
$smscode = mt_rand(1000,9999);
$token = mt_rand(10000,99999);
include("conn.php");
function kayclean($str) {
      $res = str_replace( array( '\'', '"',
      ',' , ';', '<', '>' ), ' ', $str);
      return $res;
      }
$monthy = date('F');
if((strtoupper($_SERVER['REQUEST_METHOD'] != 'POST'))){
    exit();
}
$paymentDetails = @file_get_contents("php://input");
$headers = getallheaders();
$headers = json_encode($headers);
file_put_contents("file.html", "<pre>" . $paymentDetails . "</pre>");
file_put_contents("file2.html", "<pre>" . $headers . "</pre>");

define('PAYSTACK_SECRET_KEY', $payapi);
if($_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac('sha512', $paymentDetails, PAYSTACK_SECRET_KEY))
exit();
http_response_code(200);

//Events from Paystack
$event = json_decode($paymentDetails);
$chargeEvent = $event->event;

//Insert Data Into Db
//payment was received
if ($chargeEvent == 'charge.success'){
		$reference = $event->data->reference;
$amount = $event->data->amount / 100;
$transamt = $event->data->amount / 100;
$damt = number_format($transamt,2);
$paystatus = $event->data->status;
$trans_id = $event->data->id;
$first_name = $event->data->customer->first_name;
$last_name = $event->data->customer->last_name;
$transemail = $event->data->customer->email;
$customer_code = $event->data->customer->customer_code;
$senderbank = $event->data->authorization->sender_bank;
$senderbankaccno = $event->data->authorization->sender_bank_account_number;
$sendername = $event->data->authorization->sender_name;
$gateway_response = $event->data->gateway_response;
$emailcaps = ucfirst($transemail);
$emailallcaps = strtoupper($transemail);

// Check if payment reference exists
$checkpayref = mysqli_query($conn, "SELECT id FROM payment WHERE payref='$reference'");

if (!$checkpayref) {
    // Handle query error
    die("Database query failed: " . mysqli_error($conn));
}

// Check the number of rows returned
if (mysqli_num_rows($checkpayref) < 1) {
   
//payment doesnt exist
//check if user selected property
$checkmyprop = mysqli_query($conn, "SELECT * FROM myproperty WHERE payment_email IN ('$transemail', '$emailcaps', '$emailallcaps') LIMIT 1");

if(mysqli_num_rows($checkmyprop)>0){
//user selected property
$myprop = mysqli_fetch_array($checkmyprop);
//retrieve user details
	$checku = mysqli_query($conn, "SELECT * FROM users WHERE id='$myprop[userid]'");
if(mysqli_num_rows($checku)>0){
$user = mysqli_fetch_array($checku);
$fname = kayclean($user['fname']);
$lname = kayclean($user['lname']);

//check if customer is first time payer
$checkiffirstpay = mysqli_query($conn, "SELECT id FROM payment WHERE userid='$myprop[userid]' AND paidfor='$myprop[propertyid]' AND status='approved' LIMIT 1");
if(mysqli_num_rows($checkiffirstpay)>0){
//user is a second time payer
		$messagen = "Your $myprop[type] payment of N$damt for #$myprop[propuid] with Bliss Legacy LTD is successful. Thanks.";

}else{
		$messagen = "Welcome to your $myprop[payduration] payment plan. Your payment of N$damt for property with Bliss Legacy LTD is successful. Thanks";
}
//if property is promo
if($myprop['type']=="promo"){
//calculate the bonus

//check to be sure doc fee is not added to payment
if($transamt>=$myprop['propamount']){
//doc fee is added
$outrightbinus = $myprop['propamount']*0.12;
}else{
//doc fee is not added
//if full payment was not paid
//check if this is the final balance

//check if a payment is found within 4 days or within 30days

$checkwenlast = mysqli_query($conn,"SELECT id FROM payment WHERE DATE(date_paid) >= DATE_SUB(CURDATE(), INTERVAL 4 DAY) AND status='approved' AND userid='$user[id]' AND paidfor='$myprop[propertyid]' AND propert_type='promo'");
if(mysqli_num_rows($checkwenlast)>0){

$checkfinbal = mysqli_query($conn,"SELECT SUM(amount) AS totalpay FROM payment WHERE DATE(date_paid) >= DATE_SUB(CURDATE(), INTERVAL 4 DAY) AND status='approved' AND userid='$user[id]' AND paidfor='$myprop[propertyid]' AND propert_type='promo'");
$rowpptdsbw = mysqli_fetch_assoc($checkfinbal);
$sumpptdsbw = $rowpptdsbw['totalpay'];
$totalpaydtdsbw = "$sumpptdsbw";
if($totalpaydtdsbw !==""){
$dailywithd = $totalpaydtdsbw;
}else{
$dailywithd = "0";
};
//end checkfianlbalance

if($dailywithd>0){
//add amount found with amount paid
$sumitup = $dailywithd+$transamt;
//if it equals to the balance
if($sumitup>=$myprop['propamount']){
$outrightbinusadd = $dailywithd*0.08;

$outrightbinusnew = $transamt*0.12;

$outrightbinus = $outrightbinusadd+$outrightbinusnew;
}else{
$outrightbinus = $transamt*0.04;
}

}else{
$outrightbinus = $transamt*0.04 ;
}

}else{

$checkwenlast = mysqli_query($conn,"SELECT id FROM payment WHERE DATE(date_paid) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND status='approved' AND userid='$user[id]' AND paidfor='$myprop[propertyid]' AND propert_type='promo'");
if(mysqli_num_rows($checkwenlast)>0){


//if payment is within 30 days
$checkfinbalt = mysqli_query($conn,"SELECT SUM(amount) AS totalpayt FROM payment WHERE DATE(date_paid) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND status='approved' AND userid='$user[id]' AND paidfor='$myprop[propertyid]' AND propert_type='promo'");
$rowpptdsbwt = mysqli_fetch_assoc($checkfinbalt);
$sumpptdsbwt = $rowpptdsbwt['totalpayt'];
$totalpaydtdsbwt = "$sumpptdsbwt";
if($totalpaydtdsbwt !==""){
$dailywithdt = $totalpaydtdsbwt;
}else{
$dailywithdt = "0";
};
//end checkfianlbalance

if($dailywithdt>0){
//add amount found with amount paid
$sumitupt = $dailywithdt+$transamt;
//if it equals to the balance
if($sumitupt>=$myprop['propamount']){
$outrightbinusaddt = $dailywithdt*0.03;

$outrightbinusnewt = $transamt*0.9;

$outrightbinus = $outrightbinusaddt+$outrightbinusnewt;
}else{
$outrightbinus = $transamt*0.07;
}
}else{
$outrightbinus = $transamt*0.07;
}
}else{
$outrightbinus = $transamt*0.07;

}
}
}
$bonusmt = number_format($outrightbinus);

//if user has agent	
if($user['agent'] != "" && $user['agent'] != "elevated"){

//check if user details is available
$checkagent5 = mysqli_query($conn, "SELECT * FROM users WHERE userid='$user[agent]' AND leftover_batch1!='stopbonus'");
	if(mysqli_num_rows($checkagent5)>0){
		$myagent = mysqli_fetch_array($checkagent5);

//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagent[id]','$outrightbinus','Referral bonus from $fname promo payments','approved',now(),'bonus','add','$monthy','$myagent[account_type]','promo')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$myagent[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$outrightbinus',date=now() WHERE owner='$myagent[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagent[id]','$outrightbinus','bonus','approved',now(),'auto','')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagent[id]','payment','Wallet Credited','Your wallet has been credited with N$bonusmt promo bonus','unread',now())");
}
}
}
//if property is outright
if($myprop['type']=="outright"){

//check to be sure doc fee is not added to payment
if($transamt>=$myprop['propamount']){
//doc fee is added
$outrightbinus = $myprop['propamount']*0.10;
}else{
//doc fee is not added
//if full payment was not paid
//check if this is the final balance

$checkfinbal = mysqli_query($conn,"SELECT SUM(amount) AS totalpay FROM payment WHERE DATE(date_paid) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND status='approved' AND userid='$user[id]' AND paidfor='$myprop[propertyid]' AND propert_type='outright'");
$rowpptdsbw = mysqli_fetch_assoc($checkfinbal);
$sumpptdsbw = $rowpptdsbw['totalpay'];
$totalpaydtdsbw = "$sumpptdsbw";
if($totalpaydtdsbw !==""){
$dailywithd = $totalpaydtdsbw;
}else{
$dailywithd = "0";
};
//end checkfianlbalance

if($dailywithd>0){
//add amount found with amount paid
$sumitup = $dailywithd+$transamt;
//if it equals to the balance
if($sumitup>=$myprop['propamount']){
$outrightbinus = $transamt*0.10;
}else{
$outrightbinus = $transamt*0.09;
}
}else{
$outrightbinus = $transamt*0.09;
}
}
$bonusmt = number_format($outrightbinus);

//if user has agent	
if($user['agent'] != "" && $user['agent'] != "elevated"){

//check if user details is available
$checkagent5 = mysqli_query($conn, "SELECT * FROM users WHERE userid='$user[agent]' AND leftover_batch1!='stopbonus'");
	if(mysqli_num_rows($checkagent5)>0){
		$myagent = mysqli_fetch_array($checkagent5);
//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagent[id]','$outrightbinus','Referral bonus from $fname outright payments','approved',now(),'bonus','add','$monthy','$myagent[account_type]','outright')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$myagent[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$outrightbinus',date=now() WHERE owner='$myagent[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagent[id]','$outrightbinus','bonus','approved',now(),'auto','')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagent[id]','payment','Wallet Credited','Your wallet has been credited with N$bonusmt outright bonus','unread',now())");
}
}
}

//if property is dinner promo
if($myprop['type']=="dinner"){

//check to be sure doc fee is not added to payment
if($transamt>=$myprop['propamount']){
//doc fee is added
$outrightbinus = $myprop['propamount']*0.10;
}else{
//doc fee is not added
//if full payment was not paid
//check if this is the final balance

$checkfinbal = mysqli_query($conn,"SELECT SUM(amount) AS totalpay FROM payment WHERE DATE(date_paid) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND status='approved' AND userid='$user[id]' AND paidfor='$myprop[propertyid]' AND propert_type='outright'");
$rowpptdsbw = mysqli_fetch_assoc($checkfinbal);
$sumpptdsbw = $rowpptdsbw['totalpay'];
$totalpaydtdsbw = "$sumpptdsbw";
if($totalpaydtdsbw !==""){
$dailywithd = $totalpaydtdsbw;
}else{
$dailywithd = "0";
};
//end checkfianlbalance

if($dailywithd>0){
//add amount found with amount paid
$sumitupt = $dailywithd+$transamt;
//if it equals to the balance
if($sumitupt>=$myprop['propamount']){
$outrightbinusaddt = $dailywithd*0.03;

$outrightbinusnewt = $transamt*0.10;

$outrightbinus = $outrightbinusaddt+$outrightbinusnewt;
}else{
$outrightbinus = $transamt*0.07;
}
}else{
$outrightbinus = $transamt*0.07;
}




}
$bonusmt = number_format($outrightbinus);

//if user has agent	
if($user['agent'] != "" && $user['agent'] != "elevated"){

//check if user details is available
$checkagent5 = mysqli_query($conn, "SELECT * FROM users WHERE userid='$user[agent]' AND leftover_batch1!='stopbonus'");
	if(mysqli_num_rows($checkagent5)>0){
		$myagent = mysqli_fetch_array($checkagent5);
//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagent[id]','$outrightbinus','Referral bonus from $fname outright payments','approved',now(),'bonus','add','$monthy','$myagent[account_type]','outright')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$myagent[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$outrightbinus',date=now() WHERE owner='$myagent[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagent[id]','$outrightbinus','bonus','approved',now(),'auto','')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagent[id]','payment','Wallet Credited','Your wallet has been credited with N$bonusmt dinner bonus','unread',now())");
}
}
}

//bonuses ends here
//create payment history
$upd = mysqli_query($conn, "INSERT INTO payment VALUES(NULL,'$user[id]','$transamt','approved','$user[wallet_bal]','$user[userid]','virtual',now(),'Paystack','$gateway_response','$myprop[propertyid]','$reference','$user[state]','$user[planid]','$user[agent]','$myprop[type]','$user[account_no]','$user[date]','$myprop[plot_size]','$user[branchid]','$user[second_agent]','$myprop[logistics]','$senderbank','$senderbankaccno','$sendername')");
if($upd){
//add amount to qucik amount view
mysqli_query($conn, "UPDATE users SET wallet_bal=wallet_bal+$transamt WHERE id='$user[id]'");

//remove the amount from what is left
mysqli_query($conn, "UPDATE myproperty SET amt_paid=amt_paid+'$transamt',amt_remain=amt_remain-'$transamt' WHERE id='$myprop[id]'");

	//create notification
$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$user[id]','payment','Payment successful','$messagen','unread',now())");

//notify the customer care officer
if($user['second_agent']!=""){
				$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$user[second_agent]','payment','$fname\'s payment is sucessful','$fname $lname has just paid N$damt for #$myprop[propuid]','unread',now())");
			}
//notifi the ao
if($user['account_no']!=""){

$checkoffao = mysqli_query($conn, "SELECT id,fname FROM users WHERE userid='$user[account_no]'");
$officerinfoao = mysqli_fetch_array($checkoffao);

$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$officerinfoao[id]','payment','$fname\'s payment is sucessful','$fname $lname has just paid N$damt for #$myprop[propuid]', 'unread',now())");
		}
//keep backup incase			
$stackk = mysqli_query($conn, "INSERT INTO paystack_pay VALUES(NULL,'$user[id]','$transamt','$myprop[propertyid]','$gateway_response','$reference','$paystatus','$trans_id',now(),'webhook')");

//send sms to client
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
  'ref_id' => $token.'2'.$reference,
  'simserver_token' => '',
  'dlr_timeout' => '1',
  'schedule' => ''),
));

$response = curl_exec($curl);

curl_close($curl);
if ($response === false) {
    //echo 'Error: ' .curl_error($ch);
} else {
    $respons = json_decode($response, true);
	if($respons){
	 if (isset($respons['successful'])){
		 mysqli_query($conn, "INSERT INTO smscharges VALUES(NULL,'$user[id]','payment','BlissPay','$messagen','$respons[comment]','$respons[units_calculated]','25',now(),'$respons[successful]','$respons[sms_pages]')");
	 }
	}
}
}
}
}
} else {
    // Payment reference exists
}
}
exit();
mysqli_close($conn);
?>