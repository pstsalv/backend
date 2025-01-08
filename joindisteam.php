<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');    // cache for 1 day
include("conn.php");
$token = mt_rand(9999,99999);
$me = mysqli_real_escape_string($conn, $_GET['me']);

$check = mysqli_query($conn, "SELECT * FROM users WHERE id='$me'");
if(mysqli_num_rows($check)>0){
$user = mysqli_fetch_array($check);
?>
<option value="" disabled>Select a team</option>
<?php
$checkteam = mysqli_query($conn, "SELECT * FROM myteams WHERE branch_id='$user[branchid]' AND whtsapp_link<25");
while($teams = mysqli_fetch_array($checkteam)){?>
<option value="<?php print $teams['id'];?>"><?php print ucwords($teams['branchname']);?></option>
<?php }}else{?>
<option value="" disabled>You are already in a team</option>
<?php }?>