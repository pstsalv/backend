<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include('conn.php');
$answer = mysqli_real_escape_string($conn, $_GET['answer']);

if($answer=="branch"){
?>
 <div class="group-input">
                <label class="bg-light border text-dark">Select Your Branch</label>
                <select class="getbranchy" name="branchid" id="branchid" required onchange="getBranch('branchid')">
                  <option value="">Select your branch</option>
				  <?php $checkbr = mysqli_query($conn, "SELECT * FROM branches");
				  while($jbranch = mysqli_fetch_array($checkbr)){
					  $checkow = mysqli_query($conn, "SELECT * FROM users WHERE userid='$jbranch[coordinator]'");
					  $leaderbr = mysqli_fetch_array($checkow);
					  ?>
				  <option value="<?php print $jbranch['branch_rand'];?>" data-leader="<?php print $leaderbr['fname'];?> <?php print $leaderbr['lname'];?>" data-contact="<?php print $leaderbr['phone'];?>" data-state="<?php print $jbranch['state'];?>"><?php print $jbranch['branchname'];?></option>
				  <?php }?>
                </select>
            </div>
			
 <div class="group-input">
                <label class="bg-light border text-dark">Branch Head</label>
                <input type="text" name="leadername" class="branchhead" required readonly />
            </div>
            <div class="group-input">
                <label class="bg-light border text-dark">Branch Contact</label>
                <input type="text" name="leaderphone" class="branchcontact" required readonly />
            </div>

    <div class="group-input">
                <label class="bg-light border text-dark">Branch State</label>
                <input type="text" name="zonestate" class="branchstate" readonly />
            </div>


<input type="hidden" name="outletid" class="myoutletid" />
<input type="hidden" name="centerid" class="centerid" />

<?php }elseif($answer=="outlet"){ ?>	
<input type="hidden" name="branchid" class="branchid" />
<input type="hidden" name="centerid" class="centerid" />
<div class="group-input">
                <label class="bg-light border text-dark">Select Outlet</label>
                <select class="box-sl-profile" name="outletid" id="outletidy" onchange="getOutlet('outletidy')" required>
<option value="">Select</option>

 <?php $checkout = mysqli_query($conn, "SELECT * FROM allzones");
				  while($joutlet = mysqli_fetch_array($checkout)){
					$checkbrr = mysqli_query($conn, "SELECT * FROM branches WHERE branch_rand='$joutlet[branchid]'");
				  $jbranchh = mysqli_fetch_array($checkbrr);
					  ?>
				  <option value="<?php print $joutlet['id'];?>" data-leader="<?php print $joutlet['zonehead'];?>" data-contact="<?php print $joutlet['phone'];?>" data-state="<?php print $joutlet['state'];?>" data-branchnamed="<?php print $jbranchh['branchname'];?>" data-branchid="<?php print $joutlet['branchid'];?>"><?php print $joutlet['zone'];?></option>
				  <?php }?>
				  
				  
</select>
            </div>
			
			

			
            <div class="group-input">
                <label class="bg-light border text-dark">My Branch</label>
                <input type="text" name="branchname" class="branchnamed" required readonly onclick="javascript:alert('If this didnt populate after selecting Outlet Kindly update again, apologies for the inconvinience');" />
            </div>

			
            <div class="group-input">
                <label class="bg-light border text-dark">Outlet Head</label>
                <input type="text" name="leadername" class="outlethead" required readonly />
            </div>
            <div class="group-input">
                <label class="bg-light border text-dark">Outlet Contact</label>
                <input type="text" name="leaderphone" class="outletcontact" required readonly />
            </div>
			
			 <div class="group-input">
                <label class="bg-light border text-dark">Outlet State</label>
                <input type="text" name="zonestate" class="outletstate" readonly />
            </div>
			
			
<?php }elseif($answer=="center"){ ?>	

<input type="hidden" name="outletid" class="myoutletid" />
<input type="hidden" name="branchid" class="branchid" />
<div class="group-input">
                <label class="bg-light border text-dark">Select Center</label>
                <select class="box-sl-profile" name="centerid" id="centerd" onchange="getCenter('centerd')" required>
<option value="">Select</option>

<?php $checkc = mysqli_query($conn, "SELECT * FROM centers");
				  while($jcenter = mysqli_fetch_array($checkc)){
					  $checkowc = mysqli_query($conn, "SELECT * FROM users WHERE userid='$jcenter[leader_id]'");
					  $leaderbr = mysqli_fetch_array($checkowc);
					  
					  $checkbrr = mysqli_query($conn, "SELECT * FROM branches WHERE branch_rand='$jcenter[branch_id]'");
				  $jbranchh = mysqli_fetch_array($checkbrr);
				  
				  
				  $checkoutm = mysqli_query($conn, "SELECT * FROM allzones WHERE id='$jcenter[outlet_id]'");
				  $joutletm = mysqli_fetch_array($checkoutm);
					  ?>
				  <option value="<?php print $jcenter['id'];?>" data-leader="<?php print $leaderbr['fname'];?> <?php print $leaderbr['lname'];?>" data-contact="<?php print $leaderbr['phone'];?>" data-state="<?php print $jcenter['state'];?>" data-branchnamed="<?php print $jbranchh['branchname'];?>" data-branchid="<?php print $jcenter['branch_id'];?>" data-myoutletname="<?php print $joutletm['zone'];?>" data-myoutletid="<?php print $jcenter['outlet_id'];?>"><?php print $jcenter['center_name'];?></option>
				  <?php }?>
</select>
            </div>
			
			

            <div class="group-input">
                <label class="bg-light border text-dark">My Branch</label>
                <input type="text" name="branchname" class="branchnamed" required readonly onclick="javascript:alert('If this didnt populate after selecting Center Kindly update again, apologies for the inconvinience');" />
            </div>
			
            <div class="group-input">
                <label class="bg-light border text-dark">My Outlet</label>
                <input type="text" name="outletidnm" class="myoutlet" required readonly onclick="javascript:alert('If this didnt populate after selecting Center Kindly update again, apologies for the inconvinience');" />
            </div>
			
            <div class="group-input">
                <label class="bg-light border text-dark">Center Head</label>
                <input type="text" name="zonehead" class="centerhead" required readonly />
            </div>
            <div class="group-input">
                <label class="bg-light border text-dark">Center Contact</label>
                <input type="text" name="leaderphoneleaderphone" class="centercontact" required readonly />
            </div>
			
			 <div class="group-input">
                <label class="bg-light border text-dark">Center State</label>
                <input type="text" name="zonestate" class="centerstate" readonly />
            </div>
			
<?php }?>
			