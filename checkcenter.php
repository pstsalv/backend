<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

?>
<option value="">Select Center (optional)</option>
<?php
$area = mysqli_real_escape_string($conn, $_GET['loco']);
$zone = mysqli_real_escape_string($conn, $_GET['zone']);
$heckpr = mysqli_query($conn, "SELECT * FROM centers WHERE state='$area' AND branch_id='$zone' ORDER BY center_name ASC");
if(mysqli_num_rows($heckpr)>0){
while($zones = mysqli_fetch_array($heckpr)){
?>
<option value="<?php print $zones['id'];?>"><?php print $zones['center_name'];?></option>
<?php }}else{?>
<option value="">No center available</option>
<?php }?>