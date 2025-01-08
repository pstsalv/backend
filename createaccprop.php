<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');
include_once("conn.php");
$me = mysqli_real_escape_string($conn,$_POST['me']);
$fname = mysqli_real_escape_string($conn,$_POST['fname']);
$lname = mysqli_real_escape_string($conn,$_POST['lname']);

$phone = mysqli_real_escape_string($conn,$_POST['phone']);
$email = mysqli_real_escape_string($conn,$_POST['email']);
$propertyuid = mysqli_real_escape_string($conn,$_POST['propertyuid']);

if(isset($_POST['prefbank'])){
$dbankk = mysqli_real_escape_string($conn,$_POST['prefbank']);
}else{
	$dbankk = 'wema-bank';
}


if(isset($_POST['proptype'])){
$proptype = mysqli_real_escape_string($conn,$_POST['proptype']);

if($proptype=="Building"){
	$dapi = $payhapi;
}else{
	$dapi = $payapi;
}
}else{
	$proptype = 'wema-bank';
	$dapi = $payapi;
}

if($propertyuid =="documentation" OR $propertyuid =="sms"){
	
	
	
	
	
		

  $url = "https://api.paystack.co/customer";

  $fields = [
    "email" => $email,
    "first_name" => $fname,
    "last_name" => $lname,
    "phone" => $phone
  ];

  $fields_string = http_build_query($fields);

  //open connection
  $ch = curl_init();
  
  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer $dapi",
    "Cache-Control: no-cache",
  ));
  
  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
  
  //execute post
  $result = curl_exec($ch);
 //echo $result;
  
  $response = json_decode($result, true);
    if ($response['status'] === true) {
		
		$customercode = $response['data']['id'];
		$customer_code = $response['data']['customer_code'];
				//echo $customercode;
				
				
				

  $url = "https://api.paystack.co/dedicated_account";

  $fields = [
    "customer" => $customercode,
    "phone" => $phone,
    "preferred_bank" => $dbankk
  ];

  $fields_string = http_build_query($fields);

  //open connection
  $ch = curl_init();
  
  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer $dapi",
    "Cache-Control: no-cache",
  ));
  
  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
  
  //execute post
  $responsed = curl_exec($ch);

