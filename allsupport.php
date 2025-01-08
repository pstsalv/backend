<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");
?>
<div style="margin-top:-140px"></div>
<?php
$check =mysqli_query($conn, "SELECT * FROM users WHERE position='tech national'");
if(mysqli_num_rows($check)>0){
	while($user = mysqli_fetch_array($check)){
		?>
 <li>
                            <a href="https://api.whatsapp.com/send?phone=234<?php print $user['phone'];?>&text=Hello%20Bliss%20Legacy%20Support%20I%20need%20your%20help" class="recipient-list link external" target="_blank">
                                <ul class="inner">
                                    <li class="user">
                                        <img src="<?php print $kaylink;?>/pix/thumb/<?php print $user['pix'];?>" alt="image">
                                    </li>
                                    <li class="info">
                                        <h4><?php print $user['fname'];?> <?php print $user['lname'];?></h4>
                                        <p><?php print $user['position'];?> | <?php print $user['state'];?></p>
                                    </li>
                                </ul>
                                <ul class="alphabet">
                                    <li><span class="material-icons text-primary">perm_phone_msg</span></li>
                                  
                                </ul>
                            </a>
                        </li>
<?php } }else{?>
<div class="text-center">
<img src="<?php print $kaylink;?>/pix/callus.png" style="width:100%;height:auto" alt= />
<a href="tel:07000325477" class="external btn btn-primary">Call Us Now 07000325477</a><br>
<b>OR</b><br>
<a href="/tech-support/">Open a ticket</a>
</div>

<?php }?>