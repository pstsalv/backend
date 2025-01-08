<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
?>
<style>
.contractlist li{
	list-style-type:circle;
}
</style>
<?php
$checkp =mysqli_query($conn, "SELECT id,userid,propertyid,amount,status,date,planid,propuid,payduration,initial_deposit,amt_due,propamount,amt_paid,amt_remain FROM myproperty WHERE userid='$me' ORDER BY id");
if(mysqli_num_rows($checkp)>0){
	while($myproppy = mysqli_fetch_array($checkp)){
		
		
$checkpp =mysqli_query($conn, "SELECT id,title,description,amount,images,location,status,date,provider,popular,prop_uid,estate_name,unit_available,plots,prop_category,prop_full_desc,canpay_installment,state,axis,promotitle,promocode,promogift,promoprice,type,propertycode,dailyones FROM property WHERE prop_uid='$myproppy[propuid]'");
$actualprop = mysqli_fetch_array($checkpp);


$checkdocp =mysqli_query($conn, "SELECT id,plan_name,amount,plancode,status,type,duration,amt_to_pay,location,plot_size,payplancode FROM payment_plan WHERE id='$myproppy[planid]'");

if(mysqli_num_rows($checkdocp)>0){
	$myplan = mysqli_fetch_array($checkdocp);
		$myplanz = $myplan['plan_name'];
		$planamt = $myplan['amount'];
	}else{
		$myplanz = ucwords(str_replace('-',' ',$myproppy['planid'])).' Payment';
		$planamt = $myproppy['initial_deposit'];
	}

$checks =mysqli_query($conn, "SELECT signature FROM mysigns WHERE owner_id='$me'");
if($checks && mysqli_num_rows($checks)>0){
	$mysign = mysqli_fetch_array($checks);
$mysigned = $mysign[0];
}else{
	$mysigned = "";
}

$checkuser =mysqli_query($conn, "SELECT id,fname,lname,pix FROM users WHERE id='$me'");
$user = mysqli_fetch_array($checkuser);
?>
<style>
.notouchy{
	-moz-user-select: none;
-webkit-user-select: none;
user-select: none;
}
</style>
<div class="card mx-0">
                                    <div class="card-header bg-white">
                                        <div class="align-items-center">
                                            <div class=" mb-0 " style="font-size:10px">Contract of Sales for #<?php print $myproppy['propuid'];?></div>
                                         
                                        </div>
                                    </div>
                                    <div class="row g-0">
                                        <div class="col-7">
										<div class="bg-primary p-2 text-white ">
                                                 Available Documents
                                                </div>
                                            <div class="card-body border-end px-0 py-0 bg-primary">
                                               <a href="/readdoc/<?php print $myproppy[0];?>">
                                                <div style="height:190px;overflow-y:scroll" class="py-0 bg-light">
                                                    <div class="px-0 pt-0 mb-0 showpdf" id="candidate-list">
													

<div class="p-2 border mx-2 mt-3 bg-white shadow-sm notouchy" style="height:100%;border-radius:10px">
<div class="p-2 border">
<p class="text-center pt-1" style="font-size:7px;font-weight:700;line-height:8px">CONTRACT OF
<?php print strtoupper($myplanz);?>/DEPOSIT<br>
FOR <?php print strtoupper($actualprop['prop_category']);?> </p>
<div class="text-center">
<p style="font-size:7px;font-weight:700;margin-top:10px">
BETWEEN
</p>
<p style="font-size:7px;font-weight:700;margin-top:10px;line-height:8px">
BLISS LEGACY LIMITED <br>
(VENDOR)
</p>
<p style="font-size:7px;font-weight:700;margin-top:10px">
AND
</p>
<p style="font-size:7px;font-weight:700;margin-top:5px;line-height:8px">
<?php print strtoupper($user['fname'].' '.$user['lname']);?><br>
	(DEPOSITOR)	 
</p>
<p style="font-size:7px;font-weight:700;margin-top:10px">
PREPARED BY:</p>
<img src="<?php print $kaylink;?>/pix/lawyer.png" alt="" style="height:25px;width:auto" />
<p style="font-size:5px;font-weight:700;margin-top:5px;line-height:10px">
Samuel Akpologun Esq<br>
Ace & Vanguard Legal Practitioners<br>
1B Akin Osiyemi Street,
Off Allen Avenue, Lagos.<br>
234 806 526 4570<br>
info@aceandvanguard.com
</p>
</div>
</div>



                                                    </div>
                                                </div>
                                                </div>
												</a>
												<a href="/readdoc/<?php print $myproppy[0];?>" class="px-2 text-white">
												Click to Preview</a>
                                            </div>
											
                                        </div>
                                        <div class="col-5">
                                            <div class="card-body text-center p-1">
                                                <div class="avatar-md mb-0 mx-auto p-3">
                                                    <img src="<?php print $kaylink;?>/pix/thumb/<?php print $user['pix'];?>" alt="" id="candidate-img" class="img-thumbnail rounded-circle shadow-none" style="height:100px;width:100px;object-fit:cover" />
                                                </div>
                                
                                                <h5 id="candidate-name" class="mb-0"><?php print $user['fname'];?> <?php print $user['lname'];?></h5>
                                     <?php if($mysigned!==""){?>    
                                <a href="<?php print $mysigned;?>" class="external">
                                             <img src="<?php print $mysigned;?>" class="oldsign" style="height:50px;width:98%;filter: grayscale(100%);" />
                                </a>
									 <?php }?>
                                                <div class="mt-2">
                                                    <a href="/signature/" class="btn btn-success custom-toggle w-100 p-1">
                                                      Sign Now
                                                     
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
	<?php }
}else{
?>
  <div class="text-center" style="height:90vh">
			   <img src="images/empty.png" alt="" style="height:250px;width:auto;mix-blend-mode: multiply" />
			   <h3>Your Contract Will be automatically generated once you add property and plan.</h3>
			   </div>
<?php }?>
								<?php 