if ($responsed === false) {
    echo 'Error: ' .curl_error($ch);
} else {
    $respons = json_decode($responsed, true);
	
    if ($respons['status'] === true) {
		
		$accname = $respons['data']['account_name'];
		$accno = $respons['data']['account_number'];
		$banknm = $respons['data']['bank']['name'];
		
		mysqli_query($conn, "INSERT INTO banks VALUES(NULL,'$accname','$accno','$banknm','$me',now(),'$customer_code','$customercode','$email','$propertyuid')");
		
		
		?>
		
<?php
 $checkfeesh = mysqli_query($conn,"SELECT SUM(propamount) AS amttpaidp FROM myproperty WHERE userid='$me' AND status='ongoing'");
$rowppph = mysqli_fetch_assoc($checkfeesh);
$sumppmp = $rowppph['amttpaidp'];
$totalpaydp = "$sumppmp";
if($totalpaydp!=""){
$mybalpp = $totalpaydp;
}else{
	$mybalpp = 0;
}

$tenpers = "$mybalpp"*0.1;
?>
		<div class="alert alert-success p-1 m-1 text-center"><h1>All Done!</h1>
		Virtual account generated for <?php print $propertyuid;?> successfully. You can now transfer money to this account details.</div>
        <div class="tf-balance-box">
                <div class="balance">
                    <div class="row">
                        <div class="col-12">
						<a href="#">
                            <div class="">
                                <p><?php print ucwords($propertyuid);?>  Fees:</p>
                               
								<?php if($propertyuid=="documentation"){?>
								<h3 class="text-danger ">10% of Property Amount</h3>
								<?php }else{ ?>
								<h3 class="text-danger ">Transaction, Notification, Reminders and other sms Charges</h3>
								<?php }?>
                            </div>
							</a>
                        </div>
                     
                    </div>
                </div>
			<?php
$checkp2 =mysqli_query($conn, "SELECT id FROM myproperty WHERE userid='$me'");
$total2 = mysqli_num_rows($checkp2);
?>	
				 <div class="balance">
                    <div class="row">
					 <div class="col-6">
                        <div class="inner-left">
                                <p>Charged Fees</p>
								<?php if($propertyuid=="documentation"){?>
                                <h3 class="text-secondary">₦ <?php print number_format($tenpers);?>
								<?php }else{
									 $checksms = mysqli_query($conn,"SELECT SUM(amount_charged) AS smsamt FROM smscharges WHERE userid='$me'");
$rowsms = mysqli_fetch_assoc($checksms);
$sumsms = $rowsms['smsamt'];
$totalsms = "$sumsms";
if($totalsms!=""){
$mysms = "$totalsms"+7000;
}else{
	$mysms = 0;
}
?>
                                <h3 class="text-secondary">₦ <?php print number_format($mysms);?>
								<?php }?>
                                </h3>
                            </div>
                            </div>
                        <div class="col-6">
					
                            <div class="inner-right">
                                <p>Subscriptions</p>
                                <h3 class="text-warning">
                                <?php print number_format($total2);?> property
                                </h3>
                            </div>
						
                        </div>
                    </div>
                </div>
				
				
                <div class="wallet-footer">
                    <div class="justify-content-between align-items-center">
                 
					 <h1 class="text-center"><?php print $accno;?></h1>
					 <i class="icon-copy1 copydiz" style="position:absolute;right:30px;font-size:20px"></i>
                        <div class="text-dark text-center" style="font-size:19px">Wema Bank Plc</div>
                        <div class="text-dark text-center"><?php print $accname;?></div>
					
                    </div>
                </div>
				
					
            </div>
			<div class="bottom" style="margin-top:10px;margin-bottom:10px">
                <a class=" sheet-close" style="border:1px solid black;border-radius:5px" href="#">Dismiss</a>
            </div>
   <?php
   } else {
	   ?>
       	<div class="alert alert-danger p-1 m-1 text-center"><h1>Oh oh!</h1>
		Something went wrong, we could not generate virtual account for #<?php print $propertyuid;?>. Try again after a while.</div>
		
		<div class="bottom" style="margin-top:10px;margin-bottom:10px">
                <a class=" sheet-close" style="border:1px solid black;border-radius:5px" href="#">Dismiss</a>
            </div>
   <?php
   }
}
	}
	
	
}else{
	
	
	
$check =mysqli_query($conn, "SELECT * FROM myproperty WHERE propertyid='$propertyuid' AND userid='$me'");
if(mysqli_num_rows($check)>0){
	$property = mysqli_fetch_array($check);
	$propuidy = $property['propuid'];
	$proptype = $property['type'];
	$propamt = $property['propamount'];
	$amtdue = $property['amt_due'];
}else{
	$propuidy = 1;
	$proptype = "Not selected";
	$propamt = 0;
	$amtdue = 0;
}

		

  $url = "https://api.paystack.co/customer";

  $fields = [
    "email" => $email,
    "first_name" => $fname,
    "last_name" => $lname,
    "phone" => $phone
  ];

  $fields_string = http_build_query($fields);

  //open connection
  $ch = curl_init();
  
  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer $dapi",
    "Cache-Control: no-cache",
  ));
  
  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
  
  //execute post
  $result = curl_exec($ch);
 //echo $result;
  
  $response = json_decode($result, true);
    if ($response['status'] === true) {
		
		$customercode = $response['data']['id'];
		$customer_code = $response['data']['customer_code'];
				//echo $customercode;
				
				
				

  $url = "https://api.paystack.co/dedicated_account";

  $fields = [
    "customer" => $customercode,
    "phone" => $phone,
    "preferred_bank" => $dbankk
  ];

  $fields_string = http_build_query($fields);

  //open connection
  $ch = curl_init();
  
  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $url);
  curl_setopt($ch,CURLOPT_POST, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer $dapi",
    "Cache-Control: no-cache",
  ));
  
  //So that curl_exec returns the contents of the cURL; rather than echoing it
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
  
  //execute post
  $responsed = curl_exec($ch);

