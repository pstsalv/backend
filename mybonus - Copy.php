<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include("timed.php");
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);

$fortoday =  mysqli_query($conn, "SELECT id, date, DATE_FORMAT(date, '%Y-%m-%d') AS dday FROM referral WHERE agentid='$me' GROUP BY dday ORDER BY id DESC") OR die(mysqli_error($conn));


$today = date('Y-m-d');
$yesterday = new DateTime('yesterday');
$yerst = $yesterday->format('Y-m-d');
if(mysqli_num_rows($fortoday)>0){
	
	while($transday = mysqli_fetch_array($fortoday)){
?>
<div class="trading-month">
                        <h5 class="fw_5 mb-3"><?php if($transday['dday']=="$today"){ print "Today"; }elseif($transday['dday']=="$yerst"){ print "Yesterday"; }else{ print date('F jS, Y',strtotime($transday['dday'])); }?></h5>
                        <div class="group-trading-history mb-5">
<?php
$check =mysqli_query($conn, "SELECT id,agentid,customerid,amount,payment_method,pay_status,date,seen_status FROM referral WHERE agentid='$me' AND DATE_FORMAT(date, '%Y-%m-%d')='$transday[dday]' ORDER BY id DESC");
if(mysqli_num_rows($check)>0){
	while($referral = mysqli_fetch_array($check)){
		if($referral['pay_status'] =="paid"){
			$status = "success_color";
		}else{
			$status = "critical_color";
		}
		$checku = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$referral[customerid]'");
		$users = mysqli_fetch_array($checku)
		?>
        <a class="tf-trading-history bg-white px-2 py-3" style="border-radius:10px" href="/sendmoney/">
                                <div class="inner-left">
                                  
									<div class="thumb">
                                        <img src="<?php print $kaylink;?>/pix/thumb/<?php print $users['pix'];?>" alt="image">
                                    </div>
                                    <div class="content">
                                        <h4><?php print ucwords($users['fname']);?> <?php print ucwords($users['lname']);?></h4>
                                        <p>Joined <?php print get_time_ago(strtotime($users['date']));?></p>
                                    </div>
                                </div>
                                <span class="num-val <?php print $status;?>">+ ₦ <?php print number_format($referral['amount'],2);?></span>
                            </a>
<?php } } ?>
                           
                        </div>
                </div>
<?php }} ?>