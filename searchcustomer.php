<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$uid = mysqli_real_escape_string($conn,$_GET['uid']);
$check = mysqli_query($conn,"SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE userid='$uid'");
if(mysqli_num_rows($check)>0){
while($users = mysqli_fetch_array($check)){
	if($users['agent']==""){
	if($users['status'] =="active"){
			$status = "success_color";
		}elseif($users['status'] =="reset"){
			$status = "critical_color";
		}else{
			$status = "critical_color";
		}
		?>
	 <div class="tf-trading-history bg-white p-2 shadow-xs <?php print $status;?>  selectcust" style="border-radius:5px" data-id="<?php print $users['id'];?>" data-names="<?php print $users['fname'].' '.$users['lname'];?>">
                                <div class="inner-left">
                                  
									<div class="thumb" style="object-fit:cover">
                                        <img src="<?php print $kaylink;?>/pix/thumb/<?php print $users['pix'];?>" alt="image" style="object-fit:cover;height:40px;width:40px" />
                                    </div>
                                    <div class="content">
                                        <h4><?php print ucwords($users['fname'].' '.$users['lname']);?></h4>
                                        <p><?php print ucwords($users['account_type']);?> - <?php if($users['status']=="unverified"){ echo 'Unverified Account'; }else{ echo $users['region'].'  '.$users['state']; }?></p>
                                    </div>
                                </div>
                                <a href="#" class="num-val selectcust p-2" data-id="<?php print $users['id'];?>" data-names="<?php print $users['fname'].' '.$users['lname'];?>"><i class="icon-plus"></i></a>
                            </div>
<?php
}else{?>
<div class="alert alert-info">Account already assigned to an Account Officer</div>
<?php }
}
}else{
?>
<div class="alert alert-warning">No Account Found for UID: <?php print $uid;?></div>
<?php }?>