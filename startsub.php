<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$token = mt_rand(99999,99999);
$couponsy = mt_rand(99999999,99999999);

$ptitle = mysqli_real_escape_string($conn,$_POST['ptitle']);
$puid = mysqli_real_escape_string($conn,$_POST['puid']);
$pamount2 = mysqli_real_escape_string($conn,$_POST['pamount']);
$propid = mysqli_real_escape_string($conn,$_POST['propid']);
$plan = mysqli_real_escape_string($conn,$_POST['plan']);
$amt_due = mysqli_real_escape_string($conn,$_POST['amt_due']);
$me = mysqli_real_escape_string($conn,$_POST['me']);
$payduration = mysqli_real_escape_string($conn,$_POST['payduration']);
$amounty = mysqli_real_escape_string($conn,$_POST['amount']);
$propertyamounnt = mysqli_real_escape_string($conn,$_POST['propertyamounnt']);
$giftname = mysqli_real_escape_string($conn,$_POST['giftname']);

if($giftname==""){
$agentbonus = 0.15;
}elseif($giftname=="rice"){
$agentbonus = 0.10;
}elseif($giftname=="gen"){
$agentbonus = 0.10;
}


if(isset($_POST['meemail'])){
	$meemail = mysqli_real_escape_string($conn,$_POST['meemail']);
}else{
	$meemail = '';
}

if(isset($_POST['couponcode'])){
	$couponcode = mysqli_real_escape_string($conn,$_POST['couponcode']);
}else{
	$couponcode = '';
}

$checkdp = mysqli_query($conn, "SELECT * FROM property WHERE id='$propid'");
$propdetails = mysqli_fetch_array($checkdp);


if($couponcode!=""){

$checkcode = mysqli_query($conn, "SELECT * FROM coupon WHERE userid='$me' LIMIT 1");
if(mysqli_num_rows($checkcode)>0){
$mycoupon = mysqli_fetch_array($checkcode);
if($mycoupon['validity']>0){
if($propdetails['promoprice']>0){
$discounted = $propdetails['promoprice']/100;
$amount = $amounty*$discounted;
$actualamt = $propertyamounnt*$discounted;
$pamount = number_format($propertyamounnt*$discounted);
}else{
$discounted = 0.5;
$amount = $amounty*$discounted;
$actualamt = $propertyamounnt*$discounted;
$pamount = number_format($propertyamounnt*$discounted);
}
}else{
	$amount = $amounty;
$pamount = number_format($propertyamounnt);
$actualamt = $propertyamounnt;
}

}else{
mysqli_query($conn, "INSERT INTO coupon VALUES(NULL,'$me','$couponcode','2','active',now())");
if($propdetails['promoprice']>0){
$discounted = $propdetails['promoprice']/100;
$amount = $amounty*$discounted;
$pamount = number_format($propertyamounnt*$discounted);
$actualamt = $propertyamounnt*$discounted;
}else{
$discounted = 0.5;
$amount = $amounty*$discounted;
$pamount = number_format($propertyamounnt*$discounted);
$actualamt = $propertyamounnt*$discounted;
}
}
}else{
$amount = $amounty;
$pamount = number_format($propertyamounnt);
$actualamt = $propertyamounnt;
}

$prettyamt = number_format($amount,2);


//check if customer has any property before
if($meemail!=''){
$checkpb = mysqli_query($conn, "SELECT id FROM myproperty WHERE payment_email='$meemail'");
if(mysqli_num_rows($checkpb)<1){
	$usedemail = $meemail;
}else{
	$usedemail = $meemail;
}
}else{
	$usedemail = '';
}
$checkme = mysqli_query($conn, "SELECT * FROM users WHERE id='$me'");
$medetails = mysqli_fetch_array($checkme);


$store = mysqli_query($conn, "INSERT INTO myproperty VALUES(NULL,'$me','$propid','N $pamount','ongoing',now(),'$plan','$puid','$payduration','$amount','$amt_due','$actualamt','0','$actualamt','$propdetails[canpay_installment]','$propdetails[dailyones]','','$propdetails[type]','$propdetails[prop_category]','$propdetails[state]','$medetails[account_no]','$medetails[branchid]','$medetails[planid]','$medetails[my_center]','$medetails[second_agent]','$couponcode','$propdetails[promoprice]','$giftname')") or die(mysqli_error($conn));

$storeplan = mysqli_query($conn, "INSERT INTO myplans VALUES(NULL,'$me','$plan','$propid','$amount',now())") or die(mysqli_error($conn));

$newid =  mysqli_insert_id($conn);

if($store){
	mysqli_query($conn, "UPDATE property SET popular=popular-1, unit_available=unit_available-1 WHERE id='$propid'");

	mysqli_query($conn, "UPDATE coupon SET validity=validity-1 WHERE userid='$me'");
	
$message = "Your property subscription has been created. Based on your selected payment plan, your payment duration is $payduration. Your contract of sales is ready. Append your signature on the app. Other documents will be issued at the completion of your payment of $pamount. Thanks";

$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$me','notification','Subscription sucessful','$message','unread',now())");

echo 'success';
}else{
	die('error');
}
?>