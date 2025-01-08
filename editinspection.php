<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");

$id = mysqli_real_escape_string($conn,$_GET['id']);
$check = mysqli_query($conn, "SELECT * FROM book_inspection WHERE id='$id'");
$inspection = mysqli_fetch_array($check);
?>
                <div class="group-input">
                    <label class="text-dark">Comute Method</label>
                    <div class="box-custom-select">
                        <select class="form-select dmethod" name="method" style="height:45px" required>
                            <option value="">Select option</option>
                            <option selected><?php print $inspection['method'];?></option>
                            <option>Join Company Bus</option>
                            <option>Join convoy with your vehicle</option>
                            <option>Send Representative</option>
                           
                        </select>
                      
                    </div>
                    
                </div>
				

          
                <div class="group-input">
                     <label class="text-dark">Your Full Name</label>
                    <input type="text" placeholder="Your full names" value="<?php print $inspection['custnames'];?>" name="custnames" class="mynames" required />
                </div>
				
          
                <div class="group-input">
                     <label class="text-dark">Your Phone Number</label>
                    <input type="text" placeholder="Your phone number" value="<?php print $inspection['custphone'];?>" name="custphone" class="myphone" required />
                </div>
				
				 <div class="group-input">
                     <label class="text-dark">Inspection Days</label>
                    <div class="box-custom-select">
                        <select class="form-select dmethod" name="days" style="height:45px" required>
                            <option value="">Select days</option>
                            <option selected><?php print $inspection['days'];?></option>
                            <option>Monday</option>
                            <option>Tuesday</option>
                            <option>Wednessday</option>
                            <option>Thursday</option>
                            <option>Friday</option>
                            <option>Saturday</option>
                           
                        </select>
                      
                    </div>
                    
                </div>
				
				 <div class="group-input">
                     <label class="text-dark">Inspection Status</label>
                    <div class="box-custom-select">
                        <select class="form-select " name="inspstatus" style="height:45px" required>
                            <option value="">Select Status</option>
                            <option>Approved</option>
                            <option>Decline</option>
                            <option>Modified</option>
                            <option>Pending</option>
                          
                        </select>
                      
                    </div>
                    
                </div>
				
				
                <div class="group-input">
                     <label class="text-dark">Inspection Officer</label>
                    <input type="text" placeholder="officer in charge" name="officer" class="myphone" required value="<?php print $inspection['insp_officer'];?>" />
                </div>
				
				
                <div class="group-input" style="margin-bottom:100px">
                     <label class="text-dark">Notes to customer (optional)</label>
                    <input type="text" placeholder="If you have something to tell us" value="<?php print $inspection['notes'];?>" class="notez" name="notes">
                    <div class="tf-spacing-12"></div>
                   <input type="hidden" class="me" name="me" value="<?php print $id;?>" />
             
                </div>
               
                <div class="bottom-navigation-bar " style="position:sticky;bottom:10px;z-index:9">
               
                    <button type="submit" style="" class="tf-btn accent large">Submit Inspection Modification</button>
                 
                </div>
