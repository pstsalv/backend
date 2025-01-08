<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include("timed.php");
include_once("conn.php");

$userid = mysqli_real_escape_string($conn,$_GET['me']);
if(isset($_GET['id'])){
	
$me = mysqli_real_escape_string($conn,$_GET['id']);

$fortoday =  mysqli_query($conn, "SELECT id, date, DATE_FORMAT(date, '%Y-%m') AS dday FROM bonuses WHERE userid='$me' AND status!='draft' AND hideshow!='pump' GROUP BY dday ORDER BY id DESC") OR die(mysqli_error($conn));


$today = date('Y-m-d');
$yesterday = new DateTime('yesterday');
$yerst = $yesterday->format('Y-m-d');
if(mysqli_num_rows($fortoday)>0){
	
	while($transday = mysqli_fetch_array($fortoday)){
?>
<div class="trading-month">
                        <h5 class="fw_5 mb-3"><?php print date('F Y',strtotime($transday['dday']));?></h5>
                        <div class="group-trading-history mb-5">
<?php
$check =mysqli_query($conn, "SELECT id,userid,amount,reason,status,date,type,add_remove,month,clients,hideshow FROM bonuses WHERE userid='$me' AND DATE_FORMAT(date, '%Y-%m')='$transday[dday]' AND status!='draft' AND status!='unapproved' AND hideshow!='pump' ORDER BY id DESC");
if(mysqli_num_rows($check)>0){
	while($referral = mysqli_fetch_array($check)){
		if($referral['status'] =="approved"){
			$status = "success_color";
		}else{
			$status = "critical_color";
		}

		?>
<a class="tf-trading-history bg-white px-2 py-3" style="border-radius:10px" href="/cashout/">
	<div class="inner-left">
	  
		<div class="thumb">
			<img src="<?php print $kaylink;?>/pix/paydone.png" alt="image" />
		</div>
		<div class="content">
			<h4><?php if($referral['type']=="bonus" || $referral['type']=="Bonus"){?><?php print ucwords($referral['reason']);?><?php }else{?><?php print ucwords($referral['type']);?> for <?php print ucwords($referral['month']);?><?php }?></h4>
			<p><?php print ucwords($referral['type']);?> <?php print ucwords($referral['status']);?></p>
		</div>
	</div>
	<span class="num-val <?php print $status;?>">+ ₦ <?php print number_format($referral['amount']);?></span>
</a>
<?php } } ?>
                           
                        </div>
                </div>
<?php }}

}else{
	echo '<div class="alert alert-danger">Update your app to see your bonus history</div>';
}
 ?>