<?php
include('conn.php');
//check all myproperty
$checkprop = mysqli_query($conn, "SELECT id,userid,propertyid FROM myproperty WHERE collectorid=''") or die(mysqli_error($conn));
if(mysqli_num_rows($checkprop)>0){
	while($myprop = mysqli_fetch_array($checkprop)){
		
//check dactual property

$checkdp = mysqli_query($conn, "SELECT id,email,account_no FROM users WHERE id='$myprop[userid]' AND account_no!=''");
if(mysqli_num_rows($checkdp)>0){
$propdetails = mysqli_fetch_array($checkdp);

//update myproperty
mysqli_query($conn, "UPDATE myproperty SET collectorid='$propdetails[account_no]' WHERE id='$myprop[id]'");

echo 'done for '.$myprop['id'].'<br>';
echo $propdetails['email'].'<br>';
}else{
	echo 'user not found for '.$myprop['userid'].'<br>';
}
	}
}else{
	echo 'no customer found <br>';
}
?>