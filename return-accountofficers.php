<?php
include('conn.php');
$check = mysqli_query($conn, "SELECT * FROM users WHERE account_no!='' GROUP BY account_no");
if(mysqli_num_rows($check)>0){
while($customer = mysqli_fetch_array($check)){
//$checkaos = mysqli_query($conn, "SELECT * FROM users WHERE ");
$update = mysqli_query($conn, "UPDATE users SET position='Account Officer', account_type='Admin Staff' WHERE userid='$customer[account_no]'");
if($update){
echo $customer['fname'].'<br>';
}else{
echo 'could not update agent <br>';
}
}
}else{
echo 'Nothing found<br>';
}
?>