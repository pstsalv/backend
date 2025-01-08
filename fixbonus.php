<?php
include('conn.php');
$dismonth = date('F');
$prevpage = $_SERVER['HTTP_REFERER'];
$type = mysqli_real_escape_string($conn, $_GET['type']);
$monthy = mysqli_real_escape_string($conn, $_GET['month']);
$dismonth = date('Y-m-d h:i:s',strtotime("$monthy"));
$typecaps = $type;

$checkacc = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE account_type!='customer' AND account_type!='Admin Staff' ORDER BY id ASC");
while($userz=mysqli_fetch_array($checkacc)){
													
$checkamtppo = mysqli_query($conn,"SELECT SUM(amount) AS revenue FROM payment WHERE gentid='$userz[userid]' AND status='approved'");
$rowppo = mysqli_fetch_assoc($checkamtppo);
$sumppo = $rowppo['revenue'];
$outstand = "$sumppo";
if($outstand!==""){
$myclients = $outstand;
}else{
$myclients = "0.00";
};


if($myclients>2999){

		mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$userz[id]','3000','$typecaps Referral Bonus for client that paid N3,000 and above','unapproved',now(),'bonus','add','$dismonth','$type','')");

}
}

header("Location:$prevpage");
mysqli_close($conn);
?>