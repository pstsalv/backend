<?php
include('conn.php');

$monthy = "November";
$dismonth = date('Y-m-d h:i:s',strtotime("$monthy"));

	$filtermonth = $monthy;
	if($filtermonth=="January"){
		$dmonth = 1;
	}elseif($filtermonth=="February"){
		$dmonth = 2;
	}elseif($filtermonth=="March"){
		$dmonth = 3;
	}elseif($filtermonth=="April"){
		$dmonth = 4;
	}elseif($filtermonth=="May"){
		$dmonth = 5;
	}elseif($filtermonth=="June"){
		$dmonth = 6;
	}elseif($filtermonth=="July"){
		$dmonth = 7;
	}elseif($filtermonth=="August"){
		$dmonth = 8;
	}elseif($filtermonth=="September"){
		$dmonth = 9;
	}elseif($filtermonth=="October"){
		$dmonth = 10;
	}elseif($filtermonth=="November"){
		$dmonth = 11;
	}elseif($filtermonth=="December"){
		$dmonth = 12;
	}else{
		$dmonth = 'MONTH(CURDATE())';
	}


$checkacc = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE account_type='Consultant' AND wallet_bal>0 ORDER BY id ASC");

if(mysqli_num_rows($checkacc)>0){
while($userz = mysqli_fetch_array($checkacc)){
													

$checkprop2 =mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE agent='$userz[userid]' AND account_type='customer' AND wallet_bal>4999 AND MONTH(date) = $dmonth");
$myclients = mysqli_num_rows($checkprop2);

if($myclients>0){



//for salary
	//*30,000 naira basic salary will be paid to anyone that brings 6-7clients.
	if($myclients>5 && $myclients<8){
		mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$userz[id]','30000','Had $myclients cliets this month','unapproved','$dismonth','salary','add','$monthy','$myclients','')");
echo 'done <br>';
	}elseif($myclients>7 && $myclients<16){
		//*40,000 naira  basic salary will be paid to anyone that brings 8-15client
		mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$userz[id]','40000','Had $myclients cliets this month','unapproved','$dismonth','salary','add','$monthy','$myclients','')");
		echo 'done <br>';
	}elseif($myclients>15 && $myclients<39){
		//* 70,000 naira basic salary will be paid to anyone that brings 16 to 39 clients and above.
		mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$userz[id]','70000','Had $myclients cliets this month','unapproved','$dismonth','salary','add','$monthy','$myclients','')");
		echo 'done <br>';
	}elseif($myclients>39){
		//* 100,000 naira basic salary will be paid to anyone that brings 40 clients and above.
		mysqli_query($conn,"INSERT INTO bonuses VALUES(NULL,'$userz[id]','100000','Had $myclients cliets this month','unapproved','$dismonth','salary','add','$monthy','$myclients','')");
		echo 'done <br>';
	}


}else{
	echo 'nothing found<br>';
}


}



}else{
	echo 'something shele';
}
mysqli_close($conn);
?>