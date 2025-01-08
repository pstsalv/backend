<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");
$me = mysqli_real_escape_string($conn, $_GET['me']);
$check = mysqli_query($conn, "SELECT * FROM mynextkin WHERE userid='$me'");
$kin = mysqli_fetch_array($check);
?>
		<input type="hidden" name="me" class="me" />
			
			 <div class="group-input">
                <label class="bg-light border text-dark">Relationship</label>
                <select class="nkrelationship" name="relationship" id="" required>
                  <option value="">Select Relationship</option>
                  <option>Wife</option>
                  <option>Husband</option>
                  <option>Child</option>
                  <option>Parent</option>
                  <option>Friend</option>
                  <option>Family Member</option>
                  <option>Others</option>
                </select>
            </div>
			
			 <div class="group-input">
                <label class="bg-light border text-dark">Select Gender</label>
                <select class="box-sl-profile nkgender" name="gender" required>
<option value="">Select Gender</option>
<option>Male</option>
<option>Female</option>
</select>
            </div>
			
			
            <div class="group-input">
                <label class="bg-light border text-dark">Full Names</label>
                <input type="text" name="fullnames" class="nkfullnames" required placeholder="Enter Nexk of Kin Fullnames" />
            </div>
            <div class="group-input">
                <label class="bg-light border text-dark">Phone</label>
                <input type="text" name="phone" class="nkphone" required placeholder="Enter Nexk of Kin Phone" />
            </div>
			 <div class="group-input">
                <label class="bg-light border text-dark">Email (optional)</label>
                <input type="text" name="phone" class="nkemail" placeholder="Enter Nexk of Kin email" />
            </div>
			
			<div class="group-input">
                <label class="bg-light border text-dark">Address</label>
                <input type="text" name="address" class="nkaddress" placeholder="Enter Nexk of Kin address" />
            </div>
			
	
            <div class="bottom-navigation-bar bottom-btn-fixed st2">
                <button type="submit" class="tf-btn accent large">Save Data</button>
            </div>
