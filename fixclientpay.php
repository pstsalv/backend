<?php
include('conn.php');

$checkprop = mysqli_query($conn, "SELECT * FROM users WHERE wallet_bal=0 AND status!='deleted' AND account_type='customer'");
if(mysqli_num_rows($checkprop)>0){
	while($user=mysqli_fetch_array($checkprop)){
	
//calculate all daily,weekly,monthly money paid by customer
  
  $checkamtpp2d = mysqli_query($conn,"SELECT SUM(amount) AS amttpaidd FROM payment WHERE status='approved' AND userid='$user[id]'");
$rowpp2d = mysqli_fetch_assoc($checkamtpp2d);
$sumpp2d = $rowpp2d['amttpaidd'];
$totalpayd2d = "$sumpp2d";
if($totalpayd2d!==""){
$mybalpd = $totalpayd2d;
}else{
	$mybalpd = 0;
}
if($mybalpd>0){
$dpos = 'paid';
}else{
$dpos = 'unpaid';
}
mysqli_query($conn,"UPDATE users SET wallet_bal='$mybalpd',position='$dpos' WHERE id='$user[id]' AND account_type='customer'");

echo 'done '.$user['fname'].' - '.$mybalpd.' '.$dpos.'<br>';

	}

}
?>