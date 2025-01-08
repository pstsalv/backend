<?php
include('conn.php');


$checkprop = mysqli_query($conn, "SELECT id,userid,propertyid,amount,status,date,planid,propuid,payduration,initial_deposit,amt_due,propamount,amt_paid,amt_remain FROM myproperty WHERE propertyid!='' AND state!='calculated' LIMIT 1000");
if(mysqli_num_rows($checkprop)>0){
while($dpay = mysqli_fetch_array($checkprop)){
	
	$checkrev1 = mysqli_query($conn,"SELECT SUM(amount) AS paidtoday FROM payment WHERE status='approved' AND userid='$dpay[userid]' AND paidfor='$dpay[propertyid]'");

$rowpp1 = mysqli_fetch_assoc($checkrev1);
$sumpp1 = $rowpp1['paidtoday'];
$totalpayd1 = "$sumpp1";
if($totalpayd1 !==""){
$paiddis = $totalpayd1;
}else{
$paiddis = "0";
};

$paiddis2 = $dpay['propamount']-$paiddis;


	$update = mysqli_query($conn, "UPDATE myproperty SET amt_remain='$paiddis2',amt_paid='$paiddis',state='calculated' WHERE propertyid='$dpay[propertyid]' AND userid='$dpay[userid]'");
	echo 'Calculated for - '.$dpay['userid'].' - '.$dpay['propuid'].' Balance is -'.$paiddis2.'<br>';
}
}else{
	echo 'nothing found <br>';
}
?>