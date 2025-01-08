<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
?>
<?php
$checkr =mysqli_query($conn, "SELECT id,property,customerid,agentid,custname,amount,notes,paymethod,status,date,read_status FROM payment_request WHERE agentid='$me' AND status='pending' ORDER BY id DESC");
if(mysqli_num_rows($checkr)>0){
	while($paymentr = mysqli_fetch_array($checkr)){

		
		$chwhor = mysqli_query($conn, "SELECT id,pix,fname,lname FROM users WHERE id='$paymentr[customerid]'");
$payerr = mysqli_fetch_array($chwhor);
		?>
                            <a class="tf-trading-history px-2 py-3 border shadow-md mb-2" href="/pay-request/<?php print $paymentr['id'];?>" style="border-radius:10px;background:#f8d7da">
                                <div class="inner-left">
                                  
									<div class="thumb">
                                        <img src="<?php print $kaylink;?>/pix/thumb/<?php print $payerr['pix'];?>" alt="image">
                                    </div>
                                    <div class="content">
                                        <h4>Cash Deposit Request</h4>
                                        <p style="line-height:12px"><?php print date('d-m-Y h:i A',strtotime($paymentr['date']));?></p>
										<small style="font-size:11px;line-height:8px"> <?php print ucwords($payerr['fname']);?> <?php print ucwords($payerr['lname']);?></small>
                                    </div>
                                </div>
                                <span class="num-val critical_color">+ ₦ <?php print number_format($paymentr['amount']);?>
								
								</span>
                            </a>
<?php } } 
mysqli_query($conn, "UPDATE payment_request SET read_status='read' WHERE agentid='$me'");
?>



<?php
$fortoday =  mysqli_query($conn, "SELECT id, date_paid, DATE_FORMAT(date_paid, '%Y-%m-%d') AS dday FROM payment WHERE paid_by='$me' GROUP BY dday ORDER BY id DESC") OR die(mysqli_error($conn));


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
$check =mysqli_query($conn, "SELECT id,userid,amount,status,prev_amt,paid_by,admin_approved,date_paid,payment_method,notes,paidfor,payref,state,zoneid,agentid,propert_type FROM payment WHERE paid_by='$me' AND DATE_FORMAT(date_paid, '%Y-%m-%d')='$transday[dday]' ORDER BY id DESC");
if(mysqli_num_rows($check)>0){
	while($payment = mysqli_fetch_array($check)){
		if($payment['status'] =="approved"){
			$status = "success_color";
		}else{
			$status = "critical_color";
		}
		
		$chwho = mysqli_query($conn, "SELECT id,pix,fname,lname FROM users WHERE id='$payment[userid]'");
		$payer = mysqli_fetch_array($chwho);
		?>
                            <a class="tf-trading-history bg-white px-2 py-3" href="/pay-detail/<?php print $payment['id'];?>" style="border-radius:10px">
                                <div class="inner-left">
                                  
									<div class="thumb">
                                        <img src="<?php print $kaylink;?>/pix/thumb/<?php print $payer['pix'];?>" alt="image">
                                    </div>
                                    <div class="content">
                                        <h4><?php print ucwords($payer['fname']);?> <?php print ucwords($payer['lname']);?></h4>
                                        <p style="line-height:12px"><?php print date('d-m-Y h:i A',strtotime($payment['date_paid']));?></p>
										<small style="font-size:11px;line-height:8px"><?php print $payment['payment_method'];?></small>
                                    </div>
                                </div>
                                <span class="num-val <?php print $status;?>">+ ₦ <?php print number_format($payment['amount'],2);?>
								
								</span>
                            </a>
<?php } } ?>
                           
                        </div>
                </div>
<?php }} ?>