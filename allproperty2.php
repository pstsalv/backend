<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");
$location = mysqli_real_escape_string($conn,$_GET['location']);
?>

			
	<style> 
.kaytabs .nav-tab.active {
  color: #1e1e1e;
  border-bottom: 2px solid #533dea;
}
.kaytabs .nav-tab {
  border-bottom: 1px solid #ededed;
  padding-bottom: 8px;
  text-align: center;
  color: #717171;
  font-weight: 600;
  font-size: 14px;
  line-height: 22px;
}	
	</style>        
	

                    <div class="content-tab mb-5">

					<div class="tab-gift-item alltab daily mt-2" style="display:contents">

				
<?php
$me= mysqli_real_escape_string($conn, $_GET['me']);
$checkme = mysqli_query($conn, "SELECT id,state FROM users WHERE id='$me'");
$myacc = mysqli_fetch_array($checkme);
if($location=="dinner"){
	$checkall = mysqli_query($conn, "SELECT * FROM property WHERE status='Public' AND canpay_installment='dinner' ORDER BY id DESC LIMIT 30");
}else{
$checkall = mysqli_query($conn, "SELECT * FROM property WHERE status='Public' AND canpay_installment='promo' AND state='$location' ORDER BY id DESC LIMIT 30");
}
if(mysqli_num_rows($checkall)>0){
while($property = mysqli_fetch_array($checkall)){
		?>			
                        <div class="food-box shadow-sm" style="width:100%;margin-bottom:10px">
                            <div class="img-box">
                              <a href="/propertydetails/<?php print $property[0];?>">
							  <img src="<?php print $kaylink;?>/pix/<?php print $property['images'];?>" alt="images" style="height:150px;object-fit:cover;border-radius:8px 8px 0px 0px">
							  </a>
							
                                <span style="width:auto">₦ <?php if($property['promocode']=="notapplicable"){ print number_format($property['amount'],2); }else{ print number_format($property['amount']*2,2);}?> </span>
                            </div>
                            <div class="content bg-white" style="border-radius:0px 0px 8px 8px">
                                <h4><a href="/propertydetails/<?php print $property[0];?>"><?php print ucwords($property['title']);?>..</a></h4>
                                <div class="rating mt-2">
                                  
                                    <span class="text-primary"><?php print $property['location'];?></span>
                                </div>
                            </div>
                        </div>
<?PHP }}else{?>
                        <div class="alert alert-xs alert-info">There are no active daily properties at the moment, check other properties.</div>
<?php }?>  
                    </div>
					
			
					
                    </div>
  