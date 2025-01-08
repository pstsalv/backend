<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$id = mysqli_real_escape_string($conn,$_GET['id']);
$me = mysqli_real_escape_string($conn,$_GET['me']);
		$heckpr = mysqli_query($conn, "SELECT id,title,description,amount,images,location,status,date,provider,popular,prop_uid,estate_name,unit_available,plots,prop_category,prop_full_desc,canpay_installment,state,axis,promotitle,promocode,promogift,promoprice,type,propertycode,dailyones FROM property WHERE id='$id'");
$property = mysqli_fetch_array($heckpr);

$checkmyp = mysqli_query($conn, "SELECT id,propamount FROM myproperty WHERE userid='$me' AND propertyid='$id'");
$realamt = mysqli_fetch_array($checkmyp);
		?>

<div class="header-style2" style="background: url(<?php print $kaylink;?>/pix/<?php print $property['images'];?>);
    background-position-x: 0%;
    background-position-y: 0%;
    background-repeat: repeat;
    background-size: auto;
  padding: 12px 0px 140px;
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
                <a href="#" class="critical_color fw_6">₦ <?php print number_format($realamt['propamount'],2);?></a>
            </div>
            <div class="mt-2">
                <a href="#" class="note-voucher">Pay as low as ₦ 1,000.00</a>
                <p class="mt-2 fw_4">Limited Offer</p>
            </div>
        </div>

        </div>
        <div class="app-section mt-1 bg_white_color giftcard-detail-section-2">
        <div class="tf-container">
            <div class="voucher-desc">
                <h4 class="fw_6">Property infomation</h4>
                <p class="mt-1"><?php print $property['description'];?></p>
              
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
                    <p class="fw_4">Provided by</p>
                    <h4 class="fw_6"><?php print $property['provider'];?></h4>
                </div>
                <a href="#" class="icon-right"></a>
            </div>
            <p class="text-center fw_4 mt-3 mb-2">This property is <?php if($property['status']=="active"){ echo 'Available';}else{ echo $property['status'];} ?></p>
        </div>

        </div>
    </div>  
    <div class="bottom-navigation-bar bottom-btn-fixed">
        <div class="tf-container" style="display:flex">
            <a href="/viewvirtual/<?php print $_GET['id'];?>/<?php print $_GET['me'];?>" class="tf-btn btn-secondary  p-2" style="border-radius:10px 0 0 10px">Virtual Account</a>
            <a href="/addpayment/" class="tf-btn accent p-2 paynowprop" style="border-radius:0 10px 10px 0">Pay Now</a>
        </div>
    </div>
	
	