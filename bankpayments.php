<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
$propuid = mysqli_real_escape_string($conn,$_GET['propuid']);

$fortoday =  mysqli_query($conn, "SELECT id, date_paid, DATE_FORMAT(date_paid, '%Y-%m-%d') AS dday FROM payment WHERE userid='$me' AND admin_approved='virtual' AND paidfor='$propuid' GROUP BY dday ORDER BY id DESC") OR die(mysqli_error($conn));


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
$check =mysqli_query($conn, "SELECT id,userid,amount,status,prev_amt,paid_by,admin_approved,date_paid,payment_method,notes,paidfor,payref,state,zoneid,agentid,propert_type FROM payment WHERE userid='$me' AND admin_approved='virtual' AND paidfor='$propuid' AND DATE_FORMAT(date_paid, '%Y-%m-%d')='$transday[dday]' ORDER BY id DESC");
if(mysqli_num_rows($check)>0){
	while($payment = mysqli_fetch_array($check)){
		if($payment['status'] =="approved"){
			$status = "success_color";
			$dpaypix = "checked.png";
		}else{
			$status = "critical_color";
			$dpaypix = "cancel.png";
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
<?php }}else{ ?>
 <div class="alert alert-danger p-1 m-1 text-center">No payent history for this property yet</div>
<?php }?>
<div style="height:100px;margin-top:100px;width:100%">
.
</div>





<?php
$checkow = mysqli_query($conn, "SELECT * FROM users WHERE id='$me'");
$profile = mysqli_fetch_array($checkow);

$checkpro = mysqli_query($conn, "SELECT * FROM myproperty WHERE userid='$me'");
$propert = mysqli_fetch_array($checkpro);
?>
	

