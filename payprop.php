<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
?>
 <div class="group-input">
                    <label for="" class="bg-light border text-dark">Payment For?</label>
                    <select name="property" class="propertypid"  id="forproperty" required onchange="getProperty('forproperty')">
					<option value="" disabled selected>Select your property</option>
					<?php $chkp=mysqli_query($conn,"SELECT id,userid,propertyid,amount,status,date,planid,propuid,payduration,initial_deposit,amt_due,propamount,amt_paid,amt_remain FROM myproperty WHERE userid='$me'");
					if(mysqli_num_rows($chkp)>0){
						while($prop=mysqli_fetch_array($chkp)){?>
					<option value="<?php print $prop['propertyid'];?>" data-inital="<?php print round($prop['amt_due'],0);?>"><?php print $prop['propuid'];?> - ₦<?php print number_format($prop['propamount']);?></option>
					<?php }}else{?>
					<option value="" disabled>You are not subscribed to any property</option>
					<?php }?>
					</select>
                </div>
