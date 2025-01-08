<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
?>
<div class="trading-month">
 <div class="group-trading-history mb-5 m-0">
<?php
$check =mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE agent='$me' AND account_type='customer' AND uncleared>0 ORDER BY id DESC");
if(mysqli_num_rows($check)>0){
	while($customer = mysqli_fetch_array($check)){
		$checkallp = mysqli_query($conn, "SELECT id,userid,amount,status,prev_amt,paid_by,admin_approved,date_paid,payment_method,notes,paidfor,payref,state,zoneid,agentid,propert_type FROM payment WHERE userid='$customer[id]' AND status='uncleared'");
		$totalpay = mysqli_num_rows($checkallp);
	
			$status = "text-danger";
		
		?>
                            <a class="tf-trading-history bg-white shadow-sm p-2" href="/clientpays/<?php print $customer['id'];?>/<?php print $customer['fname'].' '.$customer['lname'];?>/<?php print number_format($customer['uncleared'],2);?>" style="border-radius:10px">
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
                                <span class="num-val <?php print $status;?>">+ ₦ <?php print number_format($customer['uncleared']);?></span>
                            </a>
							



<?php } } ?>
</div>
</div>