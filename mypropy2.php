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
							<a href="/propdetails/<?php print $payment[2];?>/<?php print $me;?>">
                                <img src="<?php print $kaylink;?>/pix/<?php print $property['images'];?>" alt="images" style="border-radius: 8px 8px 0 0;height:200px;object-fit:cover" /></a>
                                <span style="width:auto"><?php print $myplan;?></span>
                            </div>
                            <div class="content bg-white">
                              <a href="#" class="critical_color fw_6">₦ <?php print number_format($property['amount'],2);?></a>

							  <h4><a href="/propdetails/<?php print $payment[2];?>/<?php print $me;?>"><?php print $property['title'];?></a></h4>
                                <div class="rating mt-2">
                                    <div class="alert alert-info alert-xs p-1"><?php print substr($property['description'],0,110);?>.. </div>
                                   
                                </div>
								<a style="width:100%" href="/propdetails/<?php print $payment[2];?>/<?php print $me;?>" class="btn btn-primary">Pay Now</a>
                            </div>
							
                        </div>
<?php } }else{ ?>


<?php }?>
<div class="tab-gift-item">
<?php
$check2 =mysqli_query($conn, "SELECT id,userid,propertyid,amount,status,date,planid,propuid,payduration,initial_deposit,amt_due,propamount,amt_paid,amt_remain FROM myproperty WHERE userid='$me' ORDER BY id DESC LIMIT 1,6000");
if(mysqli_num_rows($check2)>0){
	while($payment1 = mysqli_fetch_array($check2)){

		$chwho1 = mysqli_query($conn, "SELECT id,plan_name,amount,plancode,status,type,duration,amt_to_pay,location,plot_size,payplancode FROM payment_plan WHERE id='$payment1[planid]'");
		if(mysqli_num_rows($chwho1)>0){
			$plannn = mysqli_fetch_array($chwho1);
			$plan1 = $plannn['plan_name'];
		}else{
			$plan1 = ucwords(str_replace('-',' ',$payment1['planid']));
			
		}

		$heckpr1 = mysqli_query($conn, "SELECT id,title,description,amount,images,location,status,date,provider,popular,prop_uid,estate_name,unit_available,plots,prop_category,prop_full_desc,canpay_installment,state,axis,promotitle,promocode,promogift,promoprice,type,propertycode,dailyones FROM property WHERE id='$payment1[propertyid]'");
$property1 = mysqli_fetch_array($heckpr1);
		?>
  <div class="food-box mb-3" id="myprop_<?php print $payment1['id'];?>">
                            <div class="img-box">
							<a href="/propdetails/<?php print $payment1[2];?>/<?php print $me;?>">
                                <img src="<?php print $kaylink;?>/pix/<?php print $property1['images'];?>" alt="images" style="border-radius: 8px 8px 0 0;height:130px;object-fit:cover" /></a>
                                <span style="width:auto"><?php print $plan1;?></span>
                            </div>
                            <div class="content bg-white">
                              <a href="#" class="critical_color fw_6">₦ <?php print number_format($property1['amount'],2);?></a>

							  <h4><a href="/propdetails/<?php print $payment1[2];?>/<?php print $me;?>">#<?php print $property1['prop_uid'];?></a></h4>
                                <div class="rating mt-2">
                                    <a href="/propdetails/<?php print $payment1[2];?>/<?php print $me;?>" class="alert alert-info alert-xs p-1 btn text-center w-100" style="font-size:12px">View Details </a>
                                   
								   
								     <a href="#" data-propid="<?php print $payment1['id'];?>" class="deleteprop alert alert-danger alert-xs p-0 btn text-center"><i class="icon-recycle-bin" style="font-size:15px"></i></a>
                                </div>
                            </div>
                        </div>
<?php } } ?>
                   
                        
                    </div>