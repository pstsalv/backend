<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
$propid = mysqli_real_escape_string($conn,$_GET['propid']);
$owner = mysqli_real_escape_string($conn,$_GET['owner']);

$checkus = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$owner'");
$users = mysqli_fetch_array($checkus);


$check =mysqli_query($conn, "SELECT id,userid,propertyid,amount,status,date,planid,propuid,payduration,initial_deposit,amt_due,propamount,amt_paid,amt_remain FROM myproperty WHERE userid='$owner' AND propuid='$propid'");
if(mysqli_num_rows($check)>0){
	while($payment = mysqli_fetch_array($check)){

		$chwho = mysqli_query($conn, "SELECT id,plan_name,amount,plancode,status,type,duration,amt_to_pay,location,plot_size,payplancode FROM payment_plan WHERE id='$payment[planid]'");

if(mysqli_num_rows($chwho)>0){
		$plan = mysqli_fetch_array($chwho);
		$myplanz = $plan['plan_name'];

	}else{
		$myplanz = ucwords(str_replace('-',' ',$payment['planid']));
	}

		$heckpr = mysqli_query($conn, "SELECT id,title,description,amount,images,location,status,date,provider,popular,prop_uid,estate_name,unit_available,plots,prop_category,prop_full_desc,canpay_installment,state,axis,promotitle,promocode,promogift,promoprice,type,propertycode,dailyones FROM property WHERE id='$payment[propertyid]'");
$property = mysqli_fetch_array($heckpr);
		?>
  <div class="food-box mb-3" style="width:100%">
                            <div class="img-box">
							<a href="#">
                                <img src="<?php print $kaylink;?>/pix/<?php print $property['images'];?>" alt="images" style="border-radius: 8px 8px 0 0;height:200px;object-fit:cover" /></a>
                                <span style="width:auto"><?php print $myplanz;?> Plan</span>
                            </div>
                            <div class="content bg-white">
                              <a href="#" class="critical_color fw_6">₦ <?php print number_format($property['amount'],2);?></a>

							  <h4><a href="#"><?php print $property['title'];?></a></h4>
                                <div class="rating mt-2">
                                    <div class="alert alert-info alert-xs p-1"><?php print $property['description'];?> </div>
                                   
                                </div>
                            </div>
                        </div>
<?php } }else{?>
	<div class="alert alert-info alert-xs">Property for #<?php print $propid;?> not found</div>
<?php } ?>


  <div class="tf-panel up " id="paynowbtn">
        <div class="panel_overlay"></div>
          <div class="panel-box panel-up wrap-panel-clear panel-change-profile">
            <div class="heading">
                <a data-dhref="/paybycard/<?php print $users['id'];?>/<?php print $users['fname'];?> <?php print $users['lname'];?>" href="#" class="custpaybtn">Pay with Card</a>
                <a data-dhref="/paybycash/<?php print $users['id'];?>/<?php print $users['fname'];?> <?php print $users['lname'];?>" href="#" class="custpaybtn">Cash Deposit</a>
                <a data-dhref="/recurring-payment/<?php print $users['id'];?>/<?php print $users['fname'];?> <?php print $users['lname'];?>" href="#" class="custpaybtn">Schedule Payment</a>
            </div>
            <div class="bottom">
                <a class="clear-panel" href="#">Dismiss</a>
            </div>
          </div>
    </div>