$check =mysqli_query($conn, "SELECT * FROM contracts WHERE owner_id='$me' ORDER BY id");
if(mysqli_num_rows($check)>0){
	while($payment = mysqli_fetch_array($check)){


	?>
 <div class="card mx-0">
                                    <div class="card-header bg-white">
                                        <div class="align-items-center">
                                            <div class=" mb-0 " style="font-size:17px">Contract of engagement</div>
                                         
                                        </div>
                                    </div>
                                    <div class="row g-0">
                                        <div class="col-7">
										<div class="bg-primary p-2 text-white">
                                                 Available Documents
                                                </div>
                                            <div class="card-body border-end px-0 pt-0">
                                               
                                                <div style="max-height:170px;overflow-y:scroll" class="pt-0">
                                                    <ul class="px-3 pt-0 mb-0 contractlist" id="candidate-list">
                                                        <li>
                                                            <a href="javascript:void(0);" class="d-flex align-items-center py-2">
                                                            
                                                                <div class="flex-grow-1">
                                                                    <h5 class="fs-13 mb-1 text-truncate"><span class="candidate-name">Engagement Contract</span> <span class="text-muted fw-normal">1 Copy</span></h5>
                                                                  
                                                                </div>
                                                            </a>
                                                        </li>
                                
                                                        <li>
                                                            <a href="javascript:void(0);" class="d-flex align-items-center py-2">
                                                               
                                                                <div class="flex-grow-1">
                                                                    <h5 class="fs-13 mb-1 text-truncate"><span class="candidate-name">Payment Conract</span> <span class="text-muted fw-normal">2 copies</span></h5>
                                                                   
                                                                </div>
                                                            </a>
                                                        </li>
                                                     
                                                        <li>
                                                            <a href="javascript:void(0);" class="d-flex align-items-center py-2">
                                                               
                                                                <div class="flex-grow-1">
                                                                    <h5 class="fs-13 mb-1 text-truncate"><span class="candidate-name">Deed of Contract</span> <span class="text-muted fw-normal">1 copy</span></h5>
                                                                   
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);" class="d-flex align-items-center py-2">
                                                             
                                                                <div class="flex-grow-1">
                                                                    <h5 class="fs-13 mb-1 text-truncate"><span class="candidate-name">Survey</span> <span class="text-muted fw-normal">@Jennifer</span></h5>
                                                                    <div class="d-none candidate-position">Marketing Director</div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);" class="d-flex align-items-center py-2">
                                                               
                                                                <div class="flex-grow-1">
                                                                    <h5 class="fs-13 mb-1 text-truncate"><span class="candidate-name">Payment Receipt</span> <span class="text-muted fw-normal">2 cpies</span></h5>
                                                                   
                                                                </div>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
												
												
                                            </div>
											
                                        </div>
                                        <div class="col-5">
                                            <div class="card-body text-center p-1">
                                                <div class="avatar-md mb-0 mx-auto p-3">
                                                    <img src="<?php print $kaylink;?>/pix/thumb/<?php print $user['pix'];?>" alt="" id="candidate-img" class="img-thumbnail rounded-circle shadow-none">
                                                </div>
                                
                                                <h5 id="candidate-name" class="mb-0"><?php print $user['fname'];?> <?php print $user['lname'];?></h5>
                                         
                               <?php if($mysigned!==""){?>    
                                <a href="<?php print $mysigned;?>" class="external">
                                             <img src="<?php print $mysigned;?>" class="oldsign" style="height:50px;width:98%;" />
                                </a>
									 <?php }?>
                                                <div class="mt-2">
                                                    <a href="/signature/" class="btn btn-success custom-toggle w-100 p-1">
                                                      Sign Now
                                                     
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<?php
	}
}
?>