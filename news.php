<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");

$check =mysqli_query($conn, "SELECT * FROM news");
if(mysqli_num_rows($check)>0){
	while($noty = mysqli_fetch_array($check)){
?>
 <div class="noti-list">
<div class="icon-box bg_service-4">
   <img src="images/bell.png" alt="" style="height:20px;width:20px" />
		
</div>
<div class="content-right">
	<div class="title">
		<h3 class="fw_6"><?php print $noty['title'];?></h3>
	
	</div>
	<div class="desc">
		<p class="on_surface_color fw_4"><?php print $noty['message'];?></p>
		<?php if($noty['status']=="unread"){?>
		<i class="dot"></i>
		<?php }?>
	</div>
</div>
</div>
<?php } }
 ?>