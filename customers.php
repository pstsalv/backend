<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache'); 
header('Content-Type: application/json');
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);


$checkwho = mysqli_query($conn,"SELECT * FROM users WHERE id='$me'");
$agent = mysqli_fetch_array($checkwho);

$check = mysqli_query($conn, "SELECT * FROM users WHERE account_no!='' AND account_type='customer' AND wallet_bal>0 AND state='$agent[state]' AND lat!='none' AND lat!='' AND status='active'");
if(mysqli_num_rows($check)>0){

while($clients = mysqli_fetch_array($check)){
$file = 'pix/thumb/'.$clients['pix'];

if(file_exists($file)){
   $image = 'https://blisslegacy.com/nswin/pix/thumb/'.$clients['pix'];
} else {
   $image = 'images/male.png';
}


$locations[] = array(
      'clientid' => $clients['id'],
      'type' => $clients['account_type'],
      'imagesd' => $clients['pix'],
      'images' => $image,
      'name' => $clients['fname'].' '.$clients['lname'],
      'lat' => (float)$clients['lat'],
      'lng' => (float)$clients['lng']
    );
}
}else{
  echo "0 results";
}
mysqli_close($conn);

echo json_encode($locations);
?>