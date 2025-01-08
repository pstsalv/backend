<?php
include('conn.php');

$checkkkk = mysqli_query($conn, "SELECT * FROM bonuses WHERE amount='7440' AND hideshow='daily' AND YEAR(date) = YEAR(CURDATE())");
if(mysqli_num_rows($checkkkk)>0){
while($duser = mysqli_fetch_array($checkkkk)){

mysqli_query($conn, "UPDATE mybonus SET amount=amount-7440,date=now() WHERE owner='$duser[userid]'");


mysqli_query($conn, "INSERT INTO notification VALUES(NULL,'$duser[userid]','payment','Wallet Deducted','Your wallet has been deducted because you got overpaid with bonus of N7,440','unread',now())");

echo $duser['userid'].' = N2,480<br>';
}
}else{
echo 'no such bonus found<br>';
}

?>