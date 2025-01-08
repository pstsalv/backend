<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
//check my details

$checkme = mysqli_query($conn, "SELECT id,fname,lname,email,phone FROM users WHERE id='$me'");
$mydetails = mysqli_fetch_array($checkme);

$check =mysqli_query($conn, "SELECT * FROM myproperty WHERE userid='$me' ORDER BY id DESC");

if(mysqli_num_rows($check)>0){
	$count = 0;
	$total = mysqli_num_rows($check);
	while($property = mysqli_fetch_array($check)){
		$count = $count+1;
		
		  $checkamtpp = mysqli_query($conn,"SELECT SUM(amount) AS amttpaid FROM payment WHERE userid='$me' AND paidfor='$property[propertyid]' AND status='approved'");
$rowpp = mysqli_fetch_assoc($checkamtpp);
$sumppm = $rowpp['amttpaid'];
$totalpayd = "$sumppm";
if($totalpayd!=""){
$mybalp = $totalpayd;
}else{
	$mybalp = 0;
}
$outstad = "$property[propamount]"-$mybalp;


?>

        <div class="tf-container swiper-slide" style="margin-left:0px !important;margin-right:5px !important;width:100%" data-propid="<?php print $property['propertyid'];?>">
            <div class="tf-balance-box">
                <div class="balance">
                    <div class="row">
                        <div class="col-6 br-right">
						<a href="/cleared-payment/">
                            <div class="inner-left">
                                <p>Property UID:</p>
                                <h3 class="text-danger ">#<?php print $property['propuid'];?></h3>
                            </div>
							</a>
                        </div>
                        <div class="col-6">
						<a href="/myproperty/">
                            <div class="inner-right">
                                <p>Subscription</p>
                                <h3 class="text-secondary"><?php print ucwords($property['type']);?>
                                </h3>
							
<?php if($property['logistics']=="Building"){ print ucwords($property['logistics']);}else{ print "Land Purchase";} ?>
                            </div>
							</a>
                        </div>
                    </div>
                </div>
				
				 <div class="balance">
                    <div class="row">
                        <div class="col-6 br-right">
						<a href="/cleared-payment/">
                            <div class="inner-left">
                                <p>Payment to Bank:</p>
                                <h3 class="text-success">₦ <?php print number_format($mybalp);?></h3>
                            </div>
							</a>
                        </div>
                        <div class="col-6">
						<a href="/myproperty/">
                            <div class="inner-right">
                                <p>Outstanding</p>
                                <h3 class="text-warning">
                                 ₦ <?php print number_format($outstad);?>
                               
                                </h3>
                            </div>
							</a>
                        </div>
                    </div>
                </div>
				
				<input type="hidden" value=" <?php print $count;?> of <?php print number_format($total);?>"/>
                <div class="wallet-footer">
                    <div class="justify-content-between align-items-center">
                    <?php if($property['payment_email']!=''){
						$checkbank = mysqli_query($conn, "SELECT accname,accno,bankname,userid,acc_email FROM banks WHERE acc_email='$property[payment_email]'");
						if(mysqli_num_rows($checkbank)>0){
						$propbank = mysqli_fetch_array($checkbank);
						?>
					 <h1 class="text-center"><?php print $propbank['accno'];?></h1>
					 <i class="icon-copy1 copydiz" style="position:absolute;right:30px;font-size:20px"></i>
                        <div class="text-dark text-center" style="font-size:19px">Wema Bank Plc</div>
                        <div class="text-dark text-center"><?php print $propbank['accname'];?></div>
						
						<?php }else{?>
					
					 <div class="alert alert-danger p-1 m-1 text-center">A new payment email is required to create virtual <br>account for this property</div>
				<a href="#" class="tf-btn bg-info large sheet-open clickme" data-sheet=".createnewvirt_<?php print $property['id'];?>">Create Virtual Account</a>
					
					<?php }?>

				<a href="#" class="tf-btn bg-info large sheet-open clickme" data-sheet=".createnewvirt_<?php print $property['id'];?>">Add New Virtual Account</a>
					
					<?php }else{?>
					
					 <div class="alert alert-danger p-1 m-1 text-center">A new payment email is required to create virtual <br>account for this property</div>
				<a href="#" class="tf-btn bg-info large sheet-open clickme" data-sheet=".createnewvirt_<?php print $property['id'];?>">Create Virtual Account</a>
					
					<?php }?>
                    </div>
                </div>
				
				
            </div>
        </div>




<div class="sheet-modal genslider createnewvirt_<?php print $property['id'];?>" style="height:100%;background:transparent">
      <div class="sheet-modal-inner" style="background:transparent">
 <div class="tf-panel up panel-open">
   
        
		  <div class="panel-box panel-up wrap-panel-clear panel-change-profile">
            <div class="heading bg-white loadvirtualresult" style="padding:5px">
			 <div class="alert alert-danger p-1 m-1 text-center">A new payment email is required to create virtual <br>account for this property</div>
          <form class="tf-form mt-5 createva2" style="margin-bottom:100px">
                  <div class="group-double-ip">
                    <div class="group-input">
                        <label>First Name</label>
                        <div class="datepicker date">
                            <input type="text" placeholder="First Name" value="<?php print $mydetails['fname'];?>" class="fname" name="fname" required />
                            <span class="input-group-append"><i class="icon-user"></i></span>
                        </div>
                    </div>
                    <div class="group-input">
                        <label>Last Name</label>
                        <input type="text" placeholder="Last Name" value="<?php print $mydetails['lname'];?>" class="lname" required name="lname" />
                       
                    </div>
                </div>
				<input type="hidden" name="me" class="me" value="<?php print $me;?>" />
				<input type="hidden" name="proptype" class="proptype" value="<?php print $property['logistics'];?>" />
				<input type="hidden" name="propertyuid" value="<?php print $property['propertyid'];?>" />
				
                <div class="group-input">
                    <label>Email Address</label>
                    <input type="email" placeholder="Aa" value="<?php print $property['id'];?><?php print $mydetails['id'];?><?php print $mydetails['email'];?>" class="myemail" name="email" required />
                    <div class="credit-card">
                        <i class="icon-email"></i>
                    </div>
    
                </div>
                <div class="group-input" >
                    <label for="">Phone Number</label>
                    <input type="tel" class="myfone" name="phone" required value="<?php print $mydetails['phone'];?>" />
					 <div class="credit-card">
                        <i class="icon-phone"></i>
                    </div>
                </div>
				
				  
			  <div class="group-input" style="padding-bottom:35px">
                    <label for="">Select Prefered Bank</label>
                     <select name="prefbank" required class="form-select">
					<option value="">Select Prefered Bank</option>
					<option value="wema-bank">Wema Bank</option>
					<option value="titan-paystack">Paystack-Titan</option>
					</select>
					 <div class="credit-card">
                        <i class="icon-phone"></i>
                    </div>
                </div>
              
                <div class="bottom-navigation-bar st2 mb-2">
                    <button type="submit" class="tf-btn accent large" id="btn-popup-down">Create Virtual Account</button>
					
					<div class="bottom" style="margin-top:10px;margin-bottom:10px">
                <a class=" sheet-close" style="border:1px solid black;border-radius:5px" href="#">Dismiss</a>
            </div>
                </div>


             </form>
          </div>
		  
    </div>
	
	
  </div>
</div>
  </div>
  
  
  
	<?php
}
	?>
	
<?php
 $checkfees = mysqli_query($conn,"SELECT SUM(propamount) AS amttpaidp FROM myproperty WHERE userid='$me' AND status='ongoing'");
$rowppp = mysqli_fetch_assoc($checkfees);
$sumppmp = $rowppp['amttpaidp'];
$totalpaydp = "$sumppmp";
if($totalpaydp!=""){
$mybalpp = $totalpaydp;
}else{
	$mybalpp = 0;
}

$tenpers = "$mybalpp"*0.1;
?>	
		<div class="tf-container swiper-slide" style="margin-left:0px !important;margin-right:5px !important;width:100%" data-propid="documentation">
            <div class="tf-balance-box">
                <div class="balance">
                    <div class="row">
                        <div class="col-12">
						<a href="/cleared-payment/">
                            <div class="inner-left">
                                <p>Documentation Fees:</p>
                                <h3 class="text-danger ">10% of Property Amount</h3>
                            </div>
							</a>
                        </div>
                       
                    </div>
                </div>
				
				 <div class="balance">
                    <div class="row">
                        <div class="col-6 br-right">
						<a href="/cleared-payment/">
                            <div class="inner-left">
                                <p>Charged Fees:</p>
                                <h3 class="text-success">₦ <?php print number_format($tenpers);?></h3>
                            </div>
							</a>
                        </div>
                      <div class="col-6">
						<a href="/myproperty/">
                            <div class="inner-right">
                                <p>Total Subscription</p>
                                <h3 class="text-secondary"><?php print number_format($total);?> properties
                                </h3>
                            </div>
							</a>
                        </div>
                    </div>
                </div>
				
				<div class="wallet-footer">
                    <div class="justify-content-between align-items-center">
                    <?php
						$checkbankk = mysqli_query($conn, "SELECT accname,accno,bankname,userid,acc_email FROM banks WHERE userid='$me' AND pay_type='documentation'");
						if(mysqli_num_rows($checkbankk)>0){
						$propban = mysqli_fetch_array($checkbankk);
						?>
					 <h1 class="text-center"><?php print $propban['accno'];?></h1>
					 <i class="icon-copy1 copydiz" style="position:absolute;right:30px;font-size:20px"></i>
                        <div class="text-dark text-center" style="font-size:19px">Wema Bank Plc</div>
                        <div class="text-dark text-center"><?php print $propban['accname'];?></div>
						
						<?php }else{?>
					
					 <div class="alert alert-danger p-1 m-1 text-center">A new payment email is required to create virtual <br>account for this payment</div>
				<a href="#" class="tf-btn bg-info large sheet-open clickme" data-sheet=".createnewvirt_documentation">Create Virtual Account</a>
					
					<?php }?>
					
                    </div>
                </div>
				
				
            </div>
        </div>
		
		
		
		

<div class="sheet-modal genslider createnewvirt_documentation" style="height:100%;background:transparent">
      <div class="sheet-modal-inner" style="background:transparent">
 <div class="tf-panel up panel-open">
   
        
		  <div class="panel-box panel-up wrap-panel-clear panel-change-profile">
            <div class="heading bg-white loadvirtualresult" style="padding:5px">
			 <div class="alert alert-danger p-1 m-1 text-center">A new payment email is required to create virtual <br>account for documentation fees</div>
          <form class="tf-form mt-5 createva2" style="margin-bottom:100px">
                  <div class="group-double-ip">
                    <div class="group-input">
                        <label>First Name</label>
                        <div class="datepicker date">
                            <input type="text" placeholder="First Name" value="<?php print $mydetails['fname'];?>" class="fname" name="fname" required />
                            <span class="input-group-append"><i class="icon-user"></i></span>
                        </div>
                    </div>
                    <div class="group-input">
                        <label>Last Name</label>
                        <input type="text" placeholder="Last Name" value="<?php print $mydetails['lname'];?>" class="lname" required name="lname" />
                       
                    </div>
                </div>
				<input type="hidden" name="me" class="me"  value="<?php print $me;?>" />
				<input type="hidden" name="propertyuid" value="documentation" />
				
                <div class="group-input">
                    <label>Email Address</label>
                    <input type="email" placeholder="Aa" value="documentation_<?php print $mydetails['email'];?>" class="myemail" name="email" required />
                    <div class="credit-card">
                        <i class="icon-email"></i>
                    </div>
    
                </div>
                <div class="group-input" style="padding-bottom:30px">
                    <label for="">Phone Number</label>
                    <input type="tel" class="myfone" name="phone" required value="<?php print $mydetails['phone'];?>" />
					 <div class="credit-card">
                        <i class="icon-phone"></i>
                    </div>
                </div>
              
                <div class="bottom-navigation-bar st2 mb-2">
                    <button type="submit" class="tf-btn accent large" id="btn-popup-down">Create Virtual Account</button>
					
					<div class="bottom" style="margin-top:10px;margin-bottom:10px">
                <a class=" sheet-close" style="border:1px solid black;border-radius:5px" href="#">Dismiss</a>
            </div>
                </div>


             </form>
          </div>
		  
    </div>
	
	
  </div>
</div>
  </div>



<?php
 $checksms = mysqli_query($conn,"SELECT SUM(amount_charged) AS smsamt FROM smscharges WHERE userid='$me'");
$rowsms = mysqli_fetch_assoc($checksms);
$sumsms = $rowsms['smsamt'];
$totalsms = "$sumsms";
if($totalsms!=""){
$mysms = "$totalsms"+7000;
}else{
	$mysms = 0;
}
?>

  <div class="tf-container swiper-slide" style="margin-left:0px !important;margin-right:5px !important;width:100%" data-propid="sms">
            <div class="tf-balance-box">
                <div class="balance">
                    <div class="row">
                        <div class="col-12">
						<a href="/cleared-payment/">
                            <div class="inner-left">
                                <p>SMS Charges for:</p>
                                <h3 class="text-danger ">Transaction, Notification, Reminders</h3>
                            </div>
							</a>
                        </div>
                       
                    </div>
                </div>
				
				 <div class="balance">
                    <div class="row">
                        <div class="col-6 br-right">
						<a href="/cleared-payment/">
                            <div class="inner-left">
                                <p>Charged Fees:</p>
                                <h3 class="text-success">₦ <?php print number_format($mysms);?></h3>
                            </div>
							</a>
                        </div>
                      <div class="col-6">
						<a href="/myproperty/">
                            <div class="inner-right">
                                <p>Total Subscription</p>
                                <h3 class="text-secondary"><?php print number_format($total);?> properties
                                </h3>
                            </div>
							</a>
                        </div>
                    </div>
                </div>
				
				<div class="wallet-footer">
                    <div class="justify-content-between align-items-center">
                    <?php
						$checkbankk = mysqli_query($conn, "SELECT accname,accno,bankname,userid,acc_email FROM banks WHERE userid='$me' AND pay_type='sms'");
						if(mysqli_num_rows($checkbankk)>0){
						$propban = mysqli_fetch_array($checkbankk);
						?>
					 <h1 class="text-center"><?php print $propban['accno'];?></h1>
					 <i class="icon-copy1 copydiz" style="position:absolute;right:30px;font-size:20px"></i>
                        <div class="text-dark text-center" style="font-size:19px"><?php print $propban['bankname'];?></div>
                        <div class="text-dark text-center"><?php print $propban['accname'];?></div>
						
						<?php }else{?>
					
					 <div class="alert alert-danger p-1 m-1 text-center">A new payment email is required to create virtual <br>account for this payment</div>
				<a href="#" class="tf-btn bg-info large sheet-open clickme" data-sheet=".createnewvirt_sms">Create Virtual Account</a>
					
					<?php }?>
					
                    </div>
                </div>
				
				
            </div>
        </div>
		
		
		
		

<div class="sheet-modal genslider createnewvirt_sms" style="height:100%;background:transparent">
      <div class="sheet-modal-inner" style="background:transparent">
 <div class="tf-panel up panel-open">
   
        
		  <div class="panel-box panel-up wrap-panel-clear panel-change-profile">
            <div class="heading bg-white loadvirtualresult" style="padding:5px">
			 <div class="alert alert-danger p-1 m-1 text-center">A new payment email is required to create virtual <br>account for sms charges</div>
          <form class="tf-form mt-5 createva2" style="margin-bottom:100px">
                  <div class="group-double-ip">
                    <div class="group-input">
                        <label>First Name</label>
                        <div class="datepicker date">
                            <input type="text" placeholder="First Name" value="<?php print $mydetails['fname'];?>" class="fname" name="fname" required />
                            <span class="input-group-append"><i class="icon-user"></i></span>
                        </div>
                    </div>
                    <div class="group-input">
                        <label>Last Name</label>
                        <input type="text" placeholder="Last Name" value="<?php print $mydetails['lname'];?>" class="lname" required name="lname" />
                       
                    </div>
                </div>
				<input type="hidden" name="me" class="me"  value="<?php print $me;?>" />
				<input type="hidden" name="propertyuid" value="sms" />
				
                <div class="group-input">
                    <label>Email Address</label>
                    <input type="email" placeholder="Aa" value="sms_<?php print $mydetails['email'];?>" class="myemail" name="email" required />
                    <div class="credit-card">
                        <i class="icon-email"></i>
                    </div>
    
                </div>
                <div class="group-input">
                    <label for="">Phone Number</label>
                    <input type="tel" class="myfone" name="phone" required value="<?php print $mydetails['phone'];?>" />
					 <div class="credit-card">
                        <i class="icon-phone"></i>
                    </div>
                </div>
              
			  <div class="group-input" style="padding-bottom:35px">
                    <label for="">Select Prefered Bank</label>
                     <select name="prefbank" required class="form-select">
					<option value="">Select Prefered Bank</option>
					<option value="wema-bank">Wema Bank</option>
					<option value="titan-paystack">Paystack-Titan</option>
					</select>
					 <div class="credit-card">
                        <i class="icon-phone"></i>
                    </div>
                </div>
              
                <div class="bottom-navigation-bar st2 mb-2">
                    <button type="submit" class="tf-btn accent large" id="btn-popup-down">Create Virtual Account</button>
					
					<div class="bottom" style="margin-top:10px;margin-bottom:10px">
                <a class=" sheet-close" style="border:1px solid black;border-radius:5px" href="#">Dismiss</a>
            </div>
                </div>


             </form>
          </div>
		  
    </div>
	
	
  </div>
</div>
  </div>
<?php }?>	