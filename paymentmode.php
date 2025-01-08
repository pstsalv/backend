<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");
?>
<div class="alert alert-danger p-2"><i class="icon-info" style="font-size:19px;position:absolute;margin-top:3px;"></i><h4 style="padding-left:20px"> Notice</h4> Make sure you are Subscribed to a property first before making payment. Otherwise, your payment will not reflect on your payment history.</div>
<?php
$me = mysqli_real_escape_string($conn,$_GET['me']);
//check if client has selected property

//check if terms js signed

$checkprop = mysqli_query($conn, "SELECT id FROM myproperty WHERE userid='$me'");
if(mysqli_num_rows($checkprop)>0){
$chwho = mysqli_query($conn, "SELECT id,accname,accno,bankname,userid,date,customer_code,customer_id FROM banks WHERE userid ='$me'");
if(mysqli_num_rows($chwho)>0){
$bank = mysqli_fetch_array($chwho);
		?>

  <!---<div class="my-3 tf-card-block">
  <a href="/paybybank/" class=" d-flex align-items-center justify-content-between">
                <div class="inner d-flex align-items-center" style="gap:5px">
                    <i class="logo icon-wallet-filled-money-tool"></i>
                    <div class="content">
                        <h4><?php print $bank['bankname'];?></h4>
                        <p class=""><?php print ucwords(strtolower($bank['accname']));?></p>
                        <p class=""><?php print $bank['accno'];?></p>
                    </div>
                </div>
                <input type="radio"  name="radio">
				
			</a>
            </div>--->
  <div class="my-3 tf-card-block">
  <a href="/paybybank/" class=" d-flex align-items-center justify-content-between">
                <div class="inner d-flex align-items-center" style="gap:5px">
                    <i class="logo icon-wallet-filled-money-tool"></i>
                    <div class="content">
                        <h4>Bank Transfer</h4>
                        <p class="">Pay to your generated Virtual Account</p>
                    </div>
                </div>
                <input type="radio"  name="radio">
				
			</a>
            </div>
<?php }else{?>
<div class="my-3 tf-card-block">
  <a href="/newaccount/" class=" d-flex align-items-center justify-content-between">
                <div class="inner d-flex align-items-center" style="gap:5px">
                    <i class="logo icon-wallet-filled-money-tool"></i>
                    <div class="content">
                        <h4>Activate Virtual Account</h4>
                        <p class="">Activate Bank Account Transfers</p>
                    </div>
                </div>
            </a>
            </div>
<?php }}else{?>
<div class="my-3 tf-card-block">
  <a href="/allproperty/" class=" d-flex align-items-center justify-content-between">
                <div class="inner d-flex align-items-center" style="gap:5px">
                  <svg width="49px" height="49px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M19 9.77806V16.2C19 17.8801 19 18.7202 18.673 19.3619C18.3854 19.9264 17.9265 20.3854 17.362 20.673C17.2111 20.7499 17.0492 20.8087 16.868 20.8537M5 9.7774V16.2C5 17.8801 5 18.7202 5.32698 19.3619C5.6146 19.9264 6.07354 20.3854 6.63803 20.673C6.78894 20.7499 6.95082 20.8087 7.13202 20.8537M21 12L15.5668 5.96393C14.3311 4.59116 13.7133 3.90478 12.9856 3.65138C12.3466 3.42882 11.651 3.42887 11.0119 3.65153C10.2843 3.90503 9.66661 4.59151 8.43114 5.96446L3 12M7.13202 20.8537C7.65017 18.6447 9.63301 17 12 17C14.367 17 16.3498 18.6447 16.868 20.8537M7.13202 20.8537C7.72133 21 8.51495 21 9.8 21H14.2C15.485 21 16.2787 21 16.868 20.8537M14 12C14 13.1045 13.1046 14 12 14C10.8954 14 10 13.1045 10 12C10 10.8954 10.8954 9.99996 12 9.99996C13.1046 9.99996 14 10.8954 14 12Z" stroke="#533dea" stroke-width="1.464" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                    <div class="content">
                        <h4>Select Property First</h4>
                        <p class="">You must subscribe to a property before creating virtual account</p>
                    </div>
                </div>
            </a>
            </div>
			
<?php }?>
<div class="my-3 tf-card-block">
  <a href="/paybycard/" class=" d-flex align-items-center justify-content-between">
                <div class="inner d-flex align-items-center" style="gap:5px">
                    <div class="logo-img">
                        <img src="images/logo-banks/card-visa2.png" alt="image">
                    </div>
                    <div class="content">
                        <h4>Pay with Card</h4>
                        <p>Pay with Card or Transfer</p>
                    </div>
                </div>
                <input type="radio" name="radio">
				</a>
            </div>
	<style>
	.anoda::before {
  content: "\e91e";
  color: #533dea !important;
}
</style>	

			
			<div class="my-3 tf-card-block">
  <a href="/paybycash/" data-reload-current="true" class=" d-flex align-items-center justify-content-between">
 
                <div class="inner d-flex align-items-center"  style="gap:5px">
                   <i class="logo icon-group-dollar anoda" style=""></i>
                    <div class="content">
                        <h4>Cash Deposit</h4>
                        <p>Deposit at our offices or to our agents</p>
                    </div>
                </div>
                <input type="radio" name="radio">
				</a>
            </div>

			
			<div class="my-3 tf-card-block">
  <a href="/recurring-payment/" class=" d-flex align-items-center justify-content-between">
 
                <div class="inner d-flex align-items-center" style="gap:5px">
                   <i class="logo icon-add-card"></i>
                    <div class="content">
                        <h4>Auto Debit </h4>
                        <p>Recurring Payment: Daily, Weekly etc.</p>
                    </div>
                </div>
                <input type="radio" name="radio">
				</a>
            </div>
			
