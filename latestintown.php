<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
$state = mysqli_real_escape_string($conn,$_GET['state']);

$check =mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE state='$state'  ORDER BY id DESC LIMIT 10");
if(mysqli_num_rows($check)>0){
	while($latest = mysqli_fetch_array($check)){
?>
 <div class="swiper-slide">
	<a class="recipient-box btn-repicient" href="#">
		<img src="<?php print $kaylink;?>/pix/thumb/<?php print $latest['pix'];?>" alt="images">
		<?php print $latest['fname'];?>
	</a>
</div>
<?php } } ?>