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
$event = json_decode($paymentDetails);
$chargeEvent = $event->event;
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
$sendernamed = $event->data->authorization->sender_name;
$gateway_response = $event->data->gateway_response;
$emailcaps = ucfirst($transemail);
$emailallcaps = strtoupper($transemail);

$sendername = kayclean($sendernamed);
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
		$messagen = "Your $myprop[type] payment of N$damt for #$myprop[propuid] with Bliss Legacy LTD is successful. Thanks";

}else{
		$messagen = "Welcome to your $myprop[payduration] payment plan. Your payment of N$damt for property with Bliss Legacy LTD is successful. Thanks";
}



//if property is promo
if($myprop['type']=="promo"){
//calculate the bonus

//check to be sure doc fee is not added to payment
if($transamt>=$myprop['propamount']){
//doc fee is added
$outrightbinus = $myprop['propamount']*0.15;
}else{
//doc fee is not added
//if full payment was not paid
//check if this is the final balance

//check if a payment is found within 5 days

$checkwenlast = mysqli_query($conn,"SELECT id FROM payment WHERE DATE(date_paid) >= DATE_SUB(CURDATE(), INTERVAL 5 DAY) AND status='approved' AND userid='$user[id]' AND paidfor='$myprop[propertyid]' AND propert_type='promo'");
if(mysqli_num_rows($checkwenlast)>0){
//check if its final payment
$checkfinbal = mysqli_query($conn,"SELECT SUM(amount) AS totalpay FROM payment WHERE status='approved' AND userid='$user[id]' AND paidfor='$myprop[propertyid]' AND propert_type='promo'");
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
$outrightbinusadd = $dailywithd*0.07;

$outrightbinusnew = $transamt*0.15;

$outrightbinus = $outrightbinusadd+$outrightbinusnew;
}else{
$outrightbinus = $transamt*0.08;
}

}else{
$outrightbinus = $transamt*0.08 ;
}

}else{
$outrightbinus = $transamt*0.08 ;

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
//promo ends
//begin outright
}

if($myprop['type']=="outright" || $myprop['type']=="dinner" || $myprop['type']=="weekly" || $myprop['type']=="monthly"){
//calculate the bonus

//check to be sure doc fee is not added to payment
if($transamt>=$myprop['propamount']){
//doc fee is added
$outrightbinus = $myprop['propamount']*0.12;
}else{
//doc fee is not added
//if full payment was not paid
//check if this is the final balance

//check if a payment is found within 14 days

$checkwenlast = mysqli_query($conn,"SELECT id FROM payment WHERE DATE(date_paid) >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) AND status='approved' AND userid='$user[id]' AND paidfor='$myprop[propertyid]' AND propert_type='promo'");
if(mysqli_num_rows($checkwenlast)>0){
//check if its final payment
$checkfinbal = mysqli_query($conn,"SELECT SUM(amount) AS totalpay FROM payment WHERE status='approved' AND userid='$user[id]' AND paidfor='$myprop[propertyid]' AND propert_type='promo'");
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
$outrightbinusadd = $dailywithd*0.04;

$outrightbinusnew = $transamt*0.12;

$outrightbinus = $outrightbinusadd+$outrightbinusnew;
}else{
$outrightbinus = $transamt*0.08;
}

}else{
$outrightbinus = $transamt*0.08 ;
}

}else{
$outrightbinus = $transamt*0.08 ;

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


//if user agent has another agent (second Generation)
if($myagent['agent'] != "" && $myagent['agent'] != "elevated"){

//Outright 2nd generation is 20% of agent bonus

$givebonus = $outrightbinus*0.15;


//check if second gen user details is available

$checkagentsg = mysqli_query($conn, "SELECT * FROM users WHERE userid='$myagent[agent]' AND YEAR(date) = YEAR(CURDATE())");
	if(mysqli_num_rows($checkagentsg)>0){
		$myagentsec = mysqli_fetch_array($checkagentsg);

//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagentsec[id]','$givebonus','Second Generation referral bonus','approved',now(),'bonus','add','$monthy','$myagentsec[account_type]','$myprop[type]')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$myagentsec[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$givebonus',date=now() WHERE owner='$myagentsec[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagentsec[id]','$givebonus','bonus','approved',now(),'auto','secondgen')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagentsec[id]','payment','Wallet Credited','Your wallet has been credited with second generation bonus','unread',now())");
}
}


//outright ends
}

