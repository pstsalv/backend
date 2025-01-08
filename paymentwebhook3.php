<?php
$smscode = mt_rand(1000,9999);
$token = mt_rand(10000,99999);
include_once("conn.php");
$monthy = date('F');
//Check to be sure that its a POST method
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
$gateway_response = $event->data->gateway_response;
$emailcaps = ucfirst($transemail);
$emailallcaps = strtoupper($transemail);
//check if property wuth same email is selected

$word = "sms";

if (strpos($transemail, $word) !== false) {
    $purpose =  "sms";
	$messagenfees = "Your sms charges payment of N$damt with Bliss Legacy Limited is successful. For enquiry: 07000325477. Regards.";
} else {
    $purpose =  "documentation";
	$messagenfees = "Your documentation fees payment of N$damt with Bliss Legacy Limited is successful. For enquiry: 07000325477. Regards.";
}

//import property details
$checkmyprop = mysqli_query($conn, "SELECT * FROM myproperty WHERE (payment_email='$transemail' OR payment_email='$emailcaps' OR payment_email='$emailallcaps') LIMIT 1");
	if(mysqli_num_rows($checkmyprop)>0){
		$mypropdet = mysqli_fetch_array($checkmyprop);
		
		$property = $mypropdet['propertyid'];		
		$yourplan = $mypropdet['payduration'];
		$proptype = $mypropdet['type'];
		$propuid = $mypropdet['propuid'];
		$bonusamt = $mypropdet['bonusamt'];
		$plot_size = $mypropdet['plot_size'];
		$propamount = $mypropdet['propamount'];
		
		
//property is selected, create payment history

//retrieve user details
	$checku = mysqli_query($conn, "SELECT * FROM users WHERE id='$mypropdet[userid]'");
	if(mysqli_num_rows($checku)>0){
		//user is found
			$user = mysqli_fetch_array($checku);
			$officer = $user['account_no'];
			$myemail = strtolower($user['email']);
			$me = $user['id'];
			$payref = $reference;
	
//check if customer is first time payer
		$checkiffirstpay = mysqli_query($conn, "SELECT id FROM payment WHERE userid='$me' AND status='approved'");
if(mysqli_num_rows($checkiffirstpay)>0){
		//user is a second time payer
		$messagen = "Your $mypropdet[type] payment of N$damt for #$mypropdet[propuid] with Bliss Legacy Limited is successful. For enquiry: 07000325477. Regards.";
}else{
	$messagen = "Welcome to your $yourplan payment plan. Your payment of N$damt for property with Bliss Legacy Limited is successful. For enquiry: 07000325477. Regards.";
	
	
	
	//give first payment to consultant	
//check if daily property
if($mypropdet['type']=="daily" || $mypropdet['type']=="weekly" || $mypropdet['type']=="monthly"){
	
		$giveamt = str_replace(',', '', $mypropdet['bonusamt']);
		//if payment is equal or more than amount required
		if($transamt>=$giveamt){
			if($user['agent']!=""){
//retrieve info of agent
				$checkagent = mysqli_query($conn, "SELECT id,fname,agent FROM users WHERE userid='$user[agent]'");
				if(mysqli_num_rows($checkagent)>0){
					$myagent = mysqli_fetch_array($checkagent);
					
					
					//create bonus history
					$postit = mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagent[id]','$giveamt','Referral bonus from $user[fname] first payment','approved',now(),'bonus','add','$monthy','Consultant','')");
					
					//topup agent Wallet
					$checkwalet = mysqli_query($conn, "SELECT id FROM mybonus WHERE owner='$myagent[id]'");
if(mysqli_num_rows($checkwalet)>0){
		$postit = mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$giveamt' WHERE owner='$myagent[id]'");
}else{
	$postit = mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagent[id]','$giveamt','bonus','approved',now(),'auto','')");
}

					//notify the agent
					mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagent[id]','payment','Wallet Credited','Your wallet has been credited with N$mypropdet[bonusamt] from client first payment','unread',now())");
					
					
			
			
			
					
				}
			}
			
		}
}elseif($mypropdet['type']=="promo"){
	//its promo
	
	
	
}elseif($mypropdet['type']=="outright"){
	//if client is a staff give 10%
	
	
}else{
	mysqli_error("Unknown property type");
}

}
		
//create payment history

	$upd = mysqli_query($conn, "INSERT INTO payment VALUES(NULL,'$me','$transamt','approved','$user[wallet_bal]','$user[userid]','virtual',now(),'Paystack','$user[my_center]','$property','$payref','$user[state]','$user[planid]','$user[agent]','$mypropdet[type]','$user[account_no]','$user[date]','$mypropdet[plot_size]','$user[branchid]','$user[second_agent]')");
	
if($upd){
	
	//check if customer has unpaid
	$checkifup = mysqli_query($conn, "SELECT id FROM payment WHERE userid='$me' AND amount='$transamt' AND status='uncleared' LIMIT 1");
	if(mysqli_num_rows($checkifup)>0){
		$dpay = mysqli_fetch_array($checkifup);
		mysqli_query($conn, "DELETE FROM payment WHERE id='$dpay[id]'");
		
	}
	//add amount to qucik amount view
	$sent = mysqli_query($conn, "UPDATE users SET wallet_bal=wallet_bal+$transamt WHERE id='$me'");
	//remove the amount from what is left
	mysqli_query($conn, "UPDATE myproperty SET amt_paid=amt_paid+'$transamt',amt_remain=amt_remain-'$transamt' WHERE userid='$me' AND propertyid='$property'");
	//create notification
	$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$me','payment','Payment successful','$messagen','unread',now())");

//notify the account officer
if($officer!=""){
				$checkoff = mysqli_query($conn, "SELECT id,fname FROM users WHERE userid='$officer'");
				$officerinfo = mysqli_fetch_array($checkoff);
				
				$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$officerinfo[id]','payment','$user[fname]\'s payment is sucessful','$user[fname] $user[lname] has just paid N$damt for #$mypropdet[propuid]','unread',now())");
			}
			
	
//keep backup incase			
$stackk = mysqli_query($conn, "INSERT INTO paystack_pay VALUES(NULL,'$me','$transamt','$property','$gateway_response','$payref','$paystatus','$trans_id',now(),'webhook')");

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
  'ref_id' => $token,
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
	 if ($respons['successful']){
		 mysqli_query($conn, "INSERT INTO smscharges VALUES(NULL,'$user[id]','otp','BlissPay','$messagen','$respons[comment]','$respons[units_calculated]','15',now(),'$respons[successful]','$respons[sms_pages]')");
	 }
	}
}

