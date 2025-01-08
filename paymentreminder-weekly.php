<?php
include('conn.php');
$token = mt_rand(10000,99999);

$checkprop = mysqli_query($conn, "SELECT id,userid,propertyid,amount,status,date,planid,propuid,payduration,initial_deposit,amt_due,propamount,amt_paid,amt_remain FROM myproperty WHERE propertyid!=''");
if(mysqli_num_rows($checkprop)>0){
while($dpay = mysqli_fetch_array($checkprop)){
	$damt = number_format($dpay['amt_due']);
	
	$checkuser = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$dpay[userid]'");
	if(mysqli_num_rows($checkuser)>0){
	$userinfo = mysqli_fetch_array($checkuser);
	
	$checkpropp = mysqli_query($conn, "SELECT id,title,description,amount,images,location,status,date,provider,popular,prop_uid,estate_name,unit_available,plots,prop_category,prop_full_desc,canpay_installment,state,axis,promotitle,promocode,promogift,promoprice,type,propertycode,dailyones FROM property WHERE id='$dpay[propertyid]'");
	$propinfo = mysqli_fetch_array($checkpropp);
	
if($propinfo['canpay_installment']=="weekly"){
if($dpay['amt_remain']>10){

$messagen = "Dear $userinfo[fname], your $propinfo[canpay_installment] payment of N$damt for #$dpay[propuid] property with Bliss Legacy LTD is due for payment. Kindly Pay to avoid penalty fees. Regards.";

$noty = mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$dpay[userid]','payment','Payment Reminder','$messagen','unread',now())");

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
  'to' => $userinfo['phone'],
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
    echo 'Error: ' .curl_error($ch);
} else {
    $respons = json_decode($response, true);
	 if ($respons['successful'] !="") {
		 mysqli_query($conn, "INSERT INTO smscharges VALUES(NULL,'$userinfo[id]','reminder','BlissPay','$messagen','$respons[comment]','$respons[units_calculated]','15',now(),'$respons[successful]','$respons[sms_pages]')");
	 }
	 
}

echo 'Message sent to - '.$dpay['userid'].' - '.$dpay['propuid'].' Balance is -'.$dpay['amt_remain'].'<br>';
}else{
	echo $dpay['userid'].' User paid fully -'.$dpay['amt_remain'].'<br>';
}
}else{
	echo 'User not on weekly payment <br>';
}
}else{
	echo 'User not found <br>';
}
}
}else{
	echo 'nothing found <br>';
}
?>