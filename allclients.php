<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache'); 

include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
?>

<div class="app-section mt-0 bg_white_color mb-3 border p-0">
                <div class="tf-container1">
                    <div class="tf-tab">
                        <ul class="menu-tabs timeline mb-5 mt-0 px-0" style="gap: 0px;">
                            <li class="nav-tab active w-50 text-center" style="border-radius: 0px;font-size:11px" onclick="hideshow('tab1');">Clients</li>

                            <li class="nav-tab w-50 text-center" style="border-radius: 0px;font-size:11px" onclick="hideshow('tab2');">2nd Generation</li>
                            <li class="nav-tab w-50 text-center" style="border-radius: 0px;font-size:11px" onclick="hideshow('tab3');">Performance</li>
                          
                        </ul>
                        <div class="wrap-chart">
                      
                                <div class="content-tab">
                                    <div class="chart-item chartall" id="tab1">

<?php
$checkwho = mysqli_query($conn,"SELECT * FROM users WHERE userid='$me'");
$adminz = mysqli_fetch_array($checkwho);
if($adminz['position']=="Account Officer" || $adminz['position']=="Marketers"){
	//if agent is Collector
		?>
		<h2 class="p-1 border-bottom">Your Assigned Clients</h2>

<div class="trading-month p-2 border">
                 
                        <div class="group-trading-history mb-5">
<?php
$check =mysqli_query($conn, "SELECT * FROM users WHERE account_no='$me' ORDER BY id DESC");
if(mysqli_num_rows($check)>0){
	while($users = mysqli_fetch_array($check)){

$checkcustup = mysqli_query($conn, "SELECT id FROM payment WHERE userid='$users[id]'");
		if(mysqli_num_rows($checkcustup)>0){
			$status = "success_color";
			$allstats = 'active';
		}else{
			$allstats = 'inactive';
			$status = "critical_color";
		}


//calculate all the amount this user has paid
$checkrevv2 = mysqli_query($conn,"SELECT SUM(amount) AS revenuev2 FROM  payment WHERE userid='$users[id]' AND propert_type!='promo' AND propert_type!='outright'");
$rowppv2 = mysqli_fetch_assoc($checkrevv2);
$sumppv2 = $rowppv2['revenuev2'];
$totalpaydv2 = "$sumppv2";
if($totalpaydv2!==""){
$allrevdv2 = $totalpaydv2;
}else{
$allrevdv2 = "0";
};


if($allrevdv2==0){
$pertotal2 = 0;
}else{
$pertotal2 = $allrevdv2;
}

//calculate the expected amount by the user
$checkrevvpro2 = mysqli_query($conn,"SELECT SUM(propamount) AS revenuevpro3 FROM  myproperty WHERE userid='$users[id]' AND type!='promo' AND type!='outright'");
$rowppvpro2 = mysqli_fetch_assoc($checkrevvpro2);
$sumppvpro2 = $rowppvpro2['revenuevpro3'];
$totalpaydvpro2 = "$sumppvpro2";
if($totalpaydvpro2!==""){
$allrevdvpro2 = $totalpaydvpro2;
}else{
$allrevdvpro2 = "0";
};

if($allrevdvpro2==0){
$allrevdvpro22 = 0;
}else{
$allrevdvpro22 = $allrevdvpro2;
}

if($allrevdvpro22==0){
$pertotal27 = 0;
}else{
$pertotal27 = round($pertotal2/$allrevdvpro22);
}
		
$checkwh = mysqli_query($conn,"SELECT * FROM users WHERE userid='$me'");
$admins = mysqli_fetch_array($checkwh);
if($admins['position']=="Account Officer" || $admins['position']=="Marketers"){
	
	if($users['agent']=="pend$me"){
			$allm = "alerty";
			$linky = "#";
			}else{
				$linky = "/userprofile/".$users['id']."/".$users['fname'].' '.$users['lname'];
				$allm ="";
			}
		
}else{
	$allm = "notCollector";
	$linky = "#";
}

if($adminz['position']=="Account Officer" || $adminz['position']=="Marketers"){
	//if agent is Collector
$acctyped = "Assigned";
	$linka = '<a class="cl'.$allstats.' tf-trading-history bg-white p-2 shadow-xs '.$allm.'" href="'.$linky.'" style="border-radius:5px">';
}else{
$acctyped = "Referred";
$linka =' <a class="cl'.$allstats.' tf-trading-history bg-white p-2 w-100 shadow-xs '. $status.' '.$allm.'  sheet-open" data-sheet=".propopt'.$users['id'].'" href="'.$linky.'" style="border-radius:5px">';
}

		?>
                            <?php print $linka;?>
                                <div class="inner-left">
                                  
									<div class="thumb" style="object-fit:cover">

<span class="badge bg-primary" style="position:absolute;margin-top:27px;margin-left:4px;font-size:9px;padding-top:0;padding-bottom:0px"><?php print $pertotal27;?>%</span>

                                        <img src="<?php print $kaylink;?>/pix/thumb/<?php print $users['pix'];?>" alt="image" style="object-fit:cover;height:40px;width:40px" />
                                    </div>
                                    <div class="content">
                                      
 <h4><?php print ucwords($users['fname'].' '.$users['lname']);?> <span class="badge bg-danger" style="font-size:8px; padding:0px 7px;float:right"><?php print ucwords($acctyped);?></span></h4>


                                        <p ><?php if($users['agent']=="pend$me"){ echo 'Request Pending Approval';}else{?>
										<?php if($users['status']=="unverified"){ echo 'Incomplete Profile - OTP is : '.$users['otpcode'];}else{?><?php print $users['region'];?>, <?php print $users['state'];?><?php }?>
										<?php }?>
										</p>
<div class="progress progress-sm flex-grow-1" title="<?php print $allrevdv2;?>" style="min-width: 250px;height:10px">
    <div class="progress-bar bg-primary rounded" role="progressbar" style="width: <?php print $pertotal27;?>%;height:10px;font-size:7px" aria-valuenow="<?php print $pertotal27;?>" aria-valuemin="0" aria-valuemax="100"><?php print $pertotal27;?>%</div>
</div>
                                    </div>
                                </div>
                                <span class="num-val "><i class="icon-more1"></i></span>
                            </a>

<?php 
mysqli_query($conn, "UPDATE referral SET seen_status='read' WHERE agentid='$me'");

?>
	
	<div class="sheet-modal propopt<?php print $users['id'];?>" style="height:70%;background:transparent">
      <div class="sheet-modal-inner" style="background:transparent">
 <div class="tf-panel up panel-open">
   
          <form class="tf-form verifypayment">
		  
		  <div class="panel-box panel-up wrap-panel-clear panel-change-profile">
            <div class="heading">
              <a class="external sheet-close" href="tel:<?php print $users['phone'];?>">Call <?php print $users['fname'];?></a>

			 <a class="custpaybtn sheet-close" data-dhref="/rezone/<?php print $users['id'];?>/<?php print $users['pix'];?>/<?php print $users['fname'];?> <?php print $users['lname'];?>">Rezone <?php print $users['fname'];?></a>

			
				<a class="external sheet-close unlinkcustomer" data-customer="<?php print $users['id'];?>">Unlink <?php print $users['fname'];?></a>
				
				<a class="custpaybtn sheet-close" data-dhref="/myproperty/<?php print $users['id'];?>">Payments & Subscriptions</a>
	<?php 
	$checksub = mysqli_query($conn, "SELECT id FROM myproperty WHERE userid='$users[id]'");
	if(mysqli_num_rows($checksub)>0){
		
	?>
                <a class="custpaybtn sheet-close" data-dhref="/newaccount/<?php print $users['id'];?>/<?php print $users['fname'];?>">Generate Virtual Account</a>
	<?php }else{?>
	  <a class="custpaybtn sheet-close" data-dhref="/allproperty/">Buy Land & Generate Account</a>
	<?php }?>
				
				<a class="sheet-open" data-sheet=".validatepay<?php print $users['id'];?>" data-customer="<?php print $users['id'];?>">Validate <?php print ucwords($users['fname']);?>'s Payment</a>
            </div>
            

			<div class="bottom">
                <a class=" sheet-close" href="#">Dismiss</a>
            </div>
          </div>
		  </form>
    </div>
	
	
  </div>
</div>



<div class="sheet-modal validatepay<?php print $users['id'];?>" style="height:100%;background:transparent">
      <div class="sheet-modal-inner" style="background:transparent">
 <div class="tf-panel up panel-open">
   
          <form class="tf-form verifypayment">
		  
		  <div class="panel-box panel-up wrap-panel-clear panel-change-profile">
            <div class="heading bg-white" style="padding:5px">
                
<input type="" name="me" class="meuser" value="<?php print $users['id'];?>" />
            
                <div class="mt-4">
 <div class="group-input">
                    <label style="color:#000 !important"><?php print ucwords($users['fname']);?>'s Payment Reference</label>
                    <input type="text" placeholder="Enter <?php print $users['fname'];?>'s payment referece" name="payref" required />
                    </div>
					
                    <div class="group-input input-field input-money">
                    <label style="color:#000 !important">Property <?php print ucwords($users['fname']);?> Paid For</label>
                   <select name="property" class="payprop">
					<option>Select your property</option>
					
					<option value="" disabled selected>Select your property</option>
<?php $chkp=mysqli_query($conn,"SELECT id,userid,propertyid,amount,status,date,planid,propuid,payduration,initial_deposit,amt_due,propamount,amt_paid,amt_remain FROM myproperty WHERE userid='$users[id]'");
if(mysqli_num_rows($chkp)>0){
	while($prop=mysqli_fetch_array($chkp)){?>
<option value="<?php print $prop['propertyid'];?>" data-inital="<?php print round($prop['amt_due'],0);?>"><?php print $prop['propuid'];?></option>
<?php }}else{?>
<option value="" disabled><?php print ucwords($users['fname']);?> is not subscribed to any property yet</option>
<?php }?>


					</select>
                   
                    </div>
                   
                </div>

				
            </div>
            <div class="bottom mb-1">
                <button class="tf-btn accent large">Verify Payment</button>
            </div>

			<div class="bottom">
                <a class=" sheet-close" href="#">Dismiss</a>
            </div>
          </div>
		  </form>
    </div>
	
	
  </div>
</div>

<?php }
?>
                           
                        </div>
                </div>							


<?php
}
?>



		<h2 class="p-1 border-bottom">Your Registered Clients</h2>

<div class="trading-month p-2 border">
                 
                        <div class="group-trading-history mb-5">
<?php
$check =mysqli_query($conn, "SELECT * FROM users WHERE agent='$me' ORDER BY id DESC");
if(mysqli_num_rows($check)>0){
	while($users = mysqli_fetch_array($check)){

$checkcustup = mysqli_query($conn, "SELECT id FROM payment WHERE userid='$users[id]'");
		if(mysqli_num_rows($checkcustup)>0){
			$status = "success_color";
			$allstats = 'active';
		}else{
			$allstats = 'inactive';
			$status = "critical_color";
		}


//calculate all the amount this user has paid
$checkrevv2 = mysqli_query($conn,"SELECT SUM(amount) AS revenuev2 FROM  payment WHERE userid='$users[id]' AND propert_type!='promo' AND propert_type!='outright'");
$rowppv2 = mysqli_fetch_assoc($checkrevv2);
$sumppv2 = $rowppv2['revenuev2'];
$totalpaydv2 = "$sumppv2";
if($totalpaydv2!==""){
$allrevdv2 = $totalpaydv2;
}else{
$allrevdv2 = "0";
};


if($allrevdv2==0){
$pertotal2 = 0;
}else{
$pertotal2 = $allrevdv2;
}

//calculate the expected amount by the user
$checkrevvpro2 = mysqli_query($conn,"SELECT SUM(propamount) AS revenuevpro3 FROM  myproperty WHERE userid='$users[id]' AND type!='promo' AND type!='outright'");
$rowppvpro2 = mysqli_fetch_assoc($checkrevvpro2);
$sumppvpro2 = $rowppvpro2['revenuevpro3'];
$totalpaydvpro2 = "$sumppvpro2";
if($totalpaydvpro2!==""){
$allrevdvpro2 = $totalpaydvpro2;
}else{
$allrevdvpro2 = "0";
};

if($allrevdvpro2==0){
$allrevdvpro22 = 0;
}else{
$allrevdvpro22 = $allrevdvpro2;
}

if($allrevdvpro22==0){
$pertotal27 = 0;
}else{
$pertotal27 = round($pertotal2/$allrevdvpro22);
}
		
$checkwh = mysqli_query($conn,"SELECT * FROM users WHERE userid='$me'");
$admins = mysqli_fetch_array($checkwh);
if($admins['position']=="Account Officer" || $admins['position']=="Marketers"){
	
	if($users['agent']=="pend$me"){
			$allm = "alerty";
			$linky = "#";
			}else{
				$linky = "/userprofile/".$users['id']."/".$users['fname'].' '.$users['lname'];
				$allm ="";
			}
		
}else{
	$allm = "notCollector";
	$linky = "#";
}

if($adminz['position']=="Account Officer" || $adminz['position']=="Marketers"){
	//if agent is Collector
$acctyped = "Assigned";
	$linka = '<a class="cl'.$allstats.' tf-trading-history bg-white p-2 shadow-xs '.$allm.'" href="'.$linky.'" style="border-radius:5px">';
}else{
$acctyped = "Referred";
$linka =' <a class="cl'.$allstats.' tf-trading-history bg-white p-2 w-100 shadow-xs '. $status.' '.$allm.'  sheet-open" data-sheet=".propopt'.$users['id'].'" href="'.$linky.'" style="border-radius:5px">';
}

		?>
                            <?php print $linka;?>
                                <div class="inner-left">
                                  
									<div class="thumb" style="object-fit:cover">

<span class="badge bg-primary" style="position:absolute;margin-top:27px;margin-left:4px;font-size:9px;padding-top:0;padding-bottom:0px"><?php print $pertotal27;?>%</span>

                                        <img src="<?php print $kaylink;?>/pix/thumb/<?php print $users['pix'];?>" alt="image" style="object-fit:cover;height:40px;width:40px" />
                                    </div>
                                    <div class="content">
                                      
 <h4><?php print ucwords($users['fname'].' '.$users['lname']);?> <span class="badge bg-danger" style="font-size:8px; padding:0px 7px;float:right"><?php print ucwords($acctyped);?></span></h4>


                                        <p ><?php if($users['agent']=="pend$me"){ echo 'Request Pending Approval';}else{?>
										<?php if($users['status']=="unverified"){ echo 'Incomplete Profile - OTP is : '.$users['otpcode'];}else{?><?php print $users['region'];?>, <?php print $users['state'];?><?php }?>
										<?php }?>
										</p>
<div class="progress progress-sm flex-grow-1" title="<?php print $allrevdv2;?>" style="min-width: 250px;height:10px">
    <div class="progress-bar bg-primary rounded" role="progressbar" style="width: <?php print $pertotal27;?>%;height:10px;font-size:7px" aria-valuenow="<?php print $pertotal27;?>" aria-valuemin="0" aria-valuemax="100"><?php print $pertotal27;?>%</div>
</div>
                                    </div>
                                </div>
                                <span class="num-val "><i class="icon-more1"></i></span>
                            </a>

<?php 
mysqli_query($conn, "UPDATE referral SET seen_status='read' WHERE agentid='$me'");

?>
	
	<div class="sheet-modal propopt<?php print $users['id'];?>" style="height:70%;background:transparent">
      <div class="sheet-modal-inner" style="background:transparent">
 <div class="tf-panel up panel-open">
   
          <form class="tf-form verifypayment">
		  
		  <div class="panel-box panel-up wrap-panel-clear panel-change-profile">
            <div class="heading">
              <a class="external sheet-close" href="tel:<?php print $users['phone'];?>">Call <?php print $users['fname'];?></a>

			 <a class="custpaybtn sheet-close" data-dhref="/rezone/<?php print $users['id'];?>/<?php print $users['pix'];?>/<?php print $users['fname'];?> <?php print $users['lname'];?>">Rezone <?php print $users['fname'];?></a>

			
				<a class="external sheet-close unlinkcustomer" data-customer="<?php print $users['id'];?>">Unlink <?php print $users['fname'];?></a>
				
				<a class="custpaybtn sheet-close" data-dhref="/myproperty/<?php print $users['id'];?>">Payments & Subscriptions</a>
	<?php 
	$checksub = mysqli_query($conn, "SELECT id FROM myproperty WHERE userid='$users[id]'");
	if(mysqli_num_rows($checksub)>0){
		
	?>
                <a class="custpaybtn sheet-close" data-dhref="/newaccount/<?php print $users['id'];?>/<?php print $users['fname'];?>">Generate Virtual Account</a>
	<?php }else{?>
	  <a class="custpaybtn sheet-close" data-dhref="/allproperty/">Buy Land & Generate Account</a>
	<?php }?>
				
				<a class="sheet-open" data-sheet=".validatepay<?php print $users['id'];?>" data-customer="<?php print $users['id'];?>">Validate <?php print ucwords($users['fname']);?>'s Payment</a>
            </div>
            

			<div class="bottom">
                <a class=" sheet-close" href="#">Dismiss</a>
            </div>
          </div>
		  </form>
    </div>
	
	
  </div>
</div>



<div class="sheet-modal validatepay<?php print $users['id'];?>" style="height:100%;background:transparent">
      <div class="sheet-modal-inner" style="background:transparent">
 <div class="tf-panel up panel-open">
   
          <form class="tf-form verifypayment">
		  
		  <div class="panel-box panel-up wrap-panel-clear panel-change-profile">
            <div class="heading bg-white" style="padding:5px">
                
<input type="" name="me" class="meuser" value="<?php print $users['id'];?>" />
            
                <div class="mt-4">
 <div class="group-input">
                    <label style="color:#000 !important"><?php print ucwords($users['fname']);?>'s Payment Reference</label>
                    <input type="text" placeholder="Enter <?php print $users['fname'];?>'s payment referece" name="payref" required />
                    </div>
					
                    <div class="group-input input-field input-money">
                    <label style="color:#000 !important">Property <?php print ucwords($users['fname']);?> Paid For</label>
                   <select name="property" class="payprop">
					<option>Select your property</option>
					
					<option value="" disabled selected>Select your property</option>
<?php $chkp=mysqli_query($conn,"SELECT id,userid,propertyid,amount,status,date,planid,propuid,payduration,initial_deposit,amt_due,propamount,amt_paid,amt_remain FROM myproperty WHERE userid='$users[id]'");
if(mysqli_num_rows($chkp)>0){
	while($prop=mysqli_fetch_array($chkp)){?>
<option value="<?php print $prop['propertyid'];?>" data-inital="<?php print round($prop['amt_due'],0);?>"><?php print $prop['propuid'];?></option>
<?php }}else{?>
<option value="" disabled><?php print ucwords($users['fname']);?> is not subscribed to any property yet</option>
<?php }?>


					</select>
                   
                    </div>
                   
                </div>

				
            </div>
            <div class="bottom mb-1">
                <button class="tf-btn accent large">Verify Payment</button>
            </div>

			<div class="bottom">
                <a class=" sheet-close" href="#">Dismiss</a>
            </div>
          </div>
		  </form>
    </div>
	
	
  </div>
</div>

<?php }
}
?>
                           
                        </div>
                </div>							










<?php
}else{
 ?>




		<h2 class="p-1 border-bottom">Your Registered Clients</h2>

<div class="trading-month p-2 border">
                 
                        <div class="group-trading-history mb-5">
<?php
$check =mysqli_query($conn, "SELECT * FROM users WHERE agent='$me' ORDER BY id DESC");
if(mysqli_num_rows($check)>0){
	while($users = mysqli_fetch_array($check)){

$checkcustup = mysqli_query($conn, "SELECT id FROM payment WHERE userid='$users[id]'");
		if(mysqli_num_rows($checkcustup)>0){
			$status = "success_color";
			$allstats = 'active';
		}else{
			$allstats = 'inactive';
			$status = "critical_color";
		}


//calculate all the amount this user has paid
$checkrevv2 = mysqli_query($conn,"SELECT SUM(amount) AS revenuev2 FROM  payment WHERE userid='$users[id]' AND propert_type!='promo' AND propert_type!='outright'");
$rowppv2 = mysqli_fetch_assoc($checkrevv2);
$sumppv2 = $rowppv2['revenuev2'];
$totalpaydv2 = "$sumppv2";
if($totalpaydv2!==""){
$allrevdv2 = $totalpaydv2;
}else{
$allrevdv2 = "0";
};


if($allrevdv2==0){
$pertotal2 = 0;
}else{
$pertotal2 = $allrevdv2;
}

//calculate the expected amount by the user
$checkrevvpro2 = mysqli_query($conn,"SELECT SUM(propamount) AS revenuevpro3 FROM  myproperty WHERE userid='$users[id]' AND type!='promo' AND type!='outright'");
$rowppvpro2 = mysqli_fetch_assoc($checkrevvpro2);
$sumppvpro2 = $rowppvpro2['revenuevpro3'];
$totalpaydvpro2 = "$sumppvpro2";
if($totalpaydvpro2!==""){
$allrevdvpro2 = $totalpaydvpro2;
}else{
$allrevdvpro2 = "0";
};

if($allrevdvpro2==0){
$allrevdvpro22 = 0;
}else{
$allrevdvpro22 = $allrevdvpro2;
}

if($allrevdvpro22==0){
$pertotal27 = 0;
}else{
$pertotal27 = round($pertotal2/$allrevdvpro22);
}
		
$checkwh = mysqli_query($conn,"SELECT * FROM users WHERE userid='$me'");
$admins = mysqli_fetch_array($checkwh);
if($admins['position']=="Account Officer" || $admins['position']=="Marketers"){
	
	if($users['agent']=="pend$me"){
			$allm = "alerty";
			$linky = "#";
			}else{
				$linky = "/userprofile/".$users['id']."/".$users['fname'].' '.$users['lname'];
				$allm ="";
			}
		
}else{
	$allm = "notCollector";
	$linky = "#";
}

if($adminz['position']=="Account Officer" || $adminz['position']=="Marketers"){
	//if agent is Collector
$acctyped = "Assigned";
	$linka = '<a class="cl'.$allstats.' tf-trading-history bg-white p-2 shadow-xs '.$allm.'" href="'.$linky.'" style="border-radius:5px">';
}else{
$acctyped = "Referred";
$linka =' <a class="cl'.$allstats.' tf-trading-history bg-white p-2 w-100 shadow-xs '. $status.' '.$allm.'  sheet-open" data-sheet=".propopt'.$users['id'].'" href="'.$linky.'" style="border-radius:5px">';
}

		?>
                            <?php print $linka;?>
                                <div class="inner-left">
                                  
									<div class="thumb" style="object-fit:cover">

<span class="badge bg-primary" style="position:absolute;margin-top:27px;margin-left:4px;font-size:9px;padding-top:0;padding-bottom:0px"><?php print $pertotal27;?>%</span>

                                        <img src="<?php print $kaylink;?>/pix/thumb/<?php print $users['pix'];?>" alt="image" style="object-fit:cover;height:40px;width:40px" />
                                    </div>
                                    <div class="content">
                                      
 <h4><?php print ucwords($users['fname'].' '.$users['lname']);?> <span class="badge bg-danger" style="font-size:8px; padding:0px 7px;float:right"><?php print ucwords($acctyped);?></span></h4>


                                        <p ><?php if($users['agent']=="pend$me"){ echo 'Request Pending Approval';}else{?>
										<?php if($users['status']=="unverified"){ echo 'Incomplete Profile - OTP is : '.$users['otpcode'];}else{?><?php print $users['region'];?>, <?php print $users['state'];?><?php }?>
										<?php }?>
										</p>
<div class="progress progress-sm flex-grow-1" title="<?php print $allrevdv2;?>" style="min-width: 250px;height:10px">
    <div class="progress-bar bg-primary rounded" role="progressbar" style="width: <?php print $pertotal27;?>%;height:10px;font-size:7px" aria-valuenow="<?php print $pertotal27;?>" aria-valuemin="0" aria-valuemax="100"><?php print $pertotal27;?>%</div>
</div>
                                    </div>
                                </div>
                                <span class="num-val "><i class="icon-more1"></i></span>
                            </a>

<?php 
mysqli_query($conn, "UPDATE referral SET seen_status='read' WHERE agentid='$me'");

?>
	
	<div class="sheet-modal propopt<?php print $users['id'];?>" style="height:70%;background:transparent">
      <div class="sheet-modal-inner" style="background:transparent">
 <div class="tf-panel up panel-open">
   
          <form class="tf-form verifypayment">
		  
		  <div class="panel-box panel-up wrap-panel-clear panel-change-profile">
            <div class="heading">
              <a class="external sheet-close" href="tel:<?php print $users['phone'];?>">Call <?php print $users['fname'];?></a>

			 <a class="custpaybtn sheet-close" data-dhref="/rezone/<?php print $users['id'];?>/<?php print $users['pix'];?>/<?php print $users['fname'];?> <?php print $users['lname'];?>">Rezone <?php print $users['fname'];?></a>

			
				<a class="external sheet-close unlinkcustomer" data-customer="<?php print $users['id'];?>">Unlink <?php print $users['fname'];?></a>
				
				<a class="custpaybtn sheet-close" data-dhref="/myproperty/<?php print $users['id'];?>">Payments & Subscriptions</a>
	<?php 
	$checksub = mysqli_query($conn, "SELECT id FROM myproperty WHERE userid='$users[id]'");
	if(mysqli_num_rows($checksub)>0){
		
	?>
                <a class="custpaybtn sheet-close" data-dhref="/newaccount/<?php print $users['id'];?>/<?php print $users['fname'];?>">Generate Virtual Account</a>
	<?php }else{?>
	  <a class="custpaybtn sheet-close" data-dhref="/allproperty/">Buy Land & Generate Account</a>
	<?php }?>
				
				<a class="sheet-open" data-sheet=".validatepay<?php print $users['id'];?>" data-customer="<?php print $users['id'];?>">Validate <?php print ucwords($users['fname']);?>'s Payment</a>
            </div>
            

			<div class="bottom">
                <a class=" sheet-close" href="#">Dismiss</a>
            </div>
          </div>
		  </form>
    </div>
	
	
  </div>
</div>



<div class="sheet-modal validatepay<?php print $users['id'];?>" style="height:100%;background:transparent">
      <div class="sheet-modal-inner" style="background:transparent">
 <div class="tf-panel up panel-open">
   
          <form class="tf-form verifypayment">
		  
		  <div class="panel-box panel-up wrap-panel-clear panel-change-profile">
            <div class="heading bg-white" style="padding:5px">
                
<input type="" name="me" class="meuser" value="<?php print $users['id'];?>" />
            
                <div class="mt-4">
 <div class="group-input">
                    <label style="color:#000 !important"><?php print ucwords($users['fname']);?>'s Payment Reference</label>
                    <input type="text" placeholder="Enter <?php print $users['fname'];?>'s payment referece" name="payref" required />
                    </div>
					
                    <div class="group-input input-field input-money">
                    <label style="color:#000 !important">Property <?php print ucwords($users['fname']);?> Paid For</label>
                   <select name="property" class="payprop">
					<option>Select your property</option>
					
					<option value="" disabled selected>Select your property</option>
<?php $chkp=mysqli_query($conn,"SELECT id,userid,propertyid,amount,status,date,planid,propuid,payduration,initial_deposit,amt_due,propamount,amt_paid,amt_remain FROM myproperty WHERE userid='$users[id]'");
if(mysqli_num_rows($chkp)>0){
	while($prop=mysqli_fetch_array($chkp)){?>
<option value="<?php print $prop['propertyid'];?>" data-inital="<?php print round($prop['amt_due'],0);?>"><?php print $prop['propuid'];?></option>
<?php }}else{?>
<option value="" disabled><?php print ucwords($users['fname']);?> is not subscribed to any property yet</option>
<?php }?>


					</select>
                   
                    </div>
                   
                </div>

				
            </div>
            <div class="bottom mb-1">
                <button class="tf-btn accent large">Verify Payment</button>
            </div>

			<div class="bottom">
                <a class=" sheet-close" href="#">Dismiss</a>
            </div>
          </div>
		  </form>
    </div>
	
	
  </div>
</div>

<?php }
}
?>
                           
                        </div>
                </div>							



<?php }?>
                           
                        </div>
               
                                    <div class="chart-item chartall" id="tab2" style="display:none">

		<h2 class="p-1 border-bottom">Referred Realtors</h2>
		<?php
	
