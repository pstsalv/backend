<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods PUT, GET, POST");
header("Access-Control-Allow-Credentials: true");
header('content-type: application/json; charset=utf-8');
header('Cache-Control: no-cache');

include_once("conn.php");
$smscode = mt_rand(1000,9999);
$token = mt_rand(9999,99999);
if($_POST['phone']=="" || $_POST['password']==""){
	$responses = json_encode('{"fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":"empty", "message":"Empty login details"}');
	echo $responses;
	exit();
}else{
$myusername = mysqli_real_escape_string($conn, $_POST['phone']);
$mypassword = mysqli_real_escape_string($conn, $_POST['password']);
$acctype = mysqli_real_escape_string($conn, $_POST['acctype']);

$result = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE phone='$myusername' AND password='$mypassword' AND account_type='customer' AND status!='deleted' LIMIT 1") or die(mysqli_error($conn));
if($result){
if(mysqli_num_rows($result)<1){
	$responses = json_encode('{"fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":"invalid", "message":"Invalid login details"}');
	echo $responses;
	exit();
}else{
	$in = mysqli_fetch_array($result);
if($in['status'] =="suspended" || $in['status'] =="banned"){
	$responses = json_encode('{"appstatus":"banned", "fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":"reset", "message":"Your account has been suspended"}');
	echo $responses;
	
	exit();
}elseif($in['status'] =="reset" || $in['status'] ==""){
	

		$heckprall = mysqli_query($conn, "SELECT id,owner_id,state,zone,zonehead,phone,group_link,date,branchid,marketers,clients,cleared,uncleared FROM allzones WHERE id='$in[planid]'");
		if(mysqli_num_rows($heckprall)>0){
	$zonesd = mysqli_fetch_array($heckprall);
		$zonestate = $zonesd['state'];
		$zonename = $zonesd['zone'];
		$zonehead = $zonesd['zonehead'];
		$zphone = $zonesd['phone'];
		$grouplink = $zonesd['group_link'];
	}else{
		$zonestate = "";
		$zonename = "";
		$zonehead = "";
		$zphone = "";
		$grouplink = "";
	}
	
	
	
$responses = json_encode('{"user_id":"'.$in[0].'", "fname":"'.ucwords($in['fname']).'", "lname":"'.ucfirst($in['lname']).'", "email":"'.$in['email'].'", "phone":"'.$in['phone'].'", "foto":"'.rawurlencode($in['pix']).'", "gender":"'.$in['gender'].'", "status":"reset", "region":"'.$in['region'].'", "state":"'.$in['state'].'", "acc_type":"'.$in['account_type'].'", "walletbal":"'.$in['wallet_bal'].'", "uncleared":"'.$in['uncleared'].'", "user_token":"'.$in['userid'].'", "myaccno":"'.$in['account_no'].'", "address":"'.$in['address'].'", "dob":"'.$in['dob'].'", "zonestate":"'.$zonestate.'", "region":"'.$zonename.'", "zonehead":"'.$zonehead.'", "zcontact":"'.$zphone.'", "group":"'.$grouplink.'"}');

	echo $responses;
	
	exit();
}elseif($in['status'] =="unverified"){
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


$to = $in['email'];
	$headers = "From: info@blisslegacy.com" . "\r\n" .
	$subject = "Verification Code";
	$message = "Use this digits $smscode to verify your account on Bliss pAy - Customer App";
	mail($to,$subject,$message,$headers);
	
mysqli_query($conn,"UPDATE users SET otpcode='$smscode' WHERE id='$in[0]'");
	
	$responses = json_encode('{"status":"unverified", "url":"/otp/'.$in['phone'].'/'.$in['id'].'", "message":"Your account is unverified"}');
	echo $responses;
	
	exit();
}elseif($in['status'] =='active' || $in['status']=='incomplete' || $in['status']=='verified'){

if(isset($_POST['mytoken']) && $_POST['mytoken']!=""){
$mytoken = $_POST['mytoken'];

$check = mysqli_query($conn, "SELECT id,owner,token,date,type,owner_token,name FROM tokens WHERE owner='$in[id]'");
if($check && mysqli_num_rows($check)<1){
mysqli_query($conn, "INSERT INTO tokens VALUES(NULL,'$in[id]','$mytoken',now(),'Fresh login token','$in[id]','$in[fname]')") or die(mysqli_error($conn));
}else{
	mysqli_query($conn, "UPDATE tokens SET token='$mytoken',type='Refreshd login token',date=now(),name='$in[fname]' WHERE owner='$in[id]'");
}


}








//for outstanding payments
$checkamtppo = mysqli_query($conn,"SELECT SUM(amt_remain) AS outstanding FROM  myproperty WHERE status='ongoing' AND userid='$in[id]'");
$rowppo = mysqli_fetch_assoc($checkamtppo);
$sumppo = $rowppo['outstanding'];
$outstand = "$sumppo";
if($outstand!==""){
$outstandy = $outstand;
}else{
$outstandy = "0.00";
};

//for bonus
$checkbn = mysqli_query($conn,"SELECT SUM(amt_remain) AS bonusamt FROM users_disbursement WHERE status='paid' AND userid='$in[userid]'");
$rowbn = mysqli_fetch_assoc($checkbn);
$sumpp = $rowbn['bonusamt'];
$totalbn= "$sumpp";
if($totalbn!=""){
$mybalbn = $totalbn;
}else{
$mybalbn = "0.00";
};

//for created new accounts
$checkusers = mysqli_query($conn, "SELECT id,agentid,customerid,amount,payment_method,pay_status,date,seen_status FROM referral WHERE seen_status='unread' AND agentid='$in[userid]'");
if($checkusers && mysqli_num_rows($checkusers)>0){
	$newreferal = "found";
}else{
	$newreferal = "notfound";
}
//for new cash requests
$checkcr = mysqli_query($conn, "SELECT id,property,customerid,agentid,custname,amount,notes,paymethod,status,date,read_status FROM payment_request WHERE read_status='unread' AND agentid='$in[userid]'");
if($checkcr && mysqli_num_rows($checkcr)>0){
	$myreq = "found";
}else{
	$myreq = "notfound";
}

//for activations
$checksub = mysqli_query($conn, "SELECT toend,type FROM accsubid WHERE type='sfa'");
if($checksub && mysqli_num_rows($checksub)>0){
	$dall = mysqli_fetch_array($checksub);
	$sfa = $dall['toend'];
}else{
	$sfa = 0;
}

//for noty
$check =mysqli_query($conn, "SELECT id,owner,type,title,message,status,date FROM notification WHERE owner='$in[id]' AND status!='read'");
if(mysqli_num_rows($check)>0){
$total = mysqli_num_rows($check);
$resp = '<span>'.$total.'</span>';

}else{
$resp ='';
}


$agentid = $in['userid'];
if($in['account_type']!="customer"){
//for agent
//calculate ll money brought by downlines
 $checkamtpp = mysqli_query($conn,"SELECT SUM(amount) AS amttpaid FROM payment WHERE agentid='$agentid' AND status='approved' AND MONTH(owner_date)=MONTH(CURDATE())");
$rowpp = mysqli_fetch_assoc($checkamtpp);
$sumppm = $rowpp['amttpaid'];
$totalpayd = "$sumppm";
if($totalpayd!=""){
$mybalp = $totalpayd;
}else{
	$mybalp = 0;
}
mysqli_query($conn,"UPDATE users SET wallet_bal='$mybalp' WHERE userid='$agentid' AND account_type!='customer'");
}else{
	//for customer
//calculate ll money paid by customer
  
  $checkamtpp2 = mysqli_query($conn,"SELECT SUM(amount) AS amttpaid FROM  payment WHERE status='approved' AND userid='$in[id]'");
$rowpp2 = mysqli_fetch_assoc($checkamtpp2);
$sumpp2 = $rowpp2['amttpaid'];
$totalpayd2 = "$sumpp2";
if($totalpayd2!==""){
$mybalp = $totalpayd2;
}else{
	$mybalp = 0;
}
mysqli_query($conn,"UPDATE users SET wallet_bal='$mybalp' WHERE userid='$agentid' AND account_type='customer'");
}

//for uncleared/uncleared
if($in['account_type']!="customer"){
//for agent
//calculate ll payment collected by agent uncleared
  $checkamtppu = mysqli_query($conn,"SELECT SUM(uncleared) AS amttpaidu FROM users WHERE agent='$agentid'");
$rowppu = mysqli_fetch_assoc($checkamtppu);
$sumppmu = $rowppu['amttpaidu'];
$totalpaydu = "$sumppmu";
if($totalpaydu!=""){
$mybalpu = $totalpaydu;
}else{
	$mybalpu = 0;
}
mysqli_query($conn,"UPDATE users SET uncleared='$mybalpu' WHERE userid='$agentid' AND account_type!='customer'");
}else{
	//for customer
//calculate ll money paid by customer uncleared
  
  $checkamtpp2c = mysqli_query($conn,"SELECT SUM(amount) AS amttpaidc FROM  payment WHERE status='uncleared' AND userid='$in[id]'");
$rowpp2c = mysqli_fetch_assoc($checkamtpp2c);
$sumpp2c = $rowpp2c['amttpaidc'];
$totalpayd2c = "$sumpp2c";
if($totalpayd2c!==""){
$mybalpc = $totalpayd2c;
}else{
	$mybalpc = 0;
}
mysqli_query($conn,"UPDATE users SET uncleared='$mybalpc' WHERE userid='$agentid' AND account_type='customer'");
}




if($in['account_type']=="coodinator"){
			$checkbranch = mysqli_query($conn, "SELECT id,branch_rand,coordinator,date,branchname,state FROM branches WHERE id='$in[planid]'");
			$branch = mysqli_fetch_array($checkbranch);
		$zonestate = $branch['state'];
		$zonename = $branch['branchname'];
		$zonehead = $users['fname'].' '.$users['lname'];
		$zphone = $users['phone'];
		$grouplink = "Not applicable";
			
		}else{
	if($in['planid']!=""){
		
		$heckprall = mysqli_query($conn, "SELECT id,owner_id,state,zone,zonehead,phone,group_link,date,branchid,marketers,clients,cleared,uncleared FROM allzones WHERE id='$in[planid]'");
		if(mysqli_num_rows($heckprall)>0){
	$zonesd = mysqli_fetch_array($heckprall);
		$zonestate = $zonesd['state'];
		$zonename = $zonesd['zone'];
		$zonehead = $zonesd['zonehead'];
		$zphone = $zonesd['phone'];
		$grouplink = $zonesd['group_link'];
		}else{
			$zonestate = "";
		$zonename = "Join Zone";
		$zonehead = "";
		$zphone = "";
		$grouplink = "";
		}
	}else{
		$zonestate = "";
		$zonename = "Join Zone";
		$zonehead = "";
		$zphone = "";
		$grouplink = "";
	}
		}
		
		
$heckacc = mysqli_query($conn, "SELECT id,accname,accno,bankname,userid,date,customer_code,customer_id FROM banks WHERE userid='$in[0]' AND accno!=''");
	if(mysqli_num_rows($heckacc)>0){
		$mybank = mysqli_fetch_array($heckacc);
		$bankname = $mybank['bankname'];
		$accno = $mybank['accno'];
		$accname = $mybank['accname'];
	}else{
		$bankname = '';
		$accno = '';
		$accname = '';
	}

$responses = json_encode('{"user_id":"'.$in[0].'", 
"fname":"'.ucwords($in['fname']).'",
 "lname":"'.ucfirst($in['lname']).'", 
 "email":"'.$in['email'].'", 
 "phone":"'.$in['phone'].'", 
 "foto":"'.rawurlencode($in['pix']).'", 
 "gender":"'.$in['gender'].'", 
 "status":"'.$in['status'].'", 
 "region":"'.$in['region'].'", 
 "state":"'.$in['state'].'", 
 "acc_type":"'.$in['account_type'].'", 
 "walletbal":"'.$in['wallet_bal'].'", 
 "uncleared":"'.$in['uncleared'].'", 
 "user_token":"'.$in['userid'].'", 
 "myaccno":"'.$in['account_no'].'", 
 "address":"'.$in['address'].'", 
 "dob":"'.$in['dob'].'", 
 "bonus":"'.$mybalbn.'",
 "referral":"'.$newreferal.'",
 "zonestate":"'.$zonestate.'",
 "myzoneid":"'.$zonename.'",
 "regiona":"'.$zonename.'",
 "zonehead":"'.$zonehead.'",
 "zcontact":"'.$zphone.'",
 "group":"'.$grouplink.'",
 "requested":"'.$myreq.'",
 "bankname":"'.$bankname.'",
 "accno":"'.$accno.'",
 "accname":"'.$accname.'"
 }');

echo $responses;
	
exit();

}else{
	$responses = json_encode('{"appstatus":"invalid", "fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":""unknown, "message":"Invalid User occured"}');
	echo $responses;
}
}
}else{
	$responses = json_encode('{"appstatus":"unknown", "fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":""unknown, "message":"Unknown error occured"}');
	echo $responses;
}
}
mysqli_close($conn);
?>