<?php
include('conn.php');


$checkprop = mysqli_query($conn, "SELECT * FROM bonuses WHERE DATE(date) = DATE_SUB(CURDATE(), INTERVAL 12 DAY) GROUP BY userid");
if(mysqli_num_rows($checkprop)>0){
while($dpay = mysqli_fetch_array($checkprop)){

	$update = mysqli_query($conn, "UPDATE mybonus SET date='$dpay[date]' WHERE owner='$dpay[userid]'");
echo '1-'.$dpay['userid'].' - '.$dpay['date'].'<br>';
}
}else{
	echo 'nothing found <br>';
}
?>