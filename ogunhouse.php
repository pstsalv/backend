<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");

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
	
<ul class="menu-tabs kaytabs" style="display:flex">
                    <li class="nav-tab active" style="flex:1" data-id="daily">Daily</li>
                    <li class="nav-tab" style="flex:1" data-id="monthly">Monthly</li>
                    <li class="nav-tab" style="flex:1" data-id="outright">Outright</li>
                </ul>

				<h2 class="mb-3 mt-3">Ogun House</h2> 
                    <div class="content-tab mb-5">

					<div class="tab-gift-item alltab daily mt-2" style="display:contents">

				
            <?php
		$checkall = mysqli_query($conn, "SELECT * FROM property WHERE status='Public' AND canpay_installment='daily' AND prop_category='Building' AND state='Ogun' ORDER BY id DESC LIMIT 30");
		if(mysqli_num_rows($checkall)>0){
while($property = mysqli_fetch_array($checkall)){
		?>			
                        <div class="food-box" style="width:100%;margin-bottom:10px">
                            <div class="img-box">
                              <a href="/propertydetails/<?php print $property[0];?>">
							  <img src="<?php print $kaylink;?>/pix/<?php print $property['images'];?>" alt="images" style="height:150px;object-fit:cover">
							  </a>
                                <span style="width:auto">₦ <?php print $property['amttogive'];?> Daily</span>
                            </div>
                            <div class="content bg-white" style="border-radius:0 0 10px 10px">
                                <h4><a href="/propertydetails/<?php print $property[0];?>"><?php print ucwords($property['title']);?>..</a></h4>
                                <div class="rating mt-2">
                                  
                                    <span><?php print $property['location'];?></span>
                                </div>
                            </div>
                        </div>
<?PHP }}else{?>
                        <div class="alert alert-xs alert-info">There are no active daily properties at the moment, check other properties.</div>
<?php }?>  
                    </div>
					
					
					
					<div class="tab-gift-item-2 alltab monthly" style="display:none">
					 <?php
		$checkall2 = mysqli_query($conn, "SELECT * FROM property WHERE status='Public' AND canpay_installment='monthly' AND prop_category='Building' AND state='Ogun' ORDER BY id DESC LIMIT 30");
		if(mysqli_num_rows($checkall2)>0){
while($property2 = mysqli_fetch_array($checkall2)){
		?>			
                        <div class="food-box" style="width:100%">
                            <div class="img-box">
                              <a href="/propertydetails/<?php print $property2[0];?>">
							  <img src="<?php print $kaylink;?>/pix/<?php print $property2['images'];?>" alt="images" style="height:150px;object-fit:cover">
							  </a>
                                <span style="width:auto">₦ <?php print number_format($property2['amount']);?></span>
                            </div>
                            <div class="content bg-white">
                                <h4><a href="/propertydetails/<?php print $property2[0];?>"><?php print ucwords($property2['title']);?>..</a></h4>
                                <div class="rating mt-2">
                                  
                                    <span><?php print $property2['location'];?></span>
                                </div>
                            </div>
                        </div>
<?PHP }}else{?>
                        <div class="alert alert-xs alert-info">There are no active monthly properties at the moment, check other properties.</div>
<?php }?> 
                    </div>
					
					<div class="tab-gift-item-3 alltab outright" style="display:none">
					 <?php
		$checkall3 = mysqli_query($conn, "SELECT * FROM property WHERE status='Public' AND (canpay_installment='outright' OR canpay_installment='promo') AND prop_category='Building' AND state='Ogun' ORDER BY id DESC LIMIT 30");
		if(mysqli_num_rows($checkall3)>0){
while($property3 = mysqli_fetch_array($checkall3)){
		?>			
                        <div class="food-box" style="width:100%">
                            <div class="img-box">
                              <a href="/propertydetails/<?php print $property3[0];?>">
							  <img src="<?php print $kaylink;?>/pix/<?php print $property3['images'];?>" alt="images" style="height:150px;object-fit:cover">
							  </a>
                                <span style="width:auto">₦ <?php print number_format($property3['amount']);?> <?php if($property3['canpay_installment']=="promo"){?>- Promo<?php }?></span>
                            </div>
                            <div class="content bg-white">
                                <h4><a href="/propertydetails/<?php print $property3[0];?>"><?php print ucwords($property3['title']);?>..</a></h4>
                                <div class="rating mt-2">
                                  
                                    <span><?php print $property3['location'];?></span>
                                </div>
                            </div>
                        </div>
<?PHP }}else{?>
                        <div class="alert alert-xs alert-info">There are no active outright properties at the moment, check other state properties.</div>
<?php }?> 
                    </div>
					
					
                    </div>
  