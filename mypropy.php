<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");
$me = mysqli_real_escape_string($conn,$_GET['me']);
$check =mysqli_query($conn, "SELECT id,userid,propertyid,amount,status,date,planid,propuid,payduration,initial_deposit,amt_due,propamount,amt_paid,amt_remain FROM myproperty WHERE userid='$me' ORDER BY id DESC LIMIT 1");
if(mysqli_num_rows($check)>0){
	while($payment = mysqli_fetch_array($check)){

		$chwho = mysqli_query($conn, "SELECT id,plan_name,amount,plancode,status,type,duration,amt_to_pay,location,plot_size,payplancode FROM payment_plan WHERE id='$payment[planid]'");
if($chwho && mysqli_num_rows($chwho)>0){
$plan = mysqli_fetch_array($chwho);
$myplan = $plan['plan_name'];
}else{
	$myplan = ucwords(str_replace('-',' ',$payment['planid']));
}

		$heckpr = mysqli_query($conn, "SELECT id,title,description,amount,images,location,status,date,provider,popular,prop_uid,estate_name,unit_available,plots,prop_category,prop_full_desc,canpay_installment,state,axis,promotitle,promocode,promogift,promoprice,type,propertycode,dailyones FROM property WHERE id='$payment[propertyid]'");
$property = mysqli_fetch_array($heckpr);
		?>
  <div class="food-box mb-3" style="width:100%">
                            <div class="img-box">
							<a href="/propdetails/<?php print $payment[2];?>">
                                <img src="<?php print $kaylink;?>/pix/<?php print $property['images'];?>" alt="images" style="border-radius: 8px 8px 0 0;height:200px;object-fit:cover" /></a>
                                <span style="width:auto"><?php print $myplan;?></span>
                            </div>
<a href=""><div class="px-2 bg-primary text-white small">Change Payment Settings</div></a>
                            <div class="content bg-white border">
                              <a href="#" class="critical_color fw_6">₦ <?php print number_format($payment['propamount'],2);?></a>
<a href="#" style="position:absolute;right:25px" data-propid="<?php print $payment['id'];?>" class="deleteprop alert alert-danger alert-xs p-0 btn text-center"><i class="icon-recycle-bin" style="font-size:15px"></i></a>

							  <h4><a href="/propdetails/<?php print $payment[2];?>"><?php print $property['title'];?></a></h4>
                                <div class="rating mt-2">
                                    <div class="alert alert-info alert-xs p-1"><?php print substr($property['description'],0,110);?> </div>
                                   
                                </div>
 
                            </div>
                        </div>
<?php } } ?>

<div class="tab-gift-item">
<?php
$check2 =mysqli_query($conn, "SELECT id,userid,propertyid,amount,status,date,planid,propuid,payduration,initial_deposit,amt_due,propamount,amt_paid,amt_remain FROM myproperty WHERE userid='$me' ORDER BY id DESC LIMIT 1,200");
if(mysqli_num_rows($check2)>0){
	while($payment2 = mysqli_fetch_array($check2)){

		$chwho2 = mysqli_query($conn, "SELECT id,plan_name,amount,plancode,status,type,duration,amt_to_pay,location,plot_size,payplancode FROM payment_plan WHERE id='$payment2[planid]'");
if($chwho2 && mysqli_num_rows($chwho2)>0){
$plan2 = mysqli_fetch_array($chwho2);
$myplan2 = $plan2['plan_name'];
}else{
	$myplan2 = ucwords(str_replace('-',' ',$payment2['planid']));
}

		$heckpr2 = mysqli_query($conn, "SELECT id,title,description,amount,images,location,status,date,provider,popular,prop_uid,estate_name,unit_available,plots,prop_category,prop_full_desc,canpay_installment,state,axis,promotitle,promocode,promogift,promoprice,type,propertycode,dailyones FROM property WHERE id='$payment2[propertyid]'");
$property2 = mysqli_fetch_array($heckpr2);
		?>
  <div class="food-box mb-3" id="myprop_<?php print $payment2['id'];?>">
                          <div class="img-box">
							<a href="/propdetails/<?php print $payment2[2];?>">
                                <img src="<?php print $kaylink;?>/pix/<?php print $property2['images'];?>" alt="images" style="border-radius: 8px 8px 0 0;height:200px;object-fit:cover" /></a>
                                <span style="width:auto"><?php print $myplan2;?></span>
                            </div>
<a href=""><div class="px-2 bg-primary text-white small">Change Payment Settings</div></a>
                            <div class="content bg-white border">
                              <a href="#" class="critical_color fw_6">₦ <?php print number_format($payment2['propamount'],2);?></a>

							  <h4><a href="/propdetails/<?php print $payment2[2];?>"><?php print $property2['title'];?></a></h4>
                                <div class="rating mt-2">
                                   <a href="/propdetails/<?php print $payment2[2];?>" class="alert alert-info alert-xs p-1 btn text-center w-100" style="font-size:12px">View Details </a>
								   
                                   <a href="#" data-propid="<?php print $payment2['id'];?>" class="deleteprop alert alert-danger alert-xs p-0 btn text-center"><i class="icon-recycle-bin" style="font-size:15px"></i></a>
                                </div>
                            </div>
                        </div>
<?php } } ?>
                   
                        
                    </div>