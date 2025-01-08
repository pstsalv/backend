<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
date_default_timezone_get();
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
$checkwho = mysqli_query($conn, "SELECT id,fname,userid,account_type,position FROM users WHERE userid='$me'");
if(mysqli_num_rows($checkwho)==1){
	$mydetails = mysqli_fetch_array($checkwho);
	if($mydetails['position']=="Account Officer"){
		
		
		$fortoday =  mysqli_query($conn, "SELECT id, date_paid, DATE_FORMAT(date_paid, '%Y-%m-%d') AS dday FROM payment WHERE collectorid='$me' AND status='approved' AND admin_approved='virtual' GROUP BY dday ORDER BY date_paid DESC") OR die(mysqli_error($conn));


$today = date('Y-m-d');
$yesterday = new DateTime('yesterday');
$yerst = $yesterday->format('Y-m-d');
if(mysqli_num_rows($fortoday)>0){
	
	while($transday = mysqli_fetch_array($fortoday)){
?>
<div class="trading-month">

 <h5 class="fw_5 mb-3"><?php if($transday['dday']=="$today"){ print "Today"; }elseif($transday['dday']=="$yerst"){ print "Yesterday"; }else{ print date('F jS, Y',strtotime($transday['dday'])); }?></h5>
         
 <div class="group-trading-history mb-5 m-0">
<?php

$checkallp = mysqli_query($conn, "SELECT * FROM payment WHERE collectorid='$me' AND status='approved' AND admin_approved='virtual' AND DATE_FORMAT(date_paid, '%Y-%m-%d')='$transday[dday]' AND propert_type!='outright' AND propert_type!='promo' GROUP BY userid");
while($dpays = mysqli_fetch_array($checkallp)){

$calcaos = mysqli_query($conn,"SELECT SUM(amount) AS revenueao FROM  payment WHERE propert_type!='outright' AND userid='$dpays[userid]' AND propert_type!='promo' AND admin_approved='virtual' AND DATE_FORMAT(date_paid, '%Y-%m-%d')='$transday[dday]'");
$rowao = mysqli_fetch_assoc($calcaos);
$sumao = $rowao['revenueao'];
$outstandao = "$sumao";
if($outstandao!==""){
$revao = $outstandao;
}else{
$revao = "0.00";
};


$check =mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$dpays[userid]'");
if(mysqli_num_rows($check)>0){
$customer = mysqli_fetch_array($check);

$status = "success_color";


$checkttpaid = mysqli_query($conn, "SELECT id,userid,propuid FROM myproperty WHERE userid='$customer[id]' AND propertyid='$dpays[paidfor]'");
if(mysqli_num_rows($checkttpaid)>0){
	$totalpay = mysqli_fetch_array($checkttpaid);
	$proppdp = $totalpay['propuid'];
}else{
	$proppdp = "Property Not Selected";
}


		
		?>
                            <a class="tf-trading-history bg-white shadow-sm p-2" href="/clientpays/<?php print $customer['id'];?>/<?php print $customer['fname'].' '.$customer['lname'];?>/<?php print number_format($customer['wallet_bal'],2);?>" style="border-radius:10px">
                                <div class="inner-left">
                                  
									<div class="thumb" style="object-fit:cover;background:#f1f1f1;border:1px solid #adadad">
                                        <img src="<?php print $kaylink;?>/pix/thumb/<?php print $customer['pix'];?>" alt="<?php print $customer['pix'];?>"  />
                                    </div>
                                    <div class="content">
                                        <h4 style="line-height:10px"><?php print ucwords($customer['fname']);?> <?php print ucwords($customer['lname']);?></h4>
                                        <p class="text-primary">Property ID: <?php print $proppdp;?></p>
                                        <p class="text-danger" style="font-size:8px;line-height:7px">#<?php print $dpays['payref'];?></p>
                                    </div>
                                </div>
                                <span class="num-val <?php print $status;?>">+ ₦<?php print number_format($revao);?></span>
                            </a>
							



<?php
 }else{ ?>
		<div class="alert bg-danger">No assigned customer has made any payment.</div>
	<?php
	}
}
	}
}
?>
</div>
</div>
<?php
	}else{
		?>
		
		<div class="trading-month">
 <div class="group-trading-history mb-5 m-0">
<?php
$check =mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE agent='$me' AND account_type='customer' AND wallet_bal>0 ORDER BY id DESC");
if(mysqli_num_rows($check)>0){
	while($customer = mysqli_fetch_array($check)){
		$checkallp = mysqli_query($conn, "SELECT id,userid,amount,status,prev_amt,paid_by,admin_approved,date_paid,payment_method,notes,paidfor,payref,state,zoneid,agentid,propert_type FROM payment WHERE userid='$customer[id]' AND status='approved' AND admin_approved='virtual' AND propert_type!='outright' AND propert_type!='promo'");
	
			$status = "success_color";
			
			$checkttpaid = mysqli_query($conn, "SELECT * FROM payment WHERE userid='$customer[id]' AND status='approved' AND admin_approved='virtual' AND propert_type!='outright' AND propert_type!='promo'");

$totalpay = mysqli_num_rows($checkttpaid);
		
		?>
                            <a class="tf-trading-history bg-white shadow-sm p-2" href="/clientpays/<?php print $customer['id'];?>/<?php print $customer['fname'].' '.$customer['lname'];?>/<?php print number_format($customer['wallet_bal'],2);?>" style="border-radius:10px">
                                <div class="inner-left">
                                  
									<div class="thumb" style="object-fit:cover;background:#f1f1f1;border:1px solid #adadad">
                                        <img src="<?php print $kaylink;?>/pix/thumb/<?php print $customer['pix'];?>" alt="<?php print $customer['pix'];?>"  />
                                    </div>
                                    <div class="content">
                                        <h4 style="line-height:10px"><?php print ucwords($customer['fname']);?> <?php print ucwords($customer['lname']);?></h4>
                                        <p class="text-primary"><?php print number_format($totalpay);?> Cleared Payments</p>
                                        <p class="text-danger" style="font-size:8px;line-height:7px">Click to view <?php print ucwords($customer['fname']);?>'s payment history</p>
                                    </div>
                                </div>
                                <span class="num-val <?php print $status;?>">+ ₦ <?php print number_format($customer['wallet_bal']);?></span>
                            </a>
							



<?php } }else{
	?>
	<div class="alert bg-danger">No payment found for your clients.</div>
	<?php
}

?>
</div>
</div>


<?php
	}
}else{
	?>
	<div class="alert bg-danger">Something is wrong with your profile, contact support in your branch to check your Collector ID</div>
	<?php
}
?>