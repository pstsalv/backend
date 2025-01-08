<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$token = mt_rand(99999,99999);
$dis = date('Y-m-d h:i:s');
$payref = strtotime($dis);

$emaild = mysqli_real_escape_string($conn,$_GET['email']);
$checkh = mysqli_query($conn, "SELECT * FROM banks WHERE acc_email='$emaild'");
if($checkh){

if(mysqli_num_rows($checkh)>0){

$bankdetail = mysqli_fetch_array($checkh);
$dpage = $bankdetail['customer_id'];

$curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction?customer=".$dpage,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Authorization: Bearer sk_live_250c0f0d8ca26ff0f78723498fe18e748431c84b",
      "Cache-Control: no-cache",
    ),
  ));
  
  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);
  
  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
$results = json_decode($response, true);

foreach($results['data'] as $datas){
//echo $datas['amount'].'<br>';
$count = $count+1;

if($datas['status']=="success"){
$dstat = "success_color";
$dstatimg = "successful.png";
}else{
$dstat = "text-danger";
$dstatimg = "failed.png";
}
?>    

 <a class="tf-trading-history bg-white shadow-sm p-2 border mb-1 sheet-open" data-sheet=".propop<?php print $datas['reference'];?>" <?php if($datas['status']!="success"){?>href="javascript:alert('Transaction <?php print $datas['status'];?>')"<?php }?> style="border-radius:10px">
                                <div class="inner-left">
                                  
									<div class="thumb">
                                        <img src="images/<?php print $dstatimg;?>" alt="" style=""/>
                                    </div>
                                    <div class="content">
                                        <h4 style="line-height:10px"><?php print $datas['customer']['first_name'];?> <?php print $datas['customer']['last_name'];?></h4>
                                        <p class="text-primary" style="font-size:10px"><?php print $datas['reference'];?></p>
                                        <p class="text-danger" style="font-size:8px;line-height:7px"><?php print date('l, F d Y h:i:s A,',strtotime($datas['paidAt']));?></p>
                                    </div>
                                </div>
                                <span class="num-val <?php print $dstat;?>">₦<?php print number_format($datas['amount']/100);?><br><span style="font-size:10px;float:right"><?php if($datas['channel']=="dedicated_nuban" || $datas['channel']=="bank_transfer"){ 
print "Transfer";
}else{
 print ucwords($datas['channel']);
}?></span></span>
                            </a>
 
<div class="sheet-modal propop<?php print $datas['reference'];?>" style="height:70%;background:transparent">
      <div class="sheet-modal-inner" style="background:transparent">
 <div class="tf-panel up panel-open">
   
          <form class="tf-form verifypayment">
		  
		  <div class="panel-box panel-up wrap-panel-clear panel-change-profile">
            <div class="heading">
<?php $checkv = mysqli_query($conn, "SELECT id FROM payment WHERE payref='$datas[reference]'");
if(mysqli_num_rows($checkv)>0){
?>
              <a class="sheet-close bg-primary text-white">This transaction is Validated</a>
<?php }else{?>
 <a class="sheet-close bg-danger text-white">This transaction is Not Validated</a>
<?php }?>
			
				<a class="sheet-open" data-sheet=".validatepa<?php print $datas['reference'];?>" data-customer="<?php print $datas['reference'];?>">Validate this Payment</a>
            </div>
            

			<div class="bottom">
                <a class=" sheet-close" href="#">Dismiss</a>
            </div>
          </div>
		  </form>
    </div>
	
	
  </div>
</div>



<div class="sheet-modal validatepa<?php print $datas['reference'];?>" style="height:100%;background:transparent">
      <div class="sheet-modal-inner" style="background:transparent">
 <div class="tf-panel up panel-open">
   
          <form class="tf-form verifypayment">
		  
		  <div class="panel-box panel-up wrap-panel-clear panel-change-profile">
            <div class="heading bg-white" style="padding:5px">
                
<input type="hidden" name="me" value="<?php print $bankdetail['userid'];?>">
            
                <div class="mt-4">
 <div class="group-input">
                    <label style="color:#000 !important">Payment Reference</label>
                    <input type="text" placeholder="Enter payment referece" name="payref" required="" value="<?php print $datas['reference'];?>" />
                    </div>
					
                    <div class="group-input input-field input-money">
                    <label style="color:#000 !important">Property Testing  Paid For</label>
                   <select name="property" class="payprop">
					<option>Select your property</option>
					
					<option value="" disabled="" selected="">Select your property</option>

<?php $checkpo = mysqli_query($conn, "SELECT * FROM myproperty WHERE userid='$bankdetail[userid]'");
if(mysqli_num_rows($checkpo)>0){
while($props = mysqli_fetch_array($checkpo)){
?>
<option value="<?php print $props['id'];?>" data-inital="<?php print $props['propamount'];?>"><?php print $props['propuid'];?></option>
<?php }} ?>

					</select>
                   
                    </div>
                   
                </div>

				
            </div>
            <div class="bottom mb-1">
                <button class="tf-btn accent large">Validate Payment</button>
            </div>

			<div class="bottom">
                <a class=" sheet-close" href="#">Dismiss</a>
            </div>
          </div>
		  </form>
    </div>
	
	
  </div>
</div>
<?php }

}

}else{
echo '<div class="alert alert-danger">This email is either wrong or not found</div>';
}
}else{
echo '<div class="alert alert-danger">Something went wrong, check your input</div>';
}

?>
