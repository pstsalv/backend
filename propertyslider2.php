<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
$propuid = mysqli_real_escape_string($conn,$_GET['propuid']);
//check my details

$checkme = mysqli_query($conn, "SELECT id,fname,lname,email,phone FROM users WHERE id='$me'");
$mydetails = mysqli_fetch_array($checkme);

$check =mysqli_query($conn, "SELECT * FROM myproperty WHERE userid='$me' AND propertyid='$propuid' ORDER BY id DESC LIMIT 1");

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
						$checkbank = mysqli_query($conn, "SELECT * FROM banks WHERE acc_email='$property[payment_email]'");
						if(mysqli_num_rows($checkbank)>0){
						$propbank = mysqli_fetch_array($checkbank);
						?>
					 <h1 class="text-center"><?php print $propbank['accno'];?></h1>
					 <i class="icon-copy1 copydiz" value="<?php print $propbank['accno'];?>" style="position:absolute;right:30px;font-size:20px"></i>
                        <div class="text-dark text-center" style="font-size:19px"><?php print $propbank['bankname'];?></div>
                        <div class="text-dark text-center"><?php print $propbank['accname'];?></div>
						
							<?php }else{?>
					
					 <div class="alert alert-danger p-1 m-1 text-center">A new payment email is required to create <br>virtual account for this property</div>
				<a href="#" class="tf-btn bg-info large sheet-open clickme" data-sheet=".createnewvirt_<?php print $property['id'];?>">Create New Virtual Account</a>
					
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
				<input type="hidden" name="me" class="me" />
				<input type="hidden" name="propertyuid" value="<?php print $property['propertyid'];?>" />
				
<input type="hidden" name="proptype" class="proptype" value="<?php print $property['logistics'];?>" />



                <div class="group-input">
                    <label>Email Address</label>
                    <input type="email" placeholder="Aa" value="<?php print $property['id'];?><?php print $mydetails['id'];?><?php print $mydetails['email'];?>" class="myemail" name="email" required />
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
  
  
  
	<?php
} }
	?>