$responses = '{"status": true, "message": "Verification successful", "data": {"id": "'.$trans_id.'", "domain": "live", "status": "'.$paystatus.'", "reference": "'.$payref.'", "amount": "'.$transamt.'", "currency": "NGN"}}';
echo $responses;
}
	}
	}else{
		//check old way of this







			
		
		//check if payment already exist
		$checkp = mysqli_query($conn, "SELECT * FROM paystack_pay WHERE reference='$reference'");
		if(mysqli_num_rows($checkp)<1){
			
			
			
			
			
			
			//retrieve user details
	$checku = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position,branchid FROM users WHERE (email='$transemail' OR email='$emailcaps' OR email='$emailallcaps') LIMIT 1");
	if(mysqli_num_rows($checku)>0){
		//user is found
			$user = mysqli_fetch_array($checku);
			$officer = $user['account_no'];
			$myemail = strtolower($user['email']);
			$me = $user['id'];
			$payref = $reference;
	
	//check if user selected property
	//check to collect property id
	$checkpid = mysqli_query($conn, "SELECT * FROM myproperty WHERE userid='$me'");
		if(mysqli_num_rows($checkpid)>0){
			//user selected property
			
		$mypropdet = mysqli_fetch_array($checkpid);
		$property = $mypropdet['propertyid'];		
		$yourplan = $mypropdet['payduration'];
		$proptype = $mypropdet['type'];
		$propuid = $mypropdet['propuid'];
		$bonusamt = $mypropdet['bonusamt'];
		$plot_size = $mypropdet['plot_size'];
		
		}else{
			$property = 1;
			 $yourplan = "[No Plan Selected]";
			 $proptype = "";
			 $propuid = "[No Property Selected]";
			 $bonusamt = "0";
			 $plot_size = "Unknown Plot";
		}
		
//check if customer is first time payer
		$checkiffirstpay = mysqli_query($conn, "SELECT id FROM payment WHERE userid='$me' AND status='approved'");
if(mysqli_num_rows($checkiffirstpay)>0){
		//user is a second time payer
		$messagen = "Your $proptype payment of N$damt for #$propuid with Bliss Legacy Limited is successful. For enquiry: 07000325477. Regards.";
}else{
	$messagen = "Welcome to your $yourplan payment plan. Your payment of N$damt for property with Bliss Legacy Limited is successful. For enquiry: 07000325477. Regards.";
	
	
	
	//give first payment to consultant	
//check if daily property
if($proptype=="daily" || $proptype=="weekly" || $proptype=="monthly"){
	
		$giveamt = str_replace(',', '', $bonusamt);
		//if payment is equal or more than amount required
		if($transamt>=$giveamt){
			if($user['agent']!=""){
				$checkagent = mysqli_query($conn, "SELECT id,fname,agent FROM users WHERE userid='$user[agent]'");
				if(mysqli_num_rows($checkagent)>0){
					$myagent = mysqli_fetch_array($checkagent);
					
					
					//create bonus history
					$postit = mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagent[id]','$giveamt','Referral bonus from $user[fname] first payment','approved',now(),'bonus','add','$monthy','Consultant','')");
					
					//topup agent Wallet
					$checkwalet = mysqli_query($conn, "SELECT id FROM mybonus WHERE owner='$myagent[id]'");
if(mysqli_num_rows($checkwalet)>0){
		$postit = mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$giveamt' WHERE owner='$myagent[id]'");
}else{
	$postit = mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagent[id]','$giveamt','bonus','approved',now(),'auto','')");
}

					//notify the agent
					mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagent[id]','payment','Wallet Credited','Your wallet has been credited with N$bonusamt from client first payment','unread',now())");
			
			
			
					
					
					
						
					//check agent of my agent
			if($myagent['agent']!=""){
				
				$checkagent2 = mysqli_query($conn, "SELECT id,fname,state,agent FROM users WHERE userid='$myagent[agent]'");
				if(mysqli_num_rows($checkagent2)>0){
					$myagent2 = mysqli_fetch_array($checkagent2);
					
	
		if($mypropdet['state']=="Lagos"){
			$dbonusdailsec = "3000";
			$dbonus2dailysec = "3,000";
		}else{
			$dbonusdailsec = "2000";
			$dbonus2dailysec = "2,000";
		}
	
	
					//create bonus history
					mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$myagent2[id]','$dbonusdailsec','Referral bonus for $user[fname] - Lagos','approved',now(),'bonus','add','$monthy','Second Generation','')");
					
					//topup agent Wallet
					$checkwalet = mysqli_query($conn, "SELECT id FROM mybonus WHERE owner='$myagent2[id]'");
if(mysqli_num_rows($checkwalet)>0){
	mysqli_query($conn, "UPDATE mybonus SET amount=amount+'$dbonusdailsec' WHERE owner='$myagent2[id]'");
}else{
	mysqli_query($conn, "INSERT INTO mybonus VALUES(NULL,'$myagent2[id]','$dbonusdailsec','bonus','approved',now(),'auto','')");
}

			//notify the agent
			mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$myagent2[id]','payment','Wallet Credited','Your wallet has been credited with N$dbonus2dailysec for client outright property','unread',now())");
			
			//notify the system that client has been paid bonus
			mysqli_query($conn, "UPDATE payment SET notes='Bonus Awarded' WHERE status='approved' AND admin_approved='virtual' AND userid='$me' AND paidfor='$property'");
			
					
					
				}
				
			}
			
			
			
				}
			}
			
		}
}elseif($mypropdet['type']=="promo"){
	//its promo
	
	
	

}elseif($mypropdet['type']=="outright"){
	
	//if client is a staff give 10%
	
	
	
}

}







			
			//credit the user
			

if($myemail==$transemail){
			
			
			
			$upd = mysqli_query($conn, "INSERT INTO payment VALUES(NULL,'$me','$transamt','approved','$user[wallet_bal]','$user[userid]','virtual',now(),'Paystack','$gateway_response','$property','$payref','$user[state]','$user[planid]','$user[agent]','$proptype','$user[account_no]','$user[date]','$plot_size','$user[branchid]','$user[second_agent]')");
	
if($upd){
	
	//check if customer has unpaid
	$checkifup = mysqli_query($conn, "SELECT id FROM payment WHERE userid='$me' AND amount='$transamt' AND status='uncleared' LIMIT 1");
	if(mysqli_num_rows($checkifup)>0){
		$dpay = mysqli_fetch_array($checkifup);
		mysqli_query($conn, "DELETE FROM payment WHERE id='$dpay[id]'");
		
	}
	//add amount to qucik amount view
	$sent = mysqli_query($conn, "UPDATE users SET wallet_bal=wallet_bal+$transamt WHERE id='$me'");
	//remove the amount from what is left
	mysqli_query($conn, "UPDATE myproperty SET amt_paid=amt_paid+'$transamt',amt_remain=amt_remain-'$transamt' WHERE userid='$me' AND propertyid='$property'");
	//create notification
	$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$me','payment','Payment successful','$messagen','unread',now())");

//notify the account officer
if($officer!=""){
				$checkoff = mysqli_query($conn, "SELECT id,fname FROM users WHERE userid='$officer'");
				$officerinfo = mysqli_fetch_array($checkoff);
				
				$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$officerinfo[id]','payment','$user[fname]\'s payment is sucessful','$user[fname] $user[lname] has just paid N$damt for #$propuid','unread',now())");
			}
			
	
//keep backup incase			
$stackk = mysqli_query($conn, "INSERT INTO paystack_pay VALUES(NULL,'$me','$transamt','$property','$gateway_response','$payref','$paystatus','$trans_id',now(),'webhook')");

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
  'ref_id' => $token,
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
	 if ($respons['successful'] !="") {
		 mysqli_query($conn, "INSERT INTO smscharges VALUES(NULL,'$user[id]','otp','BlissPay','$messagen','$respons[comment]','$respons[units_calculated]','15',now(),'$respons[successful]','$respons[sms_pages]')");
	 }
	}
}

$responses = '{"status": true, "message": "Verification successful", "data": {"id": "'.$trans_id.'", "domain": "live", "status": "'.$paystatus.'", "reference": "'.$payref.'", "amount": "'.$transamt.'", "currency": "NGN"}}';
echo $responses;
}
			
			
}
	}else{
		//user not found
		
		//check bank for records
		
		$checkbankk = mysqli_query($conn, "SELECT id,accno,userid,acc_email,pay_type FROM banks WHERE (acc_email='$transemail' OR acc_email='$emailcaps' OR acc_email='$emailallcaps')");
		if(mysqli_num_rows($checkbankk)>0){
			$bankdetails = mysqli_fetch_array($checkbankk);
			
			//add payment records
			mysqli_query($conn, "INSERT INTO payment VALUES(NULL,'$bankdetails[userid]','$transamt','approved','','$bankdetails[userid]','virtual',now(),'Paystack','$gateway_response','$purpose','$reference','','','','$purpose','','','$purpose','','')");
			
			mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$bankdetails[userid]','fees','Payment successful','$messagenfees','unread',now())");
			
			$checkduse = mysqli_query($conn, "SELECT * FROM users WHERE id='$bankdetails[userid]'");
			if(mysqli_num_rows($checkduse)>0){
			$userdetails = mysqli_fetch_array($checkduse);
			
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
  'to' => $userdetails['phone'],
  'message' => $messagenfees,
  'type' => '0',
  'routing' => '4',
  'ref_id' => $token,
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
	 if ($respons['successful'] !="") {
		 mysqli_query($conn, "INSERT INTO smscharges VALUES(NULL,'$bankdetails[userid]','otp','BlissPay','$messagenfees','$respons[comment]','$respons[units_calculated]','15',now(),'$respons[successful]','$respons[sms_pages]')");
	 }
	 
}

$responses = '{"status": true, "message": "Verification successful", "data": {"id": "'.$trans_id.'", "domain": "live", "status": "'.$paystatus.'", "reference": "'.$reference.'", "amount": "'.$transamt.'", "currency": "NGN"}}';
echo $responses;
			}

			
		}
		
	}
		

		
		
		}else{
			$responses = '{"status":"alreadydone", "message":"This payment was already added to customer payment history"}';
	echo $responses;
		}
		
		
		
	}
	
    //payment received end
}
exit();
mysqli_close($conn);
?>