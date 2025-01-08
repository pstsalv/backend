<?php
header("access-control-allow-origin: *");
//header('Content-Type: text/event-stream');
header('content-type: application/json; charset=utf-8');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 0");

include_once("conn.php");

$me = mysqli_real_escape_string($conn, $_GET['me']);
if(isset($_GET['userid'])){
$userid = mysqli_real_escape_string($conn, $_GET['userid']);
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$me' AND userid='$userid' AND (status!='banned' OR status!='suspended') LIMIT 1");
}else{
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$me' AND (status!='banned' OR status!='suspended') LIMIT 1");
}

if(mysqli_num_rows($result)>0){
$in = mysqli_fetch_array($result);
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
$mybalp2 = $totalpayd2;
}else{
	$mybalp2 = 0;
}
mysqli_query($conn,"UPDATE users SET wallet_bal='$mybalp2' WHERE id='$in[id]' AND account_type='customer'");
}

//calculate all daily,weekly,monthly money paid by customer (half plot)
  
  $checkamtpp2d = mysqli_query($conn,"SELECT SUM(amount) AS amttpaidd FROM payment WHERE status='approved' AND userid='$in[id]' AND propert_type!='outright' AND plot_size='half plot'");
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
  
  $checkamtpp2df = mysqli_query($conn,"SELECT SUM(amount) AS amttpaidfd FROM payment WHERE status='approved' AND userid='$in[id]' AND propert_type!='outright' AND plot_size='half plot'");
$rowpp2df = mysqli_fetch_assoc($checkamtpp2df);
$sumpp2df = $rowpp2df['amttpaidfd'];
$totalpayd2df = "$sumpp2df";
if($totalpayd2df!==""){
$mybalpdf = $totalpayd2df;
}else{
	$mybalpdf = 0;
}
mysqli_query($conn,"UPDATE users SET full_plot='$mybalpdf' WHERE id='$in[id]' AND account_type='customer'");


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
		
	
	$checkvp = mysqli_query($conn, "SELECT id,messages,status,date FROM public_notice WHERE status='active'");
	if(mysqli_num_rows($checkvp)>0){
		$messagesu = mysqli_fetch_array($checkvp);
	$publicnote = "yes";
	$messager = $messagesu['messages'];
	}else{
		$publicnote = "no";
	$messager = "";
	}
		
		if($in['account_type']=="Admin Staff"){
		$mypost = $in['position'];
	}else{
		$mypost = $in['account_type'];
	}
	
if($in['position']=="Account Officer"){
		
		$calcaos = mysqli_query($conn,"SELECT SUM(amount) AS revenueao FROM  payment WHERE propert_type!='outright' AND (admin_approved='virtual' OR admin_approved='virtual') AND (collectorid='$in[userid]' OR agentid='$in[userid]') AND propert_type!='promo' AND MONTH(date_paid) = MONTH(CURDATE()) AND YEAR(date_paid) = YEAR(CURDATE())");
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
		$totalrevall = $in['wallet_bal'];
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

$checkbank = mysqli_query($conn,"SELECT * FROM logbank WHERE userid='$in[id]'");
if(mysqli_num_rows($checkbank)>0){
$logbankdet = mysqli_fetch_array($checkbank);
	$daysleft = $logbankdet['days'];
	$dayshold = $logbankdet['onhold'];
}else{
	$daysleft = "";
	$dayshold = "";
}


$data = array(
		'appstatus'=>'welcome', 
		'user_id'=>$in[0], 
		'user_token'=>$in['userid'], 
		'fname'=>$in['fname'], 
		'lname'=>$in['lname'], 
		'email'=>$in['email'], 
		'phone'=>$in['phone'], 
		'foto'=>rawurlencode($in['pix']), 
		'gender'=>$in['gender'], 
		'walletbal'=>$totalrevall, 
		'uncleared'=>$in['uncleared'], 
		'region'=>$in['region'],
		'state'=>$in['state'], 
		'acc_type'=>$in['account_type'],
		'status'=>$in['status'],
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
		'referral'=>$newreferal,
		'zonestate'=>$zonestate,
		'myzoneid'=>$zonename,
		'regiona'=>$zonename,
		'zonehead'=>$zonehead,
		'zcontact'=>$zphone,
		'group'=>$grouplink,
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
		'secquestion'=>$secquestionans,
		'daillog'=>ucwords($in['logistics']),
		'myteamname'=>$myteamname,
		'daysleft'=>$daysleft,
		'dayshold'=>$dayshold
		);
$responses = json_encode($data);
echo $responses;
}else{
	$responses = json_encode('{"fname":"", "lname":"", "phone":"", "photo":"", "email":"", "username":"", "status":"invalid", "message":"Account encountered and error, kindly logout and relogin"}');
	echo $responses;
}
mysqli_close($conn);
?>