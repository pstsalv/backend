<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$id = mysqli_real_escape_string($conn,$_GET['id']);

$check =mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$id'");
	$users = mysqli_fetch_array($check);
$checksub =mysqli_query($conn, "SELECT id FROM myproperty WHERE userid='$users[id]'");
if(mysqli_num_rows($checksub)>0){
	$myprop = mysqli_num_rows($checksub);
	$dhouse = $myprop;
}else{
	$dhouse =0;
}
?>
<div style="height:20vh" class="bg-kay"></div>
       <div class="tf-container" style="margin-top:-80px">
        <div class="box-user mt-5 text-center">
         
			<div class="box-avatar">
                <img src="<?php print $kaylink;?>/pix/thumb/<?php print $users['pix'];?>" alt="image" style="object-fit:cover" />
            </div>
			
            <h3 class="fw_8 mt-3 serviced"><?php print $users['fname'];?></h3>
            <p><?php print $users['gender'];?></p>
            <ul class="button-list mt-6">
                <li class=""><a href="#" class="paynow"><i class="icon-send"></i></a>Pay Now</li>
                <li><a href="tel:<?php print $users['phone'];?>"  class="external"><i class="icon-phone"></i></a>Call</li>
                <li><a href="/allproperty/" class=""><i class="icon-plus"></i></a>Subscribe</li>
            </ul>   
        </div>
       <ul class="mt-7 pb-2">
            <li class="list-user-info"><span class="icon-user"></span><?php print $users['fname'].' '.$users['lname'];?></li>
            <a href="/myproperty/<?php print $users['id'];?>"><li class="list-user-info"><span class="icon-star-fill"></span><?php print $dhouse;?> Subscriptions <span style="position:absolute;right:10px;font-size:12px">View <i class="icon-right"></i></span></li></a>
            <li class="list-user-info"><span class="icon-phone"></span><?php print $users['phone'];?></li>
            <li class="list-user-info"><span class="icon-email"></span><?php print $users['email'];?></li>
            <li class="list-user-info"><span class="icon-location"></span><?php print $users['address'];?></li>

        </ul>
		
  <div class="tf-panel up " id="paynowbtn">
        <div class="panel_overlay"></div>
          <div class="panel-box panel-up wrap-panel-clear panel-change-profile">
            <div class="heading">
			<?php $checkdpay = mysqli_query($conn,"SELECT id,accname,accno,bankname,userid,date,customer_code,customer_id FROM banks WHERE userid='$users[id]'");
			if(mysqli_num_rows($checkdpay)>0){?>
                <a data-dhref="/paybybank/<?php print $users['id'];?>" href="#" class="custpaybtn">Transfer to Bank</a>
			<?php }else{?>
                <a data-dhref="/myproperty/<?php print $users['id'];?>" href="#" class="custpaybtn">Transfer to Bank</a>
			<?php }?>
                <a data-dhref="/paybycard/<?php print $users['id'];?>/<?php print $users['fname'];?> <?php print $users['lname'];?>" href="#" class="custpaybtn">Pay with Card</a>
                <a data-dhref="/paybycash/<?php print $users['id'];?>/<?php print $users['fname'];?> <?php print $users['lname'];?>" href="#" class="custpaybtn">Cash Deposit</a>
                <a data-dhref="/recurring-payment/<?php print $users['id'];?>/<?php print $users['fname'];?> <?php print $users['lname'];?>" href="#" class="custpaybtn">Schedule Payment</a>
            </div>
            <div class="bottom">
                <a class="clear-panel" href="#">Dismiss</a>
            </div>
          </div>
    </div>
	
       </div>