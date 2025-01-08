<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
$myuid = mysqli_real_escape_string($conn,$_GET['myuid']);
?>
<div class="trading-month">

 <div class="group-trading-history mb-5 m-0">
 <?php
 $checkwho = mysqli_query($conn, "SELECT * FROM tickets WHERE userid='$myuid'");
if(mysqli_num_rows($checkwho)>0){
while($ticket = mysqli_fetch_array($checkwho)){
?>
                            <a class="tf-trading-history bg-white shadow-sm p-2 <?php print strtolower($ticket['status']);?>" href="/respond/<?php print $ticket['id'];?>/<?php print $myuid;?>" style="border-radius:10px" >
                                <div class="inner-left">
                                  
									<div class="thumb" style="object-fit:cover;background:#f1f1f1;border:1px solid #adadad">
                                        <img src="images/customer.png" alt=""  />
                                    </div>
                                    <div class="content">
                                        <h4 style="line-height:10px"><?php print ucwords($ticket['category']);?></h4>
                                        <p class="text-primary"><?php print substr($ticket['message'],0,40);?></p>
                                        <p class="text-danger" style="font-size:8px;line-height:7px">Ticket No: <?php print $ticket['ticketno'];?></p>
                                    </div>
                                </div>
                                <div><span class="badge <?php if($ticket['status']=="resolved"){?>bg-success<?php }else{?>bg-danger<?php }?>"><?php print $ticket['status'];?></span></div>
                            </a>
							



<?php
}
 }else{ ?>
		<div class="alert bg-danger">You have not created any ticket. <a href="/tech-support/">Click here to start</a></div>
	<?php
	}
?>
</div>
</div>