<?php
include('conn.php');
//check all myproperty
$checkprop = mysqli_query($conn, "SELECT id,userid,propertyid,plot_size FROM myproperty WHERE plot_size=''") or die(mysqli_error($conn));
if(mysqli_num_rows($checkprop)>0){
	while($myprop = mysqli_fetch_array($checkprop)){
		
//check d actual property

$checkdp = mysqli_query($conn, "SELECT id,type FROM property WHERE id='$myprop[propertyid]'");
$propdetails = mysqli_fetch_array($checkdp);

//update myproperty
mysqli_query($conn, "UPDATE myproperty SET plot_size='$propdetails[type]' WHERE id='$myprop[id]'");

echo 'done for '.$myprop['id'].' - '.$propdetails['type'].'<br>';
	}
}else{
	echo 'no property found <br>';
}
?>