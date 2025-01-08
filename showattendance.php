<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include("timed.php");
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
$checkwho = mysqli_query($conn,"SELECT * FROM attendance WHERE userid='$me' AND MONTH(date)=MONTH(CURDATE())");
if(mysqli_num_rows($checkwho)>0){
while($adminz = mysqli_fetch_array($checkwho)){
	$checku = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$adminz[userid]'");
	$ufoto = mysqli_fetch_array($checku);
		?>
                            <a class=" tf-trading-history bg-white p-2 shadow-xs success_color" href="#" style="border-radius:5px">
                                <div class="inner-left">
                                  
									<div class="thumb" style="object-fit:cover">
                                        <img src="<?php print $kaylink;?>/pix/thumb/<?php print $ufoto['pix'];?>" alt="image" style="object-fit:cover;height:40px;width:40px" />
                                    </div>
                                    <div class="content">
                                        <h4><?php print date('l, F d, Y',strtotime($adminz['date']));?></h4>
                                        <p><b class="text-primary"><?php print ucwords($adminz['checktype']);?> Time:</b> <?php print date('h:i:sa',strtotime($adminz['checkin']));?></p>
                                        
                                    </div>
                                </div>
                                <span class="num-val checkout" data-userid="<?php print $adminz['id'];?>"><i class="material-icons text-danger">logout</i></span>
                            </a>
<?php } } ?>