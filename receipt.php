<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$id = mysqli_real_escape_string($conn,$_GET['id']);
$check = mysqli_query($conn, "SELECT * FROM withdrawals WHERE id='$id'");
$payment = mysqli_fetch_array($check);
if($payment['status']=="successful"){
	$stimg = "successful.png";
}else{
	$stimg = "failed.png";
}
?>

<div class="app-header st1">
        <div class="tf-container">
            <div class="tf-topbar d-flex justify-content-center align-items-center">
               <a href="#" class="back-btn back"><i class="icon-left white_color"></i></a> 
                <h3 class="white_color">Transaction Receipt</h3>
            </div>
        </div>
    </div>
<div class="card-secton transfer-section">
        <div class="tf-container">
            <div class="tf-balance-box transfer-amount shadow-sm">
                <div class="top">
                  <img src="images/<?php print $stimg;?>" alt="" style="height:100px;width:100px;object-fit:cover" />
                    <h4 class="text-primary fw_4">To <?php print ucwords($payment['accname']);?></h4>
					<h1 class="mycommd mt-1">₦<?php print number_format($payment['amount'],2);?></h1>
                    <h5 class="text-success fw_4"><?php print ucwords($payment['remark']);?></h5>
                </div>
            
            </div>
           
        </div>
    </div>
	

	
	<div class="transfer-list mt-5">
        <div class="tf-container">
            <ul class="list-view" style="margin-bottom: 10px;">
                <li>
                    Transfer Amount
                    <span>₦ <?php print number_format($payment['amount'],2);?></span>
                </li>
               
				<li>
                   BlissPay Fee 
                    <span>₦ 0.00</span>
                </li>
                <li hidden>
                    Bank Fee 
                    <span>₦ 4.00</span>
                </li>
                <li style="font-weight:700">
                    Transaction Amount
                    <span>₦ <?php print number_format($payment['amount'],2);?></span>
                </li>
    
            </ul>
       
            
        </div>
    </div>
	
	
	<div class="transfer-list mt-1">
        <div class="tf-container">
            <ul class="list-view" style="margin-bottom:0px;">
                <li>
                    Receiver
                    <span><?php print ucwords($payment['accname']);?></span>
                </li>
                <li>
                   Receiver's Bank 
                    <span><?php print ucwords($payment['bankname']);?></span>
                </li>
				<li>
                   Receiver's Account Number 
                    <span><?php print $payment['accno'];?></span>
                </li>
				<li>
                   Reference No 
                    <span><?php print $payment['payref'];?></span>
                </li>
                <li>
                    Transfer From 
                    <span>BlissPay wallet</span>
                </li>
                <li>
                    Transaction Date
                    <span><?php print date('M d, Y h:i:sA',strtotime($payment['date']));?></span>
                </li>
    
            </ul>
       
            
        </div>
    </div>
	
	
	
	<div class="card shadow-sm">
	<div class="card-body text-dark">
       <small>If you have any issues with your transfer, contact tech support with your Reference No.</small>
    </div>
    </div>
	
