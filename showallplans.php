<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

?>
 <div class="group-input">
                    <label for="" class="bg-light border text-dark">Payment Plan</label>
                     <select class="form-select chooseplan" name="allplan" style="height:45px" required>
					<option value="">Select Plan</option>
					<?PHP $chkp=mysqli_query($conn,"SELECT id,plan_name,amount,plancode,status,type,duration,amt_to_pay,location,plot_size,payplancode FROM payment_plan WHERE status='active'");
					if(mysqli_num_rows($chkp)>0){
						while($prop=mysqli_fetch_array($chkp)){?>
					<option value="<?php print $prop['plancode'];?>"><?php print $prop['plan_name'];?> - ₦ <?php print number_format($prop['amount'],2);?></option>
					<?php }}else{?>
					<option>No active plan</option>
					<?php }?>
					</select>
                </div>