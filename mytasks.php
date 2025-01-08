<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include("timed.php");
include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);

$checkwho = mysqli_query($conn,"SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE userid='$me'");
$adminz = mysqli_fetch_array($checkwho);
if($adminz['account_type']=="Account Officer"){
	//if agent is collector
	
		
	
$fortoday =  mysqli_query($conn, "SELECT id, date, DATE_FORMAT(date, '%Y-%m-%d') AS dday FROM users WHERE account_no='$me' GROUP BY dday ORDER BY id DESC") OR die(mysqli_error($conn));


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
$check =mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE account_no='$me' AND DATE_FORMAT(date, '%Y-%m-%d')='$transday[dday]' ORDER BY id DESC");
if(mysqli_num_rows($check)>0){
	while($users = mysqli_fetch_array($check)){
		if($users['status'] =="active"){
			$status = "success_color";
			$allstats = $users['status'];
		}elseif($users['status'] =="reset"){
			$allstats = "incomplete";
			$status = "critical_color";
		}else{
			$allstats = $users['status'];
			$status = "critical_color";
		}		
		
$checkwh = mysqli_query($conn,"SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE userid='$me'");
$admins = mysqli_fetch_array($checkwh);
if($admins['account_type']=="Account Officer"){
	
	if($users['agent']=="pend$me"){
			$allm = "alerty";
			$linky = "#";
			}else{
				$linky = "/userprofile/".$users['id']."/".$users['fname'].' '.$users['lname'];
				$allm ="";
			}
		
}else{
	$allm = "notcollector";
	$linky = "#";
}

		?>
                            <a class="cl<?php print $allstats;?> tf-trading-history bg-white p-2 shadow-xs <?php print $status;?> <?php print $allm;?>" href="<?php print $linky;?>" style="border-radius:5px">
                                <div class="inner-left">
                                  
									<div class="thumb" style="object-fit:cover">
                                        <img src="<?php print $kaylink;?>/pix/thumb/<?php print $users['pix'];?>" alt="image" style="object-fit:cover;height:40px;width:40px" />
                                    </div>
                                    <div class="content">
                                        <h4><?php print ucwords($users['fname'].' '.$users['lname']);?></h4>
                                        <p><b>Task:</b> Manage Customer | 
										<?php if($users['status']=="unverified"){ echo 'Incomplete Profile - OTP is : '.$users['otpcode'];}else{?><?php print $users['region'];?>, <?php print $users['state'];?><?php }?>
										
										</p>
                                    </div>
                                </div>
                                <span class="num-val "><i class="icon-more1"></i></span>
                            </a>
<?php } } ?>
                           
                        </div>
                </div>
<?php }
mysqli_query($conn, "UPDATE referral SET seen_status='read' WHERE agentid='$me'");
}else{ ?>
<div class="alert alert-info">No available task right now</div>
<?php }

}elseif($adminz['account_type']=="logistics"){
	
	
		
	
$fortoday =  mysqli_query($conn, "SELECT id, date, DATE_FORMAT(date, '%Y-%m-%d') AS dday FROM book_inspection GROUP BY dday ORDER BY id DESC") OR die(mysqli_error($conn));


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
$check =mysqli_query($conn, "SELECT * FROM book_inspection WHERE DATE_FORMAT(date, '%Y-%m-%d')='$transday[dday]' ORDER BY id DESC");
if(mysqli_num_rows($check)>0){
	while($visits = mysqli_fetch_array($check)){
$checku=mysqli_query($conn,"SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$visits[userid]'");
$users = mysqli_fetch_array($checku);
	?>
                            <a class="sheet-open tf-trading-history bg-white p-2 shadow-sm" href="#" style="border-radius:5px" data-sheet=".propopt<?php print $visits['id'];?>">
                                <div class="inner-left">
                                  
									<div class="thumb" style="object-fit:cover">
                                        <img src="<?php print $kaylink;?>/pix/thumb/<?php print $users['pix'];?>" alt="image" style="object-fit:cover;height:40px;width:40px" />
                                    </div>
                                    <div class="content">
                                        <h4><?php print ucwords($visits['custnames']);?></h4>
                                        <p><b>Inspection:</b>  
										 <?php print $visits['days'];?> | 
										 <span class="badge <?php if($visits['status']=="Approved" || $visits['status']=="approved"){?>bg-success<?php }else{?>bg-danger<?php }?>"><?php print $visits['status'];?></span>
										
										</p>
                                    </div>
                                </div>
                                <span class="num-val "><i class="icon-more1"></i></span>
                            </a>
							
							
	<div class="sheet-modal propopt<?php print $visits['id'];?>" style="height:50%;background:transparent">
      <div class="sheet-modal-inner" style="background:transparent">
 <div class="tf-panel up panel-open">
   
          <form class="tf-form verifypayment">
		  
		  <div class="panel-box panel-up wrap-panel-clear panel-change-profile">
            <div class="heading">
                <a class="custpaybtn sheet-close" data-dhref="/inspection/<?php print $visits['id'];?>">Reschedule Request</a>
                <a class="approvetask sheet-close" data-id="<?php print $visits['id'];?>" data-status="Approved" data-action="approve" data-userid="<?php print $users['id'];?>">Approve Request</a>

                <a class="approvetask sheet-close" data-id="<?php print $visits['id'];?>" data-status="Declined" data-action="decline"  data-userid="<?php print $users['id'];?>">Decline Request</a>
            </div>
            

			<div class="bottom">
                <a class=" sheet-close" href="#">Dismiss</a>
            </div>
          </div>
		  </form>
    </div>
	
	
  </div>
</div>


<?php } } ?>
                           
                        </div>
                </div>
<?php }
mysqli_query($conn, "UPDATE referral SET seen_status='read' WHERE agentid='$me'");
}else{ ?>
<div class="alert alert-info">No available task right now</div>
<?php }
	
}


?>
