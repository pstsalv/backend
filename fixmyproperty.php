<?php
include('conn.php');
//check all myproperty
$checkprop = mysqli_query($conn, "SELECT id,userid,propertyid FROM myproperty WHERE branchid='' AND userid!=''") or die(mysqli_error($conn));
if(mysqli_num_rows($checkprop)>0){
	while($myprop = mysqli_fetch_array($checkprop)){
		
//check dactual property

$checkdp = mysqli_query($conn, "SELECT id,userid,planid,state,branchid,my_center,second_agent FROM users WHERE id='$myprop[userid]'");
$propdetails = mysqli_fetch_array($checkdp);

//update myproperty
mysqli_query($conn, "UPDATE myproperty SET state='$propdetails[state]',branchid='$propdetails[branchid]',outletid='$propdetails[planid]',centerid='$propdetails[my_center]',customercare='$propdetails[second_agent]' WHERE userid='$propdetails[id]'");

echo 'done for '.$myprop['id'].' - '.$propdetails['canpay_installment'].'<br>';
	}
}else{
	echo 'no customer found <br>';
}
?>