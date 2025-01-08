<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");
?>
<option value="">Select Zone First</option>
<?php
$area = mysqli_real_escape_string($conn, $_GET['area']);
$heckpr = mysqli_query($conn, "SELECT * FROM allzones WHERE branchid='$area' ORDER BY zone ASC");
if(mysqli_num_rows($heckpr)>0){
while($zones = mysqli_fetch_array($heckpr)){
?>
<option value="<?php print $zones['id'];?>"><?php print $zones['zone'];?></option>
<?php }}else{?>
<option>Please update the app</option>
<?php }?>