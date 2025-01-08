<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$id = mysqli_real_escape_string($conn,$_GET['custid']);
$check = mysqli_query($conn,"SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$id'");
$customer = mysqli_fetch_array($check);

$checkbank = mysqli_query($conn, "SELECT id,accname,accno,bankname,userid,date,customer_code,customer_id FROM banks WHERE userid='$id'");
if(mysqli_num_rows($checkbank)>0){
	$bank = mysqli_fetch_array($checkbank);
		?>
<div class="alert alert-success p-2"><i class="icon-info" style="font-size:19px;position:absolute;margin-top:3px;"></i><h4 style="padding-left:20px"> Notice</h4> Customer account already generated below. Tap on the details to copy it. You can send it to the customer to save for future payment to Bliss Legacy LTD</div>


  <div class="my-3 tf-card-block">
  <a href="#" class=" d-flex align-items-center justify-content-between copydiz" value="<?php print $bank['accno'];?>">
                <div class="inner d-flex align-items-center" style="gap:5px">
                    <i class="logo icon-wallet-filled-money-tool"></i>
                    <div class="content">
                        <h4><?php print $bank['bankname'];?></h4>
                        <p class=""><?php print ucwords(strtolower($bank['accname']));?></p>
                        <p class=""><?php print $bank['accno'];?></p>
                    </div>
                </div>
                
			</a>
            </div>
<?php }else{?>		
			
<div class="box-user mb-5">
            <div class="inner d-flex flex-column align-items-center justify-content-center">
                <div class="box-avatar">
                    <img src="<?php print $kaylink?>/pix/thumb/<?php print $customer['pix'];?>" alt="image" class="mypixc" onclick="javascript:alert('Edit your details from profile settings');" />
                </div>
                <div class="info">
                    <h2 class="fw_8 mt-3 text-center myname2c"><?php print $customer['fname'];?> <?php print $customer['lname'];?></h2>
                    <p>Edit Profile <i class="icon-edit"></i></p>
                </div>
            </div>
            
              
        </div>
             <form class="tf-form mt-5 createva">
                  <div class="group-double-ip">
                    <div class="group-input">
                        <label class="text-dark">First Name</label>
                        <div class="datepicker date">
                            <input type="text" placeholder="First Name" class="fnamec" value="<?php print $customer['fname'];?>" name="fname" required readonly onclick="javascript:alert('Edit your details from profile settings');" />
                            <span class="input-group-append"><i class="icon-user"></i></span>
                        </div>
                    </div>
                    <div class="group-input">
                        <label class="text-dark">Last Name</label>
                        <input type="text" placeholder="Last Name"  value="<?php print $customer['lname'];?>" class="lnamec" required name="lname" readonly onclick="javascript:alert('Edit your details from profile settings');" />
                       
                    </div>
                </div>
				<input type="hidden" name="me" value="<?php print $customer['id'];?>" class="mec" />
				
                <div class="group-input">
                    <label class="text-dark">Email Address</label>
                    <input type="email" placeholder="Aa" class="myemailc" name="email" required value="<?php print $customer['email'];?>" readonly onclick="javascript:alert('Edit your details from profile settings');" />
                    <div class="credit-card">
                        <i class="icon-email"></i>
                    </div>
    
                </div>
                <div class="group-input">
                    <label class="text-dark" for="">Phone Number</label>
                    <input type="tel" class="myfonec" name="phone" required readonly value="<?php print $customer['phone'];?>" onclick="javascript:alert('Edit your details from profile settings');" />
					 <div class="credit-card">
                        <i class="icon-phone"></i>
                    </div>
                </div>
              
                <div class="bottom-navigation-bar bottom-btn-fixed st2">
                    <button type="submit" class="tf-btn accent large" id="btn-popup-down">Create Virtual Account</button>
                </div>

             </form>
<?php }?>