if($myprop['type']=="daily"){



//give officer 6%
$giveamt = str_replace(',', '', $myprop['bonusamt']);
$totalinmonth = $giveamt*31;
if($transamt==$totalinmonth){

$leftcal = $transamt-$totalinmonth;
$balleft = $leftcal*0.08;

$consultbonus = $totalinmonth*0.06;
$officerbonus = $totalinmonth*0.06;
$officerbonussec = $totalinmonth*0.08;
$seconbonus = $consultbonus*0.15;
$seconbonusao = $officerbonus*0.10;

}else{
$balleft = 0;

$consultbonus = $transamt*0.06;
$officerbonus = $transamt*0.06;
$officerbonussec = $transamt*0.08;
$seconbonus = $consultbonus*0.15;
$seconbonusao = $officerbonus*0.10;
}



//if daily
//this is the payment modalities for account officers

//this is 1st month

// Check if users were registered within the last 31 days
$checkclientdate = mysqli_query($conn, "SELECT id FROM users WHERE DATE(date) >= DATE_SUB(CURDATE(), INTERVAL 31 DAY) AND id='" . $user['id'] . "' AND YEAR(date) = YEAR(CURDATE())");
if(mysqli_num_rows($checkclientdate)>0){


//give consultant/agent 6%
if($user['agent'] != "" && $user['agent'] != "elevated"){

$agentbonuses = $consultbonus;
$agentbonusesfm = number_format($agentbonuses);


//check if user details is available
$checkagent5 = mysqli_query($conn, "SELECT * FROM users WHERE userid='$user[agent]' AND leftover_batch1!='stopbonus'");
	if(mysqli_num_rows($checkagent5)>0){
		$myagent = mysqli_fetch_array($checkagent5);

//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagent[id]','$agentbonuses','Referral bonus from $fname $myprop[type] payments','approved',now(),'bonus','add','$monthy','$myagent[account_type]','$myprop[type]')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$myagent[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$agentbonuses',date=now() WHERE owner='$myagent[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagent[id]','$agentbonuses','bonus','approved',now(),'auto','')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagent[id]','payment','Wallet Credited','Your wallet has been credited with N$agentbonusesfm consultant bonuse','unread',now())");

//if user agent has another agent (second Generation)
if($myagent['agent'] != "" && $myagent['agent'] != "elevated"){

$seconbonusd = $agentbonuses*0.10;

//check if second gen user details is available

$checkagentsg = mysqli_query($conn, "SELECT * FROM users WHERE userid='$myagent[agent]' AND YEAR(date) = YEAR(CURDATE())");
	if(mysqli_num_rows($checkagentsg)>0){
		$myagentsec = mysqli_fetch_array($checkagentsg);

//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagentsec[id]','$seconbonusd','Second Generation referral bonus','approved',now(),'bonus','add','$monthy','$myagentsec[account_type]','$myprop[type]')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$myagentsec[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$seconbonusd',date=now() WHERE owner='$myagentsec[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagentsec[id]','$seconbonusd','bonus','approved',now(),'auto','secondgen')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagentsec[id]','payment','Wallet Credited','Your wallet has been credited with second generation bonus','unread',now())");
}
}
}
}
//end consultant bonus

//begin account officer bonus
//give AO 6%
if($user['account_no']!=""){
$aobonuses = $officerbonus+$balleft;
$aobonusesfm = number_format($aobonuses);


$checkoffao = mysqli_query($conn, "SELECT * FROM users WHERE userid='$user[account_no]' AND leftover_batch1!='stopbonus'");
if(mysqli_num_rows($checkoffao)>0){
$officerinfoao = mysqli_fetch_array($checkoffao);

//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$officerinfoao[id]','$aobonuses','Referral bonus from $fname payments','approved',now(),'bonus','add','$monthy','$officerinfoao[account_type]','$myprop[type]')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$officerinfoao[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$aobonuses',date=now() WHERE owner='$officerinfoao[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$officerinfoao[id]','$aobonuses','bonus','approved',now(),'auto','')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$officerinfoao[id]','payment','Wallet Credited','Your wallet has been credited with N$aobonusesfm $myprop[type] bonus','unread',now())");


$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$officerinfoao[id]','payment','$fname\'s payment is sucessful','$fname $lname has just paid N$damt for #$myprop[propuid]', 'unread',now())");


//if ao has second generation 
if($officerinfoao['agent'] != "" && $officerinfoao['agent'] != "elevated"){
//check if ao second gen user details is available
$checkagentsg = mysqli_query($conn, "SELECT * FROM users WHERE userid='$officerinfoao[agent]' AND leftover_batch1!='stopbonus'");
	if(mysqli_num_rows($checkagentsg)>0){
		$myagentsec = mysqli_fetch_array($checkagentsg);

$seconbonusaod = $aobonuses*0.10;


//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagentsec[id]','$seconbonusaod','Second Generation referral bonus','approved',now(),'bonus','add','$monthy','$myagentsec[account_type]','$myprop[type]')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$myagentsec[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$seconbonusaod',date=now() WHERE owner='$myagentsec[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagentsec[id]','$seconbonusaod','bonus','approved',now(),'auto','secondgen')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagentsec[id]','payment','Wallet Credited','Your wallet has been credited with second generation bonus','unread',now())");
}
}
}
}
//end account officer bonus
}
//1st month ends here

