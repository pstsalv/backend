<?php
header("access-control-allow-origin: *");
header('content-type: application/json; charset=utf-8');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");
$smscode = mt_rand(1000,9999);
$token = mt_rand(10000,99999);
$me = mysqli_real_escape_string($conn,$_POST['me']);
$payref = mysqli_real_escape_string($conn,$_POST['payref']);
if(isset($_POST['property'])){
$property = mysqli_real_escape_string($conn,$_POST['property']);
}else{
$property = 1;
}

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/$payref",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Authorization: Bearer $payapi",
      "Cache-Control: no-cache",
    ),
  ));
  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);
  if ($err) {
   $responses = '{"status":false, "message":"Something went terribly wrong, kindly retry"}';
	echo $responses;
  } else {
   // echo $response;
	
	 $respons = json_decode($response, true);
	
    if ($respons['status'] === true) {
    if ($respons['data']['status'] == "success") {
		$paystatus = $respons['data']['status'];
		$transamt = $respons['data']['amount']/100;
		$trans_id = $respons['data']['id'];
		$transemail = $respons['data']['customer']['email'];
$paid_at = $respons['data']['paid_at'];
		$damt = number_format($transamt,2);
		$gateway_response = $respons['data']['gateway_response'];
		//check to collect property id
		$checkpid = mysqli_query($conn, "SELECT * FROM myproperty WHERE propertyid='$property' AND userid='$me'");
		$dprop = mysqli_fetch_array($checkpid);
		
		$checkplan = mysqli_query($conn, "SELECT id,plan_name,amount,plancode,status,type,duration,amt_to_pay,location,plot_size,payplancode FROM payment_plan WHERE id='$dprop[planid]'");
		if(mysqli_num_rows($checkplan)>0){
		    $myplan = mysqli_fetch_array($checkplan);
		    $yourplan = $myplan['plan_name'];
		}else{
		    $yourplan = $dprop['planid'];
		}
		//check if payment already exist
		$checkp = mysqli_query($conn, "SELECT * FROM paystack_pay WHERE reference='$payref'");
		if(mysqli_num_rows($checkp)<1){
			
			//credit the user
			$checku = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position,branchid FROM users WHERE id='$me'");
			$user = mysqli_fetch_array($checku);
	
			$upd = mysqli_query($conn, "INSERT INTO payment VALUES(NULL,'$me','$transamt','approved','$user[wallet_bal]','$user[userid]','virtual','$paid_at','Paystack','$gateway_response','$property','$payref','$user[state]','$user[planid]','$user[agent]','','$user[account_no]','$user[date]','$dprop[plot_size]','$user[branchid]','$user[second_agent]','$dprop[logistics]','','','')");
if($upd){






// Check if users were registered within the last 31 days
$checkclientdate = mysqli_query($conn, "SELECT id FROM users WHERE DATE(date) >= DATE_SUB(CURDATE(), INTERVAL 31 DAY) AND id='" . $user['id'] . "' AND YEAR(date) = YEAR(CURDATE())");
if(mysqli_num_rows($checkclientdate)>0){


//give officer 6%
$giveamt = str_replace(',', '', $dprop['bonusamt']);
$totalinmonth = $giveamt*31;
if($transamt>=$totalinmonth){
$consultbonus = $totalinmonth*0.06;
$officerbonus = $totalinmonth*0.06;
$officerbonussec = $totalinmonth*0.08;
$seconbonus = $totalinmonth*0.03;
$seconbonusao = $totalinmonth*0.02;

}else{
$consultbonus = $transamt*0.06;
$officerbonus = $transamt*0.06;
$officerbonussec = $transamt*0.08;
$seconbonus = $transamt*0.03;
$seconbonusao = $transamt*0.02;
}

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
$aobonuses = $officerbonus;
$aobonusesfm = number_format($aobonuses);


$checkoffao = mysqli_query($conn, "SELECT * FROM users WHERE userid='$user[account_no]' AND leftover_batch1!='stopbonus'");
if(mysqli_num_rows($checkoffao)>0){
$officerinfoao = mysqli_fetch_array($checkoffao);

//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$officerinfoao[id]','$aobonuses','Referral bonus from $fname payments','approved',now(),'bonus','add','$monthy','$officerinfoao[account_type]','$unknown')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$officerinfoao[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$aobonuses',date=now() WHERE owner='$officerinfoao[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$officerinfoao[id]','$aobonuses','bonus','approved',now(),'auto','')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$officerinfoao[id]','payment','Wallet Credited','Your wallet has been credited with N$aobonusesfm $uknown bonus','unread',now())");


$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$officerinfoao[id]','payment','$fname\'s payment is sucessful','$fname $lname has just paid N$damt for unknown property', 'unread',now())");


//if ao has second generation 
if($officerinfoao['agent'] != "" && $officerinfoao['agent'] != "elevated"){
//check if ao second gen user details is available
$checkagentsg = mysqli_query($conn, "SELECT * FROM users WHERE userid='$officerinfoao[agent]' AND leftover_batch1!='stopbonus'");
	if(mysqli_num_rows($checkagentsg)>0){
		$myagentsec = mysqli_fetch_array($checkagentsg);

$seconbonusaod = $aobonuses*0.10;


//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagentsec[id]','$seconbonusaod','Second Generation referral bonus','approved',now(),'bonus','add','$monthy','$myagentsec[account_type]','$unknown')");

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
$checkclientdate = mysqli_query($conn, "SELECT id FROM users WHERE DATE(date) > DATE_SUB(CURDATE(), INTERVAL 31 DAY) AND id='$user[id]'");

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
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$officerinfoao[id]','$aobonuses','Referral bonus from $fname payments','approved',now(),'bonus','add','$monthy','$officerinfoao[account_type]','$unknown')");

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
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagentsec[id]','$seconbonusao','Second Generation referral bonus','approved',now(),'bonus','add','$monthy','$myagentsec[account_type]','$unknown')");

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

//last year begins here
//check if user joined since last year
$checkclientdate = mysqli_query($conn, "SELECT id FROM users WHERE YEAR(date) < YEAR(CURDATE()) AND id='$user[id]'");

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
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$officerinfoao[id]','$aobonuses','Referral bonus from $fname payments','approved',now(),'bonus','add','$monthy','$officerinfoao[account_type]','$uknown')");

//topup agent Wallet
	$checkwalet = mysqli_query($conn, "SELECT * FROM mybonus WHERE owner='$officerinfoao[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$aobonuses',date=now() WHERE owner='$officerinfoao[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$officerinfoao[id]','$aobonuses','bonus','approved',now(),'auto','')");
}

//notify the agent
	mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$officerinfoao[id]','payment','Wallet Credited','Your wallet has been credited with N$aobonusesfm account officer $unknown property bonus','unread',now())");


$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$officerinfoao[id]','payment','$fname\'s payment is sucessful','$fname $lname has just paid N$damt for uknown property', 'unread',now())");


//if ao has second generation 
if($officerinfoao['agent'] != "" && $officerinfoao['agent'] != "elevated"){
//check if ao second gen user details is available
$checkagentsg = mysqli_query($conn, "SELECT * FROM users WHERE userid='$officerinfoao[agent]' AND leftover_batch1!='stopbonus'");
	if(mysqli_num_rows($checkagentsg)>0){
		$myagentsec = mysqli_fetch_array($checkagentsg);

//create bonus history
mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagentsec[id]','$seconbonusao','Second Generation referral bonus','approved',now(),'bonus','add','$monthy','$myagentsec[account_type]','uknown')");

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
//last year ends




	
	$sent = mysqli_query($conn, "UPDATE users SET wallet_bal=wallet_bal+$transamt WHERE id='$me'");
	mysqli_query($conn, "UPDATE myproperty SET amt_paid=amt_paid+$transamt,amt_remain=amt_remain-$transamt WHERE userid='$me' AND propertyid='$property'");
	
$messagen = "You are now subscribed to $yourplan payment plan. Your payment of N $damt for #$dprop[propuid] with Bliss Legacy Limited is successful. For enquiry call 07000325477. Thanks. Payment Reference #$payref";

$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$me','notification','Payment sucessful','$messagen','unread',now())");

$stackk = mysqli_query($conn, "INSERT INTO paystack_pay VALUES(NULL,'$me','$transamt','$property','$gateway_response','$payref','$paystatus','$trans_id',now(),'app')");


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
  CURLOPT_POSTFIELDS => array('token' => 'G1tisaOnfDfmsz0XbHEg1p05PUDn2JJCYMHeVJpt03PpL1GU7M',
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
$responses = '{"status": true, "message": "Verification successful", "data": {"id": "'.$trans_id.'", "domain": "live", "status": "'.$paystatus.'", "reference": "'.$payref.'", "amount": "'.$transamt.'", "currency": "NGN"}}';
echo $responses;
}else{
die(mysqli_error($conn));
	
}


}

		}else{
			$responses = '{"status":"alreadydone", "message":"This payment was already added to customer payment history"}';
	echo $responses;
		}
	//echo json_encode($respons);
	}else{
		echo json_encode($respons);
		
	}
  }else{
		echo json_encode($respons);
		
	}
  }
?>