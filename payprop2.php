<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
$checkus=mysqli_query($conn, "SELECT email FROM users WHERE id='$me'");
$user = mysqli_fetch_array($checkus);
?>
<input type="hidden" name="custemail" class="dcustemail" value="<?php print $user['email'];?>" />

 <div class="group-input input-field input-money ">					
  <label for="">Select Customer's Property</label>
  <select name="property" class=""  id="forproperty" required onchange="getProperty('forproperty')" required>

<option value="" disabled selected>Select your property</option>
<?php $chkp=mysqli_query($conn,"SELECT id,userid,propertyid,amount,status,date,planid,propuid,payduration,initial_deposit,amt_due,propamount,amt_paid,amt_remain FROM myproperty WHERE userid='$me'");
if(mysqli_num_rows($chkp)>0){
	while($prop=mysqli_fetch_array($chkp)){?>
<option value="<?php print $prop['propertyid'];?>" data-inital="<?php print round($prop['amt_due'],0);?>"><?php print $prop['propuid'];?></option>
<?php }}else{?>
<option value="" disabled>Customer is not subscribed to any property yet</option>
<?php }?>
</select>
 <span class="icon-clear"></span>
 </div>