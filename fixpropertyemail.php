<?php
include('conn.php');
//check all myproperty
$checkprop = mysqli_query($conn, "SELECT * FROM banks WHERE acc_email=''") or die(mysqli_error($conn));
if(mysqli_num_rows($checkprop)>0){
	while($myprop = mysqli_fetch_array($checkprop)){
		
//check dactual property

$checkdp = mysqli_query($conn, "SELECT id,email FROM users WHERE id='$myprop[userid]'");
$propdetails = mysqli_fetch_array($checkdp);

//update myproperty
mysqli_query($conn, "UPDATE banks SET acc_email='$propdetails[email]' WHERE id='$myprop[id]'");

echo 'done for '.$myprop['id'].' - '.$propdetails['email'].'<br>';
	}
}else{
	echo 'no customer found <br>';
}
?>