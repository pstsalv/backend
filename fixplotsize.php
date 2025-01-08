<?php
include('conn.php');
//check all myproperty
$checkprop = mysqli_query($conn, "SELECT id,userid,paidfor,plot_size FROM payment WHERE paidfor!='' AND paidfor!='null'") or die(mysqli_error($conn));
if(mysqli_num_rows($checkprop)>0){
	while($myprop = mysqli_fetch_array($checkprop)){
		
//check dactual property

$checkdp = mysqli_query($conn, "SELECT id,type FROM property WHERE id='$myprop[paidfor]'");
$propdetails = mysqli_fetch_array($checkdp);

//update myproperty
mysqli_query($conn, "UPDATE payment SET plot_size='$propdetails[type]' WHERE id='$myprop[id]' AND plot_size=''");

echo 'done for '.$myprop['id'].' - '.$propdetails['type'].'<br>';
	}
}else{
	echo 'no property found <br>';
}
?>