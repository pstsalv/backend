<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");
if(isset($_GET['me']) && $_GET['me'] !=null){
	$me = mysqli_real_escape_string($conn,$_GET['me']);
	
	$checkme = mysqli_query($conn, "SELECT id,email FROM users WHERE id='$me'");
	if(mysqli_num_rows($checkme)>0){
		$meinfo = mysqli_fetch_array($checkme);
	$myemail = $meinfo['email'];
	}else{
		$myemail = "nill";
	}
	
}else{
	$me = "no user selected";
	$myemail = "nill";
}

$id = mysqli_real_escape_string($conn,$_GET['id']);
		$heckpr = mysqli_query($conn, "SELECT * FROM property WHERE id='$id'");
$property = mysqli_fetch_array($heckpr);
		?>

<div class="header-style2" style="background: url(<?php print $kaylink;?>/pix/<?php print $property['images'];?>);
    background-position-x: 0%;
    background-position-y: 0%;
    background-repeat: repeat;
    background-size: auto;
  padding: 12px 0px 200px;
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center center;
  position: relative;">
        <div class="tf-container">
            <a href="#" class="back-btn  back"> <i class="icon-left"></i> </a>
       
        </div>
    </div>
    <div class="mb-8">
        <div class="app-section bg_white_color giftcard-detail-section-1">
        <div class="tf-container">
            <div class="voucher-info">
                <h2 class="fw_6"><?php print $property['title'];?></h2>
				
				<?php if($property['prop_category']=="Building"){?>
				<a href="#" class="critical_color fw_6">₦ <?php print number_format($property['amount'],2);?></a>
				<?php }else{?>
				<?php if($property['dailyones']!=""){?>
                <a href="#" class="critical_color fw_6">₦ <?php print $property['dailyones'];?> <?php print ucwords($property['canpay_installment']);?></a>
				<?php }else{?>
                <a href="#" class="critical_color fw_6">₦ <?php if($property['promocode']=="notapplicable" || $property['promocode']=="applicable"){ print number_format($property['amount'],2); }else{ print number_format($property['amount']*2,2);}?></a>
				<?php }}?>
            </div>
            <div class="mt-2">
			
			<?php if($property['prop_category']=="Building"){?>
			 <a href="#" class="note-voucher">Pay a deposit of ₦ <?php print $property['dailyones'];?></a>
			<?php }else{?>
			<?php if($property['dailyones']!=""){?>
                <a href="#" class="note-voucher">Pay as low as ₦ <?php print $property['dailyones'];?> <?php print ucwords($property['canpay_installment']);?></a>
			<?php }else{?>
			 <a href="#" class="note-voucher">Only Outright Payment is  Accepted</a>
			 <?php }?>
			 <?php }?>
			 
                <p class="mt-2 fw_4"> <?php if($property['popular']>0){?>More than <?php print number_format($property['popular']);?> customers subscribed to this property <?php }else{?> Limited Offer<?php }?></p>
            </div>
        </div>

        </div>
        <div class="app-section mt-1 bg_white_color giftcard-detail-section-2">
        <div class="tf-container">
            <div class="voucher-desc">
                <h4 class="fw_6">Property infomation</h4>
                <div class="mt-1"><?php print $property['description'];?></div>
              
            </div> 
        </div>

        </div>
        <div class="app-section mt-1 bg_white_color giftcard-detail-section-3">
        <div class="tf-container">
      
            <div class="address mt-3 d-flex justify-content-between">
                <i class="icon-location"></i>
                <p><?php print $property['location'];?></p>
                <a><i class="icon-send2"></i></a>
            </div>
               
        </div>

        </div>
        <div class="app-section mt-1 bg_white_color giftcard-detail-section-4">
        <div class="tf-container">
            <div class="mt-3 d-flex justify-content-between top">
             
                <div class="desc">
				<?php if($property['prop_category']=="Building"){?>
                    <p class="fw_4">Property Type</p>
					 <h4 class="fw_6"><?php print $property['prop_category'];?></h4>
				<?php }else{?>
                    <p class="fw_4">Plot Size</p>
                    <h4 class="fw_6"><?php print $property['plots'];?></h4>
					<?php }?>
                </div>
                <a href="/allproperty/" class="text-center fw_4 mt-3 mb-2"><?php if( $property['status']=="Public"){?><?php if($property['unit_available']>0){?> <?php print number_format($property['unit_available']);?> Units available <?php }else{?>Sold Out<?php }}else{?>Sold Out<?php }?></a>
            </div>
           
        </div>

        </div>

		<div class="app-section mt-1 bg_white_color giftcard-detail-section-4">
        <div class="tf-container">
            <div class="mt-3 d-flex justify-content-between top">
             
                <div class="desc">
                    <p class="fw_4">Estate Name</p>
                    <h4 class="fw_6"><?php print $property['estate_name'];?></h4>
                </div>
              
            </div>
           
        </div>

        </div>

		<div class="app-section mt-1 bg_white_color giftcard-detail-section-4">
        <div class="tf-container">
            <div class="mt-3 d-flex justify-content-between top">
             
                <div class="desc">
                    <p class="fw_4">Provided by</p>
                    <h4 class="fw_6"><?php print $property['provider'];?></h4>
                </div>
                <a href="/allproperty/" class="icon-right"></a>
            </div>
            <p class="text-center fw_4 mt-3 mb-2">This property is <?php if($property['unit_available']>0){ echo 'Available';}else{ echo 'Sold Out';} ?></p>
        </div>

        </div>
    </div>  
    <div class="bottom-navigation-bar bottom-btn-fixed">
        <div class="tf-container">
           <?php if($property['status']=="Public"){?>
           <?php if($property['unit_available']>0){?>
		   <a href="#" class="tf-btn accent large sheet-open buyerdetails" data-sheet=".propertyuser" style="display:none">Select Customer</a>
		   <a href="#" class="tf-btn accent large sheet-open subscribenow" data-sheet=".propertybuy">Subscribe/Buy</a>
		   <?php }else{?>
			<a href="#" class="tf-btn accent large " disabled>Sold Out</a>
		   <?php }?>
		   <?php }else{?>
<a href="javascript:alert('This property is no longer available, check other properties');" class="tf-btn accent large " disabled>Sold Out</a>
<?php }?>
        </div>
    </div>
	
	

