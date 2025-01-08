<?php
header("access-control-allow-origin: *");
header('Content-Type: text/event-stream');
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');
include_once("conn.php");
$me = mysqli_real_escape_string($conn,$_GET['me']);

$check =mysqli_query($conn, "SELECT id,owner,type,title,message,status,date FROM notification WHERE owner='$me' AND status!='read'");
if(mysqli_num_rows($check)>0){
$total = mysqli_num_rows($check);
$resp = '<span>'.$total.'</span>';

}else{
$resp ='';
}
echo "data: ".$resp."\n\n";
 ob_end_flush();
  flush();
  
   if (connection_aborted()){
	   die();
   }
  sleep(1);
  mysqli_close($conn);
?>