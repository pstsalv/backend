<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include("timed.php");
include_once("conn.php");

$userid = mysqli_real_escape_string($conn,$_GET['me']);
if(isset($_GET['state'])){
$state = mysqli_real_escape_string($conn,$_GET['state']);

$fortoday =  mysqli_query($conn, "SELECT id, date, DATE_FORMAT(date, '%Y-%m') AS dday FROM property WHERE state='$state' GROUP BY dday ORDER BY id DESC") OR die(mysqli_error($conn));


$today = date('Y-m-d');
$yesterday = new DateTime('yesterday');
$yerst = $yesterday->format('Y-m-d');
if(mysqli_num_rows($fortoday)>0){
	
	while($transday = mysqli_fetch_array($fortoday)){
?>
<div class="trading-month">
                        <h5 class="fw_5 mb-3 text-danger"><?php print date('F Y',strtotime($transday['dday']));?> Prices</h5>
                        <div class="group-trading-history mb-5">
<?php
$check =mysqli_query($conn, "SELECT * FROM property WHERE state='$state' AND DATE_FORMAT(date, '%Y-%m')='$transday[dday]' ORDER BY id DESC");
if(mysqli_num_rows($check)>0){
	while($referral = mysqli_fetch_array($check)){
		if($referral['status'] =="Public"){
			$status = "success_color";
			$badge = "bg-primary";
		}else{
			$status = "critical_color";
			$badge = "bg-danger";
		}

		?>
<a class="tf-trading-history bg-white px-2 py-3 allfilters <?php print $referral['canpay_installment'];?>" style="border-radius:10px" href="/propertydetails/<?php print $referral['id'];?>">
	<div class="inner-left">
		<div class="content">
			<h4 style="width:100%"><?php print ucwords($referral['title']);?></h4>
			<p class="text-danger"><?php print ucwords($referral['canpay_installment']);?> | <?php print ucwords($referral['plots']);?></p>
<div>
<small class="badge <?php print $badge;?>"><?php print ucwords($referral['status']);?></small>
<span style="float:right" class="num-val <?php print $status;?>"> ₦ <?php print number_format($referral['amount']);?></span>
</div>
		</div>
	</div>
	
</a>
<?php } } ?>
                           
                        </div>
                </div>
<?php }}

}else{
	echo '<div class="alert alert-danger">Update your app to see your bonus history</div>';
}
 ?>