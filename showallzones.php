<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include('conn.php');
$type = mysqli_real_escape_string($conn, $_GET['type']);
if($type=="Branch"){
?>
<option value="">Select Branch</option>
<?php
$check = mysqli_query($conn, "SELECT * FROM branches");
while($branches = mysqli_fetch_array($check)){
?>
<option value="<?php print $branches['branch_rand'];?>"><?php print $branches['branchname'];?> - <?php print $branches['state'];?></option>
<?php
}
}elseif($type=="Outlet"){
?>
<option value="">Select Outlet</option>
<?php
$check = mysqli_query($conn, "SELECT * FROM allzones");
while($branches = mysqli_fetch_array($check)){
?>
<option value="<?php print $branches['id'];?>"><?php print $branches['zone'];?> - <?php print $branches['state'];?></option>
<?php
}
}else{
?>
<option value="">Select Center</option>
<?php
$check = mysqli_query($conn, "SELECT * FROM centers");
while($branches = mysqli_fetch_array($check)){
?>
<option value="<?php print $branches['id'];?>"><?php print $branches['center_name'];?> - <?php print $branches['state'];?></option>
<?php
}
}
?>