<div class="sheet-modal propertybuy" style="height:90%;">
      <div class="sheet-modal-inner bg-light">
   <div class="header">
        <div class="tf-container">
            <div class="tf-statusbar d-flex justify-content-center align-items-center">
               
                <h3>Subscribe/Buy</h3>
            </div>
        </div>
    </div>
	<?php if($property['canpay_installment']=="outright"){?>
	<div class="text-center">
	<div class=" badge bg-danger">Only Outright is allowed for this property</div>	
	</div>
	<?php }else{?>
	<div class="text-center">
	<div class=" badge bg-danger">Pay  Daily for this property</div>	
	</div>
	<?php }?>
    <div class="content-by-bank mt-3 bg-light" style="height:100vh;overflow-y:scroll">

        <div class="tf-container">
            <div class="heading">
            
                <div class="tf-spacing-12"></div>
            </div>
             <form class="tf-form mt-1 startsub">
                 <div class="group-input custname" style="display:none">
                     <label class="bg-light border text-dark">Customer's Name</label>
					 <span class="btn-info btn sheet-open resetuser" data-sheet=".propertyuser" style="position:absolute;right:1px;height:51px;font-size:11px;padding:10px;line-height:12px">Change<br>Customer</span>
                    <input type="text" readonly value="" name="cname" class="customername" />
                  
                </div>
				
				<div class="group-input">
                     <label class="bg-light border text-dark">Property Title</label>
                    <input type="text" readonly value="<?php print $property['title'];?>" name="ptitle" />
                  
                </div>
				<div class="group-input">
                     <label class="bg-light border text-dark">Property UID</label>
                    <input type="text" readonly value="<?php print $property['prop_uid'];?>" name="puid" />
                  
                </div>
