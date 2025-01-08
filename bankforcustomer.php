<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');
include_once("conn.php");
$me = mysqli_real_escape_string($conn,$_GET['me']);
$check = mysqli_query($conn, "SELECT id,accname,accno,bankname,userid,date,customer_code,customer_id FROM banks WHERE userid='$me'");
if(mysqli_num_rows($check)>0){
$banks = mysqli_fetch_array($check);
?>

 <div class="card-secton">
        <div class="tf-container">
            <div class="tf-balance-box">
           
                <div class="wallet-footer">
                    <div class="justify-content-between align-items-center">
                     <i class="icon-copy1 copydiz" value="<?php print $banks['accno'];?>" style="position:absolute;right:30px;font-size:20px"></i>
					 <h1 class="myaccno text-center"><?php print $banks['accno'];?></h1>
                        <div class="mybank text-dark text-center" style="font-size:19px"><?php print $banks['bankname'];?></div>
                        <div class="myaccname text-dark text-center"><?php print $banks['accname'];?></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php }else{?>
<div class="text-center">
<a href="/newaccount/<?php print $me;?>/new" class="btn btn-primary">Create Virtual Account</a>
</div>
<?php }?>
	 <div class="app-section st1 mt-1 bg_white_color">
                <div class="tf-container">
                <input type="text" placeholder="Search" />
                </div>
            </div>
			<div class="alert alert-info m-3 p-2"><i class="icon-info" style="font-size:19px;position:absolute;margin-top:3px;"></i><h4 style="padding-left:20px"> Notice</h4>You can save this bank details for future payments for property on Bliss Legacy LTD. All bank transfers to the above bank details will show here.</div>
    <div class="transfer-card">
        <div class="tf-container ">
         
  <?php     
$fortoday =  mysqli_query($conn, "SELECT id, date_paid, DATE_FORMAT(date_paid, '%Y-%m-%d') AS dday FROM payment WHERE userid='$me' AND admin_approved='virtual' GROUP BY dday ORDER BY id DESC") OR die(mysqli_error($conn));


$today = date('Y-m-d');
$yesterday = new DateTime('yesterday');
$yerst = $yesterday->format('Y-m-d');
if(mysqli_num_rows($fortoday)>0){
	
	while($transday = mysqli_fetch_array($fortoday)){
?>
<div class="trading-month">
                        <h4 class="fw_5 mb-3"><?php if($transday['dday']=="$today"){ print "Today"; }elseif($transday['dday']=="$yerst"){ print "Yesterday"; }else{ print date('F jS, Y',strtotime($transday['dday'])); }?></h4>
                        <div class="group-trading-history mb-5">
<?php
$check =mysqli_query($conn, "SELECT id,userid,amount,status,prev_amt,paid_by,admin_approved,date_paid,payment_method,notes,paidfor,payref,state,zoneid,agentid,propert_type FROM payment WHERE userid='$me' AND admin_approved='virtual' AND DATE_FORMAT(date_paid, '%Y-%m-%d')='$transday[dday]' ORDER BY id DESC");
if(mysqli_num_rows($check)>0){
	while($payment = mysqli_fetch_array($check)){
		if($payment['status'] =="approved"){
			$status = "success_color";
		}else{
			$status = "critical_color";
		}
		?>
                            <a class="tf-trading-history" href="/pay-detail/<?php print $payment['id'];?>">
                                <div class="inner-left">
                                  
									<div class="thumb">
                                        <img src="<?php print $kaylink;?>/pix/paydone.png" alt="image">
                                    </div>
                                    <div class="content">
                                        <h4><?php print ucwords($payment['status']);?> Payment</h4>
                                        <p><?php print date('h:i A',strtotime($payment['date_paid']));?></p>
                                    </div>
                                </div>
                                <span class="num-val <?php print $status;?>">+ ₦ <?php print number_format($payment['amount'],2);?></span>
                            </a>
<?php } } ?>
                           
                        </div>
                </div>
<?php }} ?>
        </div>
      
    </div>
    
   
                <div class="bottom-navigation-bar bottom-btn-fixed st2">
                    <a href="#" class="tf-btn accent large sheet-open" data-sheet=".validatepay" >Verify a Payment</a>
                </div>


<div class="sheet-modal validatepay" style="height:50%;background:transparent">
      <div class="sheet-modal-inner" style="background:transparent">
 <div class="tf-panel up panel-open">
   
          <form class="tf-form verifypayment">
		  
		  <div class="panel-box panel-up wrap-panel-clear panel-change-profile">
            <div class="heading bg-white" style="padding:5px">
                
<input type="hidden" name="me" class="me" value="<?php print $me;?>" />
            
                <div class="mt-4">
 <div class="group-input">
                    <label class="text-dark">Payment Reference Number</label>
                    <input type="text" placeholder="Enter payment referece" name="payref" required />
                    </div>
					
                    <div class="group-input">
                    <label for="" class="bg-light border text-dark">Payment For?</label>
                    <select name="property" class="propertypid"  id="forproperty" required onchange="getProperty('forproperty')">
					<option value="" disabled selected>Select your property</option>
					<?php $chkp=mysqli_query($conn,"SELECT id,userid,propertyid,amount,status,date,planid,propuid,payduration,initial_deposit,amt_due,propamount,amt_paid,amt_remain FROM myproperty WHERE userid='$me'");
					if(mysqli_num_rows($chkp)>0){
						while($prop=mysqli_fetch_array($chkp)){?>
					<option value="<?php print $prop['propertyid'];?>" data-inital="<?php print round($prop['amt_due'],0);?>"><?php print $prop['propuid'];?> - ₦<?php print number_format($prop['propamount']);?></option>
					<?php }}else{?>
					<option value="" disabled>Customer is not subscribed to any property</option>
					<?php }?>
					</select>
                </div>
                   
                </div>

				
            </div>
            <div class="bottom mb-1">
                <button class="tf-btn accent large">Verify Payment</button>
            </div>

			<div class="bottom">
                <a class="clear-panel sheet-close" href="#">Dismiss</a>
            </div>
          </div>
		  </form>
    </div>
	
	
  </div>
</div>