//begin second month
//check if user joined more than 31 days
$checkclientdate = mysqli_query($conn, "SELECT id FROM users WHERE DATE(date) < DATE_SUB(CURDATE(), INTERVAL 31 DAY) AND id='$user[id]'");

if(mysqli_num_rows($checkclientdate) > 0){
    
//begin account officer bonus
//give AO 8%
if($user['account_no']!=""){
$aobonuses = $officerbonussec;
$aobonusesfm = number_format($aobonuses);


$checkoffao = mysqli_query($conn, "SELECT * FROM users WHERE userid='$user[account_no]' AND leftover_batch1!='stopbonus'");
if(mysqli_num_rows($checkoffao)>0){
$officerinfoao = mysqli_fetch_array($checkoffao);

//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$officerinfoao[id]','$aobonuses','Referral bonus from $fname payments','approved',now(),'bonus','add','$monthy','$officerinfoao[account_type]','$myprop[type]')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$officerinfoao[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$aobonuses',date=now() WHERE owner='$officerinfoao[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$officerinfoao[id]','$aobonuses','bonus','approved',now(),'auto','')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$officerinfoao[id]','payment','Wallet Credited','Your wallet has been credited with N$aobonusesfm $myprop[type] bonus','unread',now())");


$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$officerinfoao[id]','payment','$fname\'s payment is sucessful','$fname $lname has just paid N$damt for #$myprop[propuid]', 'unread',now())");


//if ao has second generation 
if($officerinfoao['agent'] != "" && $officerinfoao['agent'] != "elevated"){
//check if ao second gen user details is available
$checkagentsg = mysqli_query($conn, "SELECT * FROM users WHERE userid='$officerinfoao[agent]' AND leftover_batch1!='stopbonus'");
	if(mysqli_num_rows($checkagentsg)>0){
		$myagentsec = mysqli_fetch_array($checkagentsg);

//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagentsec[id]','$seconbonusao','Second Generation referral bonus','approved',now(),'bonus','add','$monthy','$myagentsec[account_type]','$myprop[type]')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$myagentsec[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$seconbonusao',date=now() WHERE owner='$myagentsec[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagentsec[id]','$seconbonusao','bonus','approved',now(),'auto','secondgen')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagentsec[id]','payment','Wallet Credited','Your wallet has been credited with second generation bonus','unread',now())");
}
}
}
}

}
//second month ends





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
  CURLOPT_POSTFIELDS => array('token' => 'G1tisaOnfDfmsz0XbHEg1p05PUDn2JJCYMHeVJpt03PpL1GU7M',
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


$checkdprop = mysqli_query($conn, "SELECT * FROM property WHERE id='$myprop[propertyid]'");
if(mysqli_num_rows($checkdprop)>0){
$allprop = mysqli_fetch_array($checkdprop);

if($transamt>=$myprop['propamount']){
mysqli_query($conn, "INSERT INTO allocation VALUES(NULL,'$user[id]','$allprop[title]','','$allprop[type]','1','pending','auto','$allprop[amount]','$transamt',now(),'virtual','')");
}
}else{
mysqli_query($conn, "INSERT INTO allocation VALUES(NULL,'$user[id]','unknown','','$myprop[type]','1','pending','auto','$myprop[propamount]','$transamt',now(),'virtual','')");
}


}
}else{

$checkmypropt = mysqli_query($conn, "SELECT * FROM users WHERE email IN ('$transemail', '$emailcaps', '$emailallcaps') LIMIT 1");
if(mysqli_num_rows($checkmypropt)>0){


$user = mysqli_fetch_array($checkmypropt);
$fname = kayclean($user['fname']);
$lname = kayclean($user['lname']);


$messagen = "Your payment of N$damt with Bliss Legacy LTD is successful. Kindly attach a property to this payment";



//give officer 6%
$giveamt = 1000;
$totalinmonth = $giveamt*31;
if($transamt>=$totalinmonth){
$leftcal = $transamt-$totalinmonth;
$balleft = $leftcal*0.08;
$consultbonus = $totalinmonth*0.06;
$officerbonus = $totalinmonth*0.06;
$officerbonussec = $totalinmonth*0.08;
$seconbonus = $totalinmonth*0.03;
$seconbonusao = $totalinmonth*0.02;

}else{
$balleft = 0;
$consultbonus = $transamt*0.06;
$officerbonus = $transamt*0.06;
$officerbonussec = $transamt*0.08;
$seconbonus = $transamt*0.03;
$seconbonusao = $transamt*0.02;
}



// Check if users were registered within the last 31 days
$checkclientdate = mysqli_query($conn, "SELECT id FROM users WHERE DATE(date) >= DATE_SUB(CURDATE(), INTERVAL 31 DAY) AND id='" . $user['id'] . "' AND YEAR(date) = YEAR(CURDATE())");
if(mysqli_num_rows($checkclientdate)>0){



//give consultant/agent 6%
if($user['agent'] != "" && $user['agent'] != "elevated"){

$agentbonuses = $consultbonus;
$agentbonusesfm = number_format($agentbonuses);


//check if user details is available
$checkagent5 = mysqli_query($conn, "SELECT * FROM users WHERE userid='$user[agent]' AND leftover_batch1!='stopbonus'");
	if(mysqli_num_rows($checkagent5)>0){
		$myagent = mysqli_fetch_array($checkagent5);

//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagent[id]','$agentbonuses','Referral bonus from $fname payments','approved',now(),'bonus','add','$monthy','$myagent[account_type]','unknown')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$myagent[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$agentbonuses',date=now() WHERE owner='$myagent[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagent[id]','$agentbonuses','bonus','approved',now(),'auto','')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagent[id]','payment','Wallet Credited','Your wallet has been credited with N$agentbonusesfm consultant bonuse','unread',now())");

//if user agent has another agent (second Generation)
if($myagent['agent'] != "" && $myagent['agent'] != "elevated"){

$seconbonusd = $agentbonuses*0.10;

//check if second gen user details is available

$checkagentsg = mysqli_query($conn, "SELECT * FROM users WHERE userid='$myagent[agent]' AND YEAR(date) = YEAR(CURDATE())");
	if(mysqli_num_rows($checkagentsg)>0){
		$myagentsec = mysqli_fetch_array($checkagentsg);

//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagentsec[id]','$seconbonusd','Second Generation referral bonus','approved',now(),'bonus','add','$monthy','$myagentsec[account_type]','unknown')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$myagentsec[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$seconbonusd',date=now() WHERE owner='$myagentsec[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagentsec[id]','$seconbonusd','bonus','approved',now(),'auto','secondgen')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagentsec[id]','payment','Wallet Credited','Your wallet has been credited with second generation bonus','unread',now())");
}
}
}
}
//end consultant bonus

//begin account officer bonus
//give AO 6%
if($user['account_no']!=""){
$aobonuses = $officerbonus+$balleft;
$aobonusesfm = number_format($aobonuses);


$checkoffao = mysqli_query($conn, "SELECT * FROM users WHERE userid='$user[account_no]' AND leftover_batch1!='stopbonus'");
if(mysqli_num_rows($checkoffao)>0){
$officerinfoao = mysqli_fetch_array($checkoffao);

//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$officerinfoao[id]','$aobonuses','Referral bonus from $fname payments','approved',now(),'bonus','add','$monthy','$officerinfoao[account_type]','unknown')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$officerinfoao[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$aobonuses',date=now() WHERE owner='$officerinfoao[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$officerinfoao[id]','$aobonuses','bonus','approved',now(),'auto','')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$officerinfoao[id]','payment','Wallet Credited','Your wallet has been credited with N$aobonusesfm unknown bonus','unread',now())");


$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$officerinfoao[id]','payment','$fname\'s payment is sucessful','$fname $lname has just paid N$damt for unknown property', 'unread',now())");


//if ao has second generation 
if($officerinfoao['agent'] != "" && $officerinfoao['agent'] != "elevated"){
//check if ao second gen user details is available
$checkagentsg = mysqli_query($conn, "SELECT * FROM users WHERE userid='$officerinfoao[agent]' AND leftover_batch1!='stopbonus'");
	if(mysqli_num_rows($checkagentsg)>0){
		$myagentsec = mysqli_fetch_array($checkagentsg);

$seconbonusaod = $aobonuses*0.10;


//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagentsec[id]','$seconbonusaod','Second Generation referral bonus','approved',now(),'bonus','add','$monthy','$myagentsec[account_type]','unknown')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$myagentsec[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$seconbonusaod',date=now() WHERE owner='$myagentsec[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagentsec[id]','$seconbonusaod','bonus','approved',now(),'auto','secondgen')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagentsec[id]','payment','Wallet Credited','Your wallet has been credited with second generation bonus','unread',now())");
}
}
}
}
//end account officer bonus
}
//1st month ends here

//begin second month
//check if user joined after 31 days or joined this year
$checkclientdate = mysqli_query($conn, "SELECT id FROM users WHERE DATE(date) < DATE_SUB(CURDATE(), INTERVAL 31 DAY) AND id='$user[id]'");

if(mysqli_num_rows($checkclientdate) > 0){
    
//begin account officer bonus
//give AO 8%
if($user['account_no']!=""){
$aobonuses = $officerbonussec;
$aobonusesfm = number_format($aobonuses);


$checkoffao = mysqli_query($conn, "SELECT * FROM users WHERE userid='$user[account_no]' AND leftover_batch1!='stopbonus'");
if(mysqli_num_rows($checkoffao)>0){
$officerinfoao = mysqli_fetch_array($checkoffao);

//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$officerinfoao[id]','$aobonuses','Referral bonus from $fname payments','approved',now(),'bonus','add','$monthy','$officerinfoao[account_type]','unknown')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$officerinfoao[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$aobonuses',date=now() WHERE owner='$officerinfoao[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$officerinfoao[id]','$aobonuses','bonus','approved',now(),'auto','')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$officerinfoao[id]','payment','Wallet Credited','Your wallet has been credited with N$aobonusesfm uknown bonus','unread',now())");


$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$officerinfoao[id]','payment','$fname\'s payment is sucessful','$fname $lname has just paid N$damt for unknown property', 'unread',now())");


//if ao has second generation 
if($officerinfoao['agent'] != "" && $officerinfoao['agent'] != "elevated"){
//check if ao second gen user details is available
$checkagentsg = mysqli_query($conn, "SELECT * FROM users WHERE userid='$officerinfoao[agent]' AND leftover_batch1!='stopbonus'");
	if(mysqli_num_rows($checkagentsg)>0){
		$myagentsec = mysqli_fetch_array($checkagentsg);

//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagentsec[id]','$seconbonusao','Second Generation referral bonus','approved',now(),'bonus','add','$monthy','$myagentsec[account_type]','unknown')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$myagentsec[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$seconbonusao',date=now() WHERE owner='$myagentsec[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagentsec[id]','$seconbonusao','bonus','approved',now(),'auto','secondgen')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagentsec[id]','payment','Wallet Credited','Your wallet has been credited with second generation bonus','unread',now())");
}
}
}
}

}
//second month ends