<?php if($property['dailyones']==""){ ?>
				<div class="group-input">
                     <label class="bg-light border text-dark">Coupon Code (optional)</label>
                    <input type="tel" class="couponcode" placeholder="50%-60% Discount Coupon" name="couponcode" <?php if($property['promocode']=="notapplicable"){?>readonly onclick="javascript:alert('Discount is already applied to this property');"<?php }?> />
                  
                </div>
				<?php }?>
				<?php if($property['promogift'] ==""){?>
				<div class="group-input">
                     <label class="bg-light border text-dark">Property Amount</label>
                    <input type="text" readonly value="₦ <?php if($property['dailyones']!=""){ print number_format($property['amount'],2); }else{ if($property['promocode']=="notapplicable" || $property['promocode']=="applicable"){ print number_format($property['amount'],2); }else{ print number_format($property['amount']*2,2);}} ?>" name="pamount" class="properamt" />
                  
                </div>
				
				<input type="hidden" name="propid" value="<?php print $property['id'];?>" />
				<input type="hidden" name="meemail" class="meemail" value="<?php print $myemail;?>" />
				<input type="hidden" name="me" class="buyerid" value="<?php print $me;?>" />
				<input type="hidden" name="amt_due" class="amt_due" value="0" />
				<input type="hidden" name="propertyamounnt" class="propertyamounnt" value="<?php if($property['dailyones']!=""){ print $property['amount']; }else{ if($property['promocode']=="notapplicable" || $property['promocode']=="applicable"){ print $property['amount']; }else{ print $property['amount']*2;}}?>" />
               
                <div class="group-input">
                     <label class="bg-light border text-dark">Select Payment Plan</label>
                    <select name="plan" id="forplans" required class="payplan" onchange="GetAmount2('forplans')">
					<option value="">Select option</option>
					
				
					<?php $chp=mysqli_query($conn, "SELECT * FROM payment_plan WHERE type='$property[canpay_installment]' AND payplancode='$property[propertycode]' AND location='$property[state]' ORDER BY amount ASC");
					while($plan=mysqli_fetch_array($chp)){
						if($plan['amt_to_pay']>0){
							$amttopay = $plan['amt_to_pay'];
						}else{
							$amttopay = $plan['amount'];
						}
						?>


<option value="<?php print $plan[0];?>" data-amount="<?php if($property['dailyones']!=""){ print $plan['amount']; }else{ if($property['promocode']=="notapplicable" || $property['promocode']=="applicable"){ print $property['amount']; }else{ print $property['amount']*2;}}?>" data-totalpay="₦ <?php if($plan['amt_to_pay']>0){ print number_format($plan['amt_to_pay'],2);}else{ print number_format($property['amount'],2); }?>"  data-plan="<?php print $plan['plan_name'];?>" data-duration="<?php print $plan['duration'];?>"><?php print $plan['plan_name'];?> - ₦ <?php if($property['dailyones']!=""){ print number_format($plan['amount']); }else{ if($property['promocode']=="notapplicable" || $property['promocode']=="applicable"){ print number_format($property['amount'],2); }else{ print number_format($property['amount']*2,2);}}?></option>
					<?php }?>
					
				
					</select>
                </div>
				<?php }else{?>
				<div class="row">
				<div class="col-6">
			
				
				<div class="group-input">
                     <label class="bg-light border text-dark">Property Amount</label>
                    <input type="text" readonly class="properamt" value="₦ <?php if($property['dailyones']!=""){ print number_format($property['amount'],2); }else{ if($property['promocode']=="notapplicable" || $property['promocode']=="applicable"){ print number_format($property['amount'],2); }else{ print number_format($property['amount']*2,2);}} ?>" name="pamount" />
                  
                </div>
				
				
				
				<input type="hidden" name="propid" value="<?php print $property['id'];?>" />
				<input type="hidden" name="meemail" class="meemail" value="<?php print $myemail;?>" />
				<input type="hidden" name="me" class="buyerid" value="<?php print $me;?>" />
				<input type="hidden" name="amt_due" class="amt_due" value="0" />
				<input type="hidden" name="propertyamounnt" class="propertyamounnt" value="<?php if($property['dailyones']!=""){ print $plan['amount']; }else{ if($property['promocode']=="notapplicable" || $property['promocode']=="applicable"){ print $property['amount']; }else{ print $property['amount']*2;}}?>" />
               
             


				<div class="group-input">
                     <label class="bg-light border text-dark">Choose Promo Gift</label>
                  
  <select name="giftname" required class="giftname">
<option value="" disabled>Select Gift</option>
<option value="nogift">No Gift (15%)</option>
<option value="rice">Rice Gift (10%)</option>
<option value="gen">Generator Gift (5%)</option>
</select>
                  
                </div>
				
				


                </div>
				
				
					<div class="col-6 group-input">
                     <label class="bg-light border text-dark">Promo Gift</label>
                   <img src="<?php print $kaylink;?>/pix/<?php print $property['promogift'];?>.png" style="height:120px;width:120px" alt="" class="col-4 promoselectaimg" />
                </div>
				
				
				   <div class="col-12 group-input">
                     <label class="bg-light border text-dark">Select Payment Plan</label>
                    <select name="plan" id="forplans" required class="payplan" onchange="GetAmount2('forplans')">
					<option value="">Select option</option>
					
				
					<?php $chp=mysqli_query($conn, "SELECT id,plan_name,amount,plancode,status,type,duration,amt_to_pay,location,plot_size,payplancode FROM payment_plan WHERE type='$property[canpay_installment]' AND payplancode='$property[propertycode]' AND location='$property[state]' ORDER BY amount ASC");
					while($plan=mysqli_fetch_array($chp)){
						if($plan['amt_to_pay']>0){
							$amttopay = $plan['amt_to_pay'];
						}else{
							$amttopay = $plan['amount'];
						}
						?>
				<option value="<?php print $plan[0];?>" data-amount="<?php if($property['dailyones']!=""){ print $plan['amount']; }else{ if($property['promocode']=="notapplicable" || $property['promocode']=="applicable"){ print $property['amount']; }else{ print $property['amount']*2;}}?>" data-totalpay="₦ <?php if($plan['amt_to_pay']>0){ print number_format($plan['amt_to_pay'],2);}else{ print number_format($property['amount'],2); }?>"  data-plan="<?php print $plan['plan_name'];?>" data-duration="<?php print $plan['duration'];?>"><?php print $plan['plan_name'];?> - ₦ <?php if($property['dailyones']!=""){ print number_format($plan['amount']); }else{ if($property['promocode']=="notapplicable" || $property['promocode']=="applicable"){ print number_format($property['amount'],2); }else{ print number_format($property['amount']*2,2);}}?></option>

					<?php }?>
					
				
					</select>
                </div>

				
                </div>
				<?php }?>
				
				
				
				<div class="row pb-4">
				<div class="col-6 group-input">
                     <label class="bg-light border text-dark">Payment Duration</label>
                     <input type="text" readonly placeholder="Select payment plan first" name="payduration" class="payduration" />
                </div>
				
                <div class="col-6 group-input input-field input-money" style="margin-bottom:100px">
                     <label class="bg-light border text-dark">Initial Payment</label>
                   