if ($responsed === false) {
    echo 'Error: ' .curl_error($ch);
} else {
    $respons = json_decode($responsed, true);
	
    if ($respons['status'] === true) {
		
		$accname = $respons['data']['account_name'];
		$accno = $respons['data']['account_number'];
		$banknm = $respons['data']['bank']['name'];
		
		$checkacc = mysqli_query($conn,"SELECT * FROM banks WHERE userid='$me' AND pay_type='$propertyuid'");
		if(mysqli_num_rows($checkacc)<1){
		mysqli_query($conn, "INSERT INTO banks VALUES(NULL,'$accname','$accno','$banknm','$me',now(),'$customer_code','$customercode','$email','$propertyuid')");
		
		mysqli_query($conn, "UPDATE myproperty SET payment_email='$email' WHERE propertyid='$propertyuid' AND userid='$me'");
		}else{
		mysqli_query($conn, "UPDATE banks SET accname='$accname', accno='$accno',bankname='$banknm', customer_code='$customer_code', customer_id='$customercode',acc_email='$email' WHERE userid='$me' AND pay_type='$propertyuid'");

		mysqli_query($conn, "UPDATE myproperty SET payment_email='$email' WHERE propertyid='$propertyuid' AND userid='$me'");
		}
		
		
		?>
		<div class="alert alert-success p-1 m-1 text-center"><h1>All Done!</h1>
		Virtual account generated for #<?php print $propuidy;?> successfully. You can now transfer money to this account details.</div>
        <div class="tf-balance-box">
                <div class="balance">
                    <div class="row">
                        <div class="col-6 br-right">
						<a href="#">
                            <div class="inner-left">
                                <p>Property UID:</p>
                                <h3 class="text-danger ">#<?php print $propuidy;?></h3>
                            </div>
							</a>
                        </div>
                        <div class="col-6">
						<a href="#">
                            <div class="inner-right">
                                <p>Subscription</p>
                                <h3 class="text-secondary"><?php print ucwords($proptype);?>
                                </h3>
                            </div>
							</a>
                        </div>
                    </div>
                </div>
				
				 <div class="balance">
                    <div class="row">
                        <div class="col-6 br-right">
						<a href="#">
                            <div class="inner-left">
                                <p>Amount Due:</p>
                                <h3 class="text-success">₦ <?php print number_format($amtdue);?></h3>
                            </div>
							</a>
                        </div>
                        <div class="col-6">
						<a href="#">
                            <div class="inner-right">
                                <p>Outstanding</p>
                                <h3 class="text-warning">
                                 ₦ <?php print number_format($propamt);?>
                               
                                </h3>
                            </div>
							</a>
                        </div>
                    </div>
                </div>
				
				
                <div class="wallet-footer">
                    <div class="justify-content-between align-items-center">
                 
					 <h1 class="text-center"><?php print $accno;?></h1>
					 <i class="icon-copy1 copydiz" style="position:absolute;right:30px;font-size:20px"></i>
                        <div class="text-dark text-center" style="font-size:19px"><?php print ucwords($banknm);?></div>
                        <div class="text-dark text-center"><?php print $accname;?></div>
					
                    </div>
                </div>
				
					
            </div>
			<div class="bottom" style="margin-top:10px;margin-bottom:10px">
                <a class=" sheet-close" style="border:1px solid black;border-radius:5px" href="#">Dismiss</a>
            </div>
   <?php
   } else {
	   ?>
       	<div class="alert alert-danger p-1 m-1 text-center"><h1>Oh oh!</h1>
		Something went wrong, we could not generate virtual account for #<?php print $propuidy;?>. Try again after a while.</div>
		
		<div class="bottom" style="margin-top:10px;margin-bottom:10px">
                <a class=" sheet-close" style="border:1px solid black;border-radius:5px" href="#">Dismiss</a>
            </div>
   <?php
   }
}
	}else{
echo $result;
}
}
?>