$fortoday =  mysqli_query($conn, "SELECT id, date, DATE_FORMAT(date, '%Y-%m-%d') AS dday FROM users WHERE agent='$me' AND account_type!='customer'  GROUP BY dday ORDER BY id DESC") OR die(mysqli_error($conn));


$today = date('Y-m-d');
$yesterday = new DateTime('yesterday');
$yerst = $yesterday->format('Y-m-d');
if(mysqli_num_rows($fortoday)>0){
	
	while($transday = mysqli_fetch_array($fortoday)){
?>
<div class="trading-month p-2">
                        <h5 class="fw_5 mb-3"><?php if($transday['dday']=="$today"){ print "Today"; }elseif($transday['dday']=="$yerst"){ print "Yesterday"; }else{ print date('F jS, Y',strtotime($transday['dday'])); }?></h5>
                        <div class="group-trading-history mb-5">
<?php
$check =mysqli_query($conn, "SELECT * FROM users WHERE (agent='$me' OR agent='pend$me') AND account_type!='customer' AND DATE_FORMAT(date, '%Y-%m-%d')='$transday[dday]' ORDER BY id DESC");
if(mysqli_num_rows($check)>0){
	while($users3 = mysqli_fetch_array($check)){
		if($users3['status'] =="active"){
			$status = "success_color";
			$allstats = $users3['status'];
		}elseif($users3['status'] =="reset"){
			$allstats = "incomplete";
			$status = "critical_color";
		}else{
			$allstats = $users3['status'];
			$status = "critical_color";
		}		
		
$checkttcl = mysqli_query($conn, "SELECT id FROM users WHERE agent='$users3[userid]'");
$totalcl = mysqli_num_rows($checkttcl);

//calculate all the amount this user has paid
$checkrevv3 = mysqli_query($conn,"SELECT SUM(amount) AS revenuev3 FROM  payment WHERE agentid='$users3[userid]' AND propert_type!='promo' AND propert_type!='outright'");

$rowppv3 = mysqli_fetch_assoc($checkrevv3);
$sumppv3 = $rowppv3['revenuev3'];
$totalpaydv3 = "$sumppv3";
if($totalpaydv3!==""){
$allrevdv3 = $totalpaydv3;
}else{
$allrevdv3 = "0";
};
if($allrevdv3==0){
$pertotal2 = 0;
}else{
$pertotal2 = $allrevdv3;
}


//calculate the expected amount by the user
$checkrevvpro3 = mysqli_query($conn,"SELECT SUM(propamount) AS revenuevpro3 FROM  myproperty WHERE collectorid='$users3[userid]' AND type='daily'");
$rowppvpro3 = mysqli_fetch_assoc($checkrevvpro3);
$sumppvpro3 = $rowppvpro3['revenuevpro3'];
$totalpaydvpro3 = "$sumppvpro3";
if($totalpaydvpro3!==""){
$allrevdvpro3 = $totalpaydvpro3;
}else{
$allrevdvpro3 = "0";
};

if($allrevdvpro3==0){
$pertotal23 = 0;
}else{
$pertotal23 = $allrevdvpro3;
}

if($pertotal2==0 || $pertotal23==0){
$pertotal27 = 0;
}else{
$pertotal27 = round($pertotal2/$pertotal23);
}

$checkwh = mysqli_query($conn,"SELECT * FROM users WHERE userid='$me'");
$admins = mysqli_fetch_array($checkwh);
if($admins['position']=="Account Officer" || $admins['position']=="Marketers"){
	
	if($users3['agent']=="pend$me"){
			$allm = "alerty";
			$linky = "#";
			}else{
				$linky = "/userprofile/".$users3['id']."/".$users3['fname'].' '.$users3['lname'];
				$allm ="";
			}
		
}else{
	$allm = "notCollector";
	$linky = "#";
}
?>
                            <a class="cl<?php print $allstats;?> tf-trading-history bg-white p-2 shadow-xs <?php print $status;?> <?php print $allm;?>" href="<?php print $linky;?>" style="border-radius:5px">
                                <div class="inner-left">
                                  
									<div class="thumb" style="object-fit:cover">
                                         <span class="badge bg-primary" style="position:absolute;margin-top:27px;margin-left:4px;font-size:9px;padding-top:0;padding-bottom:0px"><?php print $pertotal27;?>%</span>

<img src="<?php print $kaylink;?>/pix/thumb/<?php print $users3['pix'];?>" alt="image" style="object-fit:cover;height:40px;width:40px" />
                                    </div>
                                    <div class="content">
                                        <h4><?php print ucwords($users3['fname'].' '.$users3['lname']);?> <span class="badge bg-danger" style="font-size:8px; padding:0px 7px;float:right"><?php print ucwords($totalcl);?> Clients</span></h4>
                                        <p><?php if($users3['agent']=="pend$me"){ echo 'Request Pending Approval';}else{?>
										<?php if($users3['status']=="unverified"){ echo 'Incomplete Profile - OTP is : '.$users3['otpcode'];}else{?><?php print $users3['region'];?>, <?php print $users3['state'];?><?php }?>
										<?php }?>
										</p>

<div class="progress progress-sm flex-grow-1" title="<?php print $allrevdv3;?>" style="min-width: 240px;height:10px">
    <div class="progress-bar bg-primary rounded" role="progressbar" style="width: <?php print $pertotal27;?>%;height:10px;font-size:7px" aria-valuenow="<?php print $pertotal27;?>" aria-valuemin="0" aria-valuemax="100"><?php print $pertotal27;?>%</div>
</div>
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
<div class="alert alert-info">You have not created any Customer Account yet</div>
<?php }

?>
                                    </div>
                                     <div class="chart-item chartall" id="tab3" style="display:none">
                                       <?php include('apexchart.php');?>
                                    </div>
                                    
                                </div>
                        </div>   
                </div>
                </div>
            </div>

<script>
function hideshow(tabid){
$('.chartall').hide();
$('#'+tabid).show();
}
</script>