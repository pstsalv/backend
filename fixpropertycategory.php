<?php
include('conn.php');


$checkprop = mysqli_query($conn, "SELECT * FROM payment WHERE paycategory='' AND paidfor!='null' AND status='approved' LIMIT 80000");
if(mysqli_num_rows($checkprop)>0){
while($dpay = mysqli_fetch_array($checkprop)){

$checkpp = mysqli_query($conn, "SELECT id, prop_category FROM property WHERE id='$dpay[paidfor]'");
if(mysqli_num_rows($checkpp)>0){
$dproperty = mysqli_fetch_array($checkpp);

	$update = mysqli_query($conn, "UPDATE payment SET paycategory='$dproperty[prop_category]' WHERE id='$dpay[id]'");
	echo 'done for - '.$dpay['userid'].' - '.$dpay['paidfor'].'<br>';
}else{
echo 'property not found <br>';
}
}
}else{
	echo 'nothing found <br>';
}
?>