<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');
include_once("conn.php");
$me = mysqli_real_escape_string($conn, $_GET['id']);
$check = mysqli_query($conn, "SELECT * FROM benefitiary WHERE owner_id='$me'");
while($bank = mysqli_fetch_array($check)){
?>
<div class="tf-card-block d-flex align-items-center justify-content-between mb-2">
	<div class="inner d-flex align-items-center">
		<i class="logo icon-wallet-filled-money-tool"></i>
		<div class="content">
			<h4><a href="#" class="fw_6"><?php print $bank['bank_nane'];?></a></h4>
			<p><?php print $bank['acc_name'];?></p>
		</div>
	</div>
	<input type="radio" checked="" name="radio">
</div>
<?php }?>