<?php if($property['prop_category']=="Building"){?>
 <input type="text" required readonly class="search-field value_input st1 bg-white lo" type="text" name="amountv" value="<?php print $property['dailyones'];?>">

 <input type="hidden" class="search-field value_input st1 bg-white hid initialpay" type="text" name="amount" value="0">

<?php }else{?>
 <input type="tel" value="0" required readonly class="search-field value_input st1 bg-white initialpay hi" type="text" name="amount">
<?php }?>
                    <span class="icon-clear"></span>
                 
                </div>
                </div>
               
               <div style="margin-bottom:110px"><hr></div>
                <div class="bottom-navigation-bar bottom-btn-fixed st2 bg-light">
                    <button type="submit" class="tf-btn accent large probtn">Proceed</button>
                </div>

             </form>

        </div>
    </div>
    
  
       
      </div>
    </div>
	
	
<?php
if(isset($_GET['agent']) && $_GET['agent'] !=null){
	$agent = mysqli_real_escape_string($conn,$_GET['agent']);
?>

<div class="sheet-modal propertyuser" style="height:80%;overflow-y:scroll">
      <div class="sheet-modal-inner bg-light">
   <div class="header">
        <div class="tf-container">
            <div class="tf-statusbar d-flex justify-content-center align-items-center">
               
                <h3>Select Customer</h3>
            </div>
        </div>
    </div>
    <div class="content-by-bank mt-2 bg-light" style="height:100%;overflow-y:scroll">
        <div class="tf-container" style="margin-bottom:100px">
            <div class="heading">
            
                <div class="tf-spacing-12"></div>
            </div>
             
			 <?php
$check =mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE agent='$agent' OR account_no='$agent' ORDER BY id DESC");
if(mysqli_num_rows($check)>0){
	while($users = mysqli_fetch_array($check)){
		if($users['status'] =="active"){
			$status = "success_color";
		}else{
			$status = "critical_color";
		}
if($users['agent']==$agent){
$acctype = "My Client";
}elseif($users['account_no']==$agent){
$acctype = "Assigned Client";
}else{
$acctype = "Assigned/Direct";
}
		?>
                            <a class="tf-trading-history bg-white p-2 shadow-xs <?php print $status;?> pickuser sheet-open border" data-id="<?php print $users['id'];?>" data-propertyid="<?php print $property['id'];?>" data-cname="<?php print $users['fname'].' '.$users['lname'];?>" href="#" style="border-radius:5px"  data-sheet=".propertybuy">
                                <div class="inner-left">
                                  
									<div class="thumb">
                                        <img src="<?php print $kaylink;?>/pix/thumb/<?php print $users['pix'];?>" alt="image">
                                    </div>
                                    <div class="content">
                                        <h4><?php print ucwords($users['fname'].' '.$users['lname']);?></h4>
                                        <p><span class="badge bg-danger px-2" style="line-height:7px"><?php print $acctype;?></span> <?php print $users['region'];?>, <?php print $users['state'];?> </p>
                                    </div>
                                </div>
                                <span class="num-val "><input type="checkbox" name="pick"></span>
                            </a>
<?php } }else{ ?>
<div class="alert alert-info">You need to register a customer first</div>
<?php }?>
        </div>
    </div>
    
  
       
      </div>
    </div>
<?php }?>


  <div class="tf-panel up " id="paynowbtn2">
        <div class="panel_overlay"></div>
          <div class="panel-box panel-up wrap-panel-clear panel-change-profile">
            <div class="heading">
			<?php
			$checku = mysqli_query($conn, "SELECT id,accname,accno,bankname,userid,date,customer_code,customer_id FROM banks WHERE userid='$id'");
if(mysqli_num_rows($checku)>0){?>
                <a data-dhref="" href="#" class="custpaybtn pybank">Pay to Bank</a>
<?php }else{?>			
                <a data-dhref="" href="#" class="custpaybtn pybank2">Pay to Bank</a>
<?php }?>				
                <a data-dhref="" href="#" class="custpaybtn pycard">Pay with Card</a>
                <a data-dhref="" href="#" class="custpaybtn pycash">Cash Deposit</a>
                <a data-dhref="" href="#" class="custpaybtn pyschedule">Schedule Payment</a>
            </div>
            <div class="bottom">
                <a class="clear-panel" href="#">Dismiss</a>
            </div>
          </div>
    </div>
	