//create payment history
$upd = mysqli_query($conn, "INSERT INTO payment VALUES(NULL,'$user[id]','$transamt','approved','$user[wallet_bal]','$user[userid]','virtual',now(),'Paystack','$gateway_response','1','$reference','$user[state]','$user[planid]','$user[agent]','unknown','$user[account_no]','$user[date]','unknown','$user[branchid]','$user[second_agent]','unknown','$senderbank','$senderbankaccno','$sendername')");
if($upd){
//add amount to qucik amount view
mysqli_query($conn, "UPDATE users SET wallet_bal=wallet_bal+$transamt WHERE id='$user[id]'");

	//create notification
$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$user[id]','payment','Payment successful','$messagen','unread',now())");

//notify the customer care officer
if($user['second_agent']!=""){
				$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$user[second_agent]','payment','$fname\'s payment is sucessful','$fname $lname has just paid N$damt','unread',now())");
			}


//keep backup incase			
$stackk = mysqli_query($conn, "INSERT INTO paystack_pay VALUES(NULL,'$user[id]','$transamt','1','$gateway_response','$reference','$paystatus','$trans_id',now(),'webhook')");

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
  CURLOPT_POSTFIELDS => array('token' => 'G1tisaOnfDfmsz0XbHEg1p05PUDn2JJCYMHeVJpt03PpL1GU7M',
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

//Promo part payments within 5days
//lemon week is 15%
//outright is 12%

//outright part payments within 14days
//first payment 8%
//middle - 8% 
//last day 12%+4% of previous payments

//if its more than a month
//All payments gives 8%
//all done

//Daily part payment
//consultant is same as account officer
//6% for consultant and 6% for ao for first month
//second month only pay account officer 8%

//Give grace of 7days for promo
//pay 20% of what was paid to consultant outright
//15% for daily
//penalty
//zoom -  1%
//Monday - 1% (physical attendance and zoom 2%)
//Wednesday - 2%
//Friday ~ 2%
//my task shows zoom meeting
//client 50k
//2% reduction monthly 
?>