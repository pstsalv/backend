<?php
header("access-control-allow-origin: *");
header('Content-Type: text/event-stream');
//header('content-type: application/json; charset=utf-8');
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');
include_once("conn.php");

$me = mysqli_real_escape_string($conn, $_GET['me']);

if(isset($_GET['lat'])){
$lat = mysqli_real_escape_string($conn, $_GET['lat']);
$lng = mysqli_real_escape_string($conn, $_GET['lng']);

mysqli_query($conn, "UPDATE users SET lat='$lat',lng='$lng' WHERE id='$me'");
}

if(isset($_GET['userid'])){
$userid = mysqli_real_escape_string($conn, $_GET['userid']);
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$me' AND userid='$userid' AND (status!='banned' OR status!='suspended') LIMIT 1");
}else{
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$me' AND status!='banned' AND status!='suspended' AND status!='deleted' LIMIT 1");
}

if(mysqli_num_rows($result)>0){
$in = mysqli_fetch_array($result);


//for outstanding payments
$checkamtppo = mysqli_query($conn,"SELECT SUM(amt_remain) AS outstanding FROM  myproperty WHERE status='ongoing' AND userid='$in[id]'");
$rowppo = mysqli_fetch_assoc($checkamtppo);
$sumppo = $rowppo['outstanding'];
$outstand = "$sumppo";
if($outstand!==""){
$outstandyg = $outstand;
}else{
$outstandyg = "0.00";
};

if($outstandg>0){
$outstandy = $outstandg;
}else{
$outstandy = "0.00";
};

//for bonus
$checkbn = mysqli_query($conn,"SELECT SUM(amount) AS bonusamt FROM mybonus WHERE (status='approved' OR status='collected' OR status='reversed') AND owner='$in[id]'");
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
$check =mysqli_query($conn, "SELECT id,owner,type,title,message,status,date FROM notification WHERE owner='$me' AND status!='read'");
if(mysqli_num_rows($check)>0){
$total = mysqli_num_rows($check);
$resp = '<span>'.$total.'</span>';

}else{
$resp ='';
}


$agentid = $in['userid'];
if($in['account_type']!="customer"){
//for agent
//calculate ll money brought by downlines this month
  $checkamtpp = mysqli_query($conn,"SELECT SUM(amount) AS amttpaid FROM payment WHERE agentid='$agentid' AND status='approved' AND MONTH(owner_date)=MONTH(CURDATE()) AND YEAR(date_paid) = YEAR(CURDATE())");
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


//calculate all daily,weekly,monthly money paid by customer
  
  $checkamtpp2d = mysqli_query($conn,"SELECT SUM(amount) AS amttpaidd FROM payment WHERE status='approved' AND userid='$in[id]' AND propert_type!='outright'");
$rowpp2d = mysqli_fetch_assoc($checkamtpp2d);
$sumpp2d = $rowpp2d['amttpaidd'];
$totalpayd2d = "$sumpp2d";
if($totalpayd2d!==""){
$mybalpd = $totalpayd2d;
}else{
	$mybalpd = 0;
}
mysqli_query($conn,"UPDATE users SET daily_bonus='$mybalpd' WHERE id='$in[id]' AND account_type='customer'");


//calculate all daily,weekly,monthly money paid by customer (full plot)
  
  $checkamtpp2df = mysqli_query($conn,"SELECT SUM(amount) AS amttpaidfdf FROM payment WHERE status='approved' AND userid='$in[id]' AND propert_type!='outright' AND propert_type!='promo' AND plot_size='full plot'");

$rowpp2df = mysqli_fetch_assoc($checkamtpp2df);
$sumpp2df = $rowpp2df['amttpaidfdf'];
$totalpayd2df = "$sumpp2df";
if($totalpayd2df!==""){
$mybalpdf = $totalpayd2df;
}else{
	$mybalpdf = 0;
}
mysqli_query($conn,"UPDATE users SET full_plot='$mybalpdf' WHERE id='$in[id]' AND account_type='customer'");

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


	if($in['branchid']!="" && $in['planid']=="" && $in['my_center']==""){
		$heckprall = mysqli_query($conn, "SELECT * FROM branches WHERE branch_rand='$in[branchid]'");
if(mysqli_num_rows($heckprall)>0){
	$zonesd = mysqli_fetch_array($heckprall);
		$zonename = $zonesd['branchname'];
}else{
$zonename = "Join Another Z0ne";
}
	}elseif($in['branchid']!="" && $in['planid']!="" && $in['my_center']==""){
		$heckprall = mysqli_query($conn, "SELECT * FROM allzones WHERE id='$in[planid]'");
if(mysqli_num_rows($heckprall)>0){
	$zonesd = mysqli_fetch_array($heckprall);
		$zonename = $zonesd['zone'];
}else{
$zonename = "Join Zone";
}
		}elseif($in['branchid']!="" && $in['planid']!="" && $in['my_center']!=""){
		$heckprall = mysqli_query($conn, "SELECT * FROM centers WHERE id='$in[my_center]'");
	$zonesd = mysqli_fetch_array($heckprall);
		$zonename = $zonesd['center_name'];
		}else{
			$zonename = "Join Zone";
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
	
	if($in['account_type']=="attendance"){
		$leader = "yes";
	}else{
		$leader = "no";
	}
	
	
	$checkv = mysqli_query($conn, "SELECT id,versionno,status,date,available FROM versioned WHERE available='yes'");
	if(mysqli_num_rows($checkv)>0){
	$appupdate = "yes";
	}else{
		$appupdate = "no";
	}
		
	
	$checkwll = mysqli_query($conn, "SELECT id FROM mybonus WHERE owner='$in[id]' AND status='locked'");
	if($checkwll){
	if(mysqli_num_rows($checkwll)>0){
	$walllock = "yes";
	}else{
		$walllock = "no";
	}
}else{
		$walllock = "no";
	}
		
	
	$checkvp = mysqli_query($conn, "SELECT id,messages,status,date FROM public_notice WHERE status='active'");
	if(mysqli_num_rows($checkvp)>0){
		$messagesu = mysqli_fetch_array($checkvp);
	$publicnote = "yes";
	$messager = $messagesu['messages'];
	}else{
		$publicnote = "no";
	$messager = "";
	}
		
	if($in['account_type']=="Consultant" || $in['account_type']=="unknown" || $in['account_type']=="agent"){
		$acctype = 'unknown';
	}else{
		$acctype = $in['account_type'];
	}
	
	if($in['account_type']=="Admin Staff"){
		$mypost = $in['position'];
	}else{
		$mypost = $in['account_type'];
	}
	
	
	if($in['account_type']=="customer"){
$totalrevall = $in['wallet_bal']; 
}else{
	if($in['position']=="Account Officer"){
		
		$calcaos = mysqli_query($conn,"SELECT SUM(amount) AS revenueao FROM  payment WHERE propert_type!='outright' AND (admin_approved='virtual' OR admin_approved='auto') AND (collectorid='$in[userid]' OR agentid='$in[userid]') AND propert_type!='promo' AND MONTH(date_paid) = MONTH(CURDATE()) AND YEAR(date_paid) = YEAR(CURDATE())");
$rowao = mysqli_fetch_assoc($calcaos);
$sumao = $rowao['revenueao'];
$outstandao = "$sumao";
if($outstandao!==""){
$revao = $outstandao;
}else{
$revao = "0.00";
};

		$totalrevall = $revao;
	}else{

$calcaos = mysqli_query($conn,"SELECT SUM(amount) AS revenueao FROM  payment WHERE propert_type!='outright' AND (admin_approved='virtual' OR admin_approved='auto') AND agentid='$in[userid]' AND propert_type!='promo' AND MONTH(date_paid) = MONTH(CURDATE()) AND YEAR(date_paid) = YEAR(CURDATE())");
$rowao = mysqli_fetch_assoc($calcaos);
$sumao = $rowao['revenueao'];
$outstandao = "$sumao";
if($outstandao!==""){
$revao = $outstandao;
}else{
$revao = "0.00";
};


		$totalrevall = $revao;
	}
}	

$checksett = mysqli_query($conn,"SELECT * FROM user_sett WHERE userid='$in[id]'");
if(mysqli_num_rows($checksett)>0){
	$mysettings = mysqli_fetch_array($checksett);
	$autologin = $mysettings['auto_login'];
	$darkmode = $mysettings['dark_mode'];
	$mutenoty = $mysettings['mute_noty'];
	$autolock = $mysettings['auto_lock'];
}else{
	$autologin = "on";
	$darkmode = "off";
	$mutenoty = "off";
	$autolock = "off";
}


$checkquest = mysqli_query($conn,"SELECT * FROM sec_question WHERE owner='$in[id]'");
if(mysqli_num_rows($checkquest)>0){
	$secquestionans = "set";
}else{
	$secquestionans = "off";
}


$checkbank = mysqli_query($conn,"SELECT * FROM logbank WHERE userid='$in[id]'");
if(mysqli_num_rows($checkbank)>0){
$logbankdet = mysqli_fetch_array($checkbank);
	$daysleft = $logbankdet['days'];
	$dayshold = $logbankdet['onhold'];
}else{
	$daysleft = "";
	$dayshold = "";
}

if(isset($_GET['deviceid'])){
$deviceid = mysqli_real_escape_string($conn, $_GET['deviceid']);

if($deviceid !=$in['phoneid'] && $in['phoneid']!=""){
	$devicetatus = "isfake";
	$messaged = "Your acount was loggedin somewhere else, kindly relogin and change your password";
}else{
	$devicetatus = "istrue";
	$messaged = "";
}
}else{
$devicetatus = "istrue";
	$messaged = "";

}
if($in['logistics']!=""){
	$checklog = mysqli_query($conn, "SELECT * FROM myteams WHERE id='$in[leftover_batch1]'");
if(mysqli_num_rows($checklog)>0){
$teams = mysqli_fetch_array($checklog);
$myteamname = $teams['branchname'];
}else{
$myteamname = "Team Not Found";
}
}else{
	$myteamname = "Not in Team";
}
$data = array(
		'appstatus'=>'welcome', 
		'user_id'=>$in[0], 
		'user_token'=>$in['userid'], 
		'fname'=>ucwords($in['fname']), 
		'lname'=>ucfirst($in['lname']), 
		'email'=>$in['email'], 
		'phone'=>$in['phone'], 
		'foto'=>rawurlencode($in['pix']), 
		'gender'=>$in['gender'], 
		'walletbal'=>$totalrevall, 
		'uncleared'=>$in['uncleared'], 
		'region'=>$in['region'],
		'state'=>$in['state'], 
		'acc_type'=>$in['account_type'],
		//'acc_type'=>$acctype,
		'status'=>$in['status'],
		//'whereareu'=>$in['pincode'],
		'whereareu'=>'',
		'otp'=>$in['otpcode'],
		'pincode'=>$in['pincode'],
		'address'=>$in['address'],
		'branchid'=>$in['branchid'],
		'outletid'=>$in['planid'],
		'centerid'=>$in['my_center'],
		'noty'=>$resp,
		'topay'=>$outstandy,
		'sfa'=>$sfa,
		'dob'=>$in['dob'],
		'myaccno'=>$in['account_no'],
		'bonus'=>$mybalbn,
		'walllock'=>$walllock,
		'referral'=>$newreferal,
		'zonestate'=>'',
		'myzoneid'=>$zonename,
		'regiona'=>$zonename,
		'centers'=>$zonename,
		'zonehead'=>'',
		'zcontact'=>'',
		'group'=>'',
		'requested'=>$myreq,
		'bankname'=>$bankname,
		'accno'=>$accno,
		'leader'=>$leader,
		'update'=>$appupdate,
		'notice'=>$publicnote,
		'messager'=>$messager,
		'mypost'=>$mypost,
		'accname'=>$accname,
		'autologin'=>$autologin,
		'darkmode'=>$darkmode,
		'mutenoty'=>$mutenoty,
		'autolock'=>$autolock,
		'message'=>$messaged,
		'secquestion'=>$secquestionans,
		'daillog'=>ucwords($in['logistics']),
		'logitout'=>$devicetatus,
		'myteamname'=>$myteamname,
		'daysleft'=>$daysleft,
		'dayshold'=>$dayshold
		);
$str = json_encode($data);


}else{
	$str = json_encode('{"fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":"empty", "message":"Account encountered and error, kindly logout and relogin"}');
}
echo "data: ".$str."\n\n";
 ob_end_flush();
  flush();
  
   if (connection_aborted()){
	   die();
   }
  sleep(15);
  mysqli_close($conn);
?>