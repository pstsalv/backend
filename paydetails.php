<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$payid = mysqli_real_escape_string($conn,$_GET['id']);
//check the payment - correct
$check =mysqli_query($conn, "SELECT * FROM payment WHERE id='$payid'");
$payment = mysqli_fetch_array($check);
	//payment owner - correct
		$chowner = mysqli_query($conn, "SELECT id,fname,lname, userid,planid,wallet_bal FROM users WHERE id='$payment[userid]'");
		$owner = mysqli_fetch_array($chowner);
	
	//my payment plan	
	
	$mypl = mysqli_query($conn, "SELECT id,planid,prop_id,initial_deposit FROM myplans WHERE prop_id='$payment[paidfor]' AND user_id='$owner[0]'");
	if(mysqli_num_rows($mypl)>0){
		$plans = mysqli_fetch_array($mypl);
		
	//payment plan	
		$checkplan = mysqli_query($conn, "SELECT id,plan_name,amount,plancode,status,type,duration,amt_to_pay,location,plot_size,payplancode FROM payment_plan WHERE id='$plans[planid]'");
		if(mysqli_num_rows($checkplan)>0){
			$plandetails = mysqli_fetch_array($checkplan);
		$myplanz = $plandetails['plan_name'];
		$planamt = $plandetails['amount'];
	}else{
		$myplanz = ucwords(str_replace('-',' ',$plans['planid']));
		$planamt = $plans['initial_deposit'];
	}

	}else{
		$planamt = '0.00';
		$myplanz = '<a href="/allproperty/" class="text-danger">Select Property</a>';
	}
		//payment for?	
		$checkfor = mysqli_query($conn, "SELECT amt_remain,propamount,propuid,amt_paid FROM myproperty WHERE propertyid='$payment[paidfor]' AND userid='$owner[0]'");
		if(mysqli_num_rows($checkfor)>0){
		$propy = mysqli_fetch_array($checkfor);
		$remainda = '₦'.number_format($propy['amt_remain'],2);
		$proppylink = '/payfilter/'.$propy['propuid'].'/'.$payment['userid'];
		$propuid = $propy['propuid'];
		}else{
			$remainda = '<a href="/allproperty/" class="text-danger">Select Property</a>';
			$proppylink = '#';
			$propuid = '<a href="/allproperty/" class="text-danger">Select Property</a>';
		}
		//payer details (agent or owner)
		$chwho = mysqli_query($conn, "SELECT id,fname,lname, userid FROM users WHERE userid='$payment[paid_by]'");
		if(mysqli_num_rows($chwho)>0){
			$payer = mysqli_fetch_array($chwho);
			$paidby = $payer['fname'].' '.$payer['lname'];
		}else{
			$paidby = "";
		}

if($payment['status'] =="approved"){
			$status = "success_color";
			$status2 = "bg-success";
		}else{
			$status = "critical_color";
			$status2 = "bg-critical";
		}
		
		
		?>
 
 <div class="mt-3 bill-topbar">
                     <svg width="22" height="24" viewBox="0 0 22 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                         <path d="M15.13 15.3658L15.1299 15.3657L15.1208 15.3737C14.3501 16.0481 13.7463 16.8979 13.391 17.85H8.65905C8.30263 16.8964 7.69165 16.0429 6.86973 15.3655C5.43411 14.1607 4.55383 12.3555 4.59987 10.3127C4.69332 7.02017 7.38931 4.23818 10.6712 4.09951L10.6712 4.09953L10.675 4.09934C14.3841 3.91387 17.4001 6.88051 17.4001 10.5C17.4001 12.4473 16.5193 14.2081 15.13 15.3658Z" fill="#FFD15C" stroke="#FFD15C"/>
                         <path d="M10.3502 18.4501H10.8002L8.80014 11.0001C8.85016 11.0001 8.90013 11.0001 8.95014 11.0001C9.20013 11.0001 9.45016 10.9001 9.65013 10.7001C9.75011 10.6001 9.85014 10.5501 10.0001 10.5501C10.1501 10.5501 10.2501 10.6001 10.3502 10.7001C10.7002 11.1001 11.3002 11.1001 11.6501 10.7001C11.7501 10.6001 11.8502 10.5501 12.0002 10.5501C12.1001 10.5501 12.2501 10.6001 12.3502 10.7001C12.5502 10.9001 12.7502 11.0001 13.0502 11.0001C13.1002 11.0001 13.1501 11.0001 13.2002 11.0001L11.2502 18.4501H11.7002L13.7501 10.6501C13.7501 10.5501 13.7501 10.4501 13.6502 10.4001C13.5502 10.3501 13.4501 10.4001 13.4002 10.4501C13.3002 10.5501 13.2002 10.6001 13.1002 10.6001C12.9502 10.6001 12.8502 10.5501 12.7002 10.4501C12.5002 10.2501 12.3002 10.1501 12.0502 10.1501C11.8001 10.1501 11.6002 10.2501 11.4002 10.4501C11.2001 10.6502 10.9001 10.6502 10.7002 10.4501C10.5502 10.2501 10.3002 10.1501 10.0502 10.1501C9.80017 10.1501 9.55014 10.2501 9.40014 10.4501C9.30016 10.5501 9.15016 10.6001 9.00016 10.6001C8.90017 10.6001 8.75017 10.5501 8.70016 10.4501C8.65014 10.4001 8.55016 10.3502 8.45017 10.4001C8.35019 10.4501 8.30017 10.5501 8.35019 10.6501L10.3502 18.4501Z" fill="#FFD15C"/>
                         <path d="M9.44995 22.95C9.69998 23.55 10.3 24 11 24C11.7 24 12.3 23.55 12.55 22.95H9.44995Z" fill="#344A5E"/>
                         <path d="M12.8501 23.0001H9.20005C8.70003 23.0001 8.30005 22.6001 8.30005 22.1001V18.3H13.7501V22.1001C13.7501 22.6 13.35 23.0001 12.8501 23.0001Z" fill="#344A5E"/>
                         <path d="M13.7 20.2001H8.29998C7.99998 20.2001 7.75 19.9501 7.75 19.6501C7.75 19.3501 7.99998 19.1001 8.29998 19.1001H13.7C14 19.1001 14.25 19.3501 14.25 19.6501C14.25 19.9501 14 20.2001 13.7 20.2001Z" fill="#415A6B"/>
                         <path d="M13.7 21.9501H8.29998C7.99998 21.9501 7.75 21.7001 7.75 21.4001C7.75 21.1001 7.99998 20.8501 8.29998 20.8501H13.7C14 20.8501 14.25 21.1001 14.25 21.4001C14.25 21.7 14 21.9501 13.7 21.9501Z" fill="#415A6B"/>
                         <path d="M11 0C10.75 0 10.5 0.200016 10.5 0.500016V2.20003C10.5 2.45002 10.7 2.70005 11 2.70005C11.3 2.70005 11.5 2.50003 11.5 2.20003V0.500016C11.5 0.200016 11.25 0 11 0Z" fill="#FFD15C"/>
                         <path d="M4.30017 3.04991C4.10016 2.8499 3.80016 2.8499 3.60019 3.04991C3.40022 3.24993 3.40017 3.54993 3.60019 3.7499L4.80019 4.9499C5.00021 5.14991 5.30021 5.14991 5.50017 4.9499C5.70014 4.74988 5.70019 4.44988 5.50017 4.24991L4.30017 3.04991Z" fill="#FFD15C"/>
                         <path d="M2.75008 9.94995H1.05006C0.80008 9.94995 0.550049 10.15 0.550049 10.45C0.550049 10.7 0.750064 10.95 1.05006 10.95H2.75008C3.00006 10.95 3.2501 10.75 3.2501 10.45C3.2501 10.1999 3.00006 9.94995 2.75008 9.94995Z" fill="#FFD15C"/>
                         <path d="M4.79996 15.95L3.59996 17.15C3.39995 17.3501 3.39995 17.6501 3.59996 17.85C3.79998 18.05 4.09998 18.05 4.29995 17.85L5.49995 16.65C5.69996 16.45 5.69996 16.15 5.49995 15.95C5.29993 15.7501 4.99998 15.75 4.79996 15.95Z" fill="#FFD15C"/>
                         <path d="M17.2001 15.9501C17.0001 15.75 16.7001 15.75 16.5001 15.9501C16.3001 16.1501 16.3001 16.4501 16.5001 16.65L17.7001 17.85C17.9001 18.0501 18.2001 18.0501 18.4001 17.85C18.6001 17.65 18.6001 17.35 18.4001 17.1501L17.2001 15.9501Z" fill="#FFD15C"/>
                         <path d="M20.95 9.94995H19.25C19 9.94995 18.75 10.15 18.75 10.45C18.75 10.7 18.95 10.95 19.25 10.95H20.95C21.2 10.95 21.45 10.75 21.45 10.45C21.45 10.1999 21.25 9.94995 20.95 9.94995Z" fill="#FFD15C"/>
                         <path d="M17.7001 3.0499L16.5001 4.2499C16.3001 4.44991 16.3001 4.74991 16.5001 4.94988C16.7001 5.1499 17.0001 5.1499 17.2001 4.94988L18.4001 3.74988C18.6001 3.54987 18.6001 3.24987 18.4001 3.0499C18.2001 2.84993 17.9001 2.84988 17.7001 3.0499Z" fill="#FFD15C"/>
                     </svg>
                     <h4 class="fw_6"><?php print $myplanz;?> Plan</h4>
                 </div>
                 <div class="wrapper-bill">
                     <div class="archive-top">
                         <span class="circle-box lg bg-critical <?php print $status2;?>">
                             <svg width="63" height="62" viewBox="0 0 63 62" fill="none" xmlns="http://www.w3.org/2000/svg">
                                 <path d="M31.5 11.2783L27.023 7.68753L22.5459 11.2824L18.069 7.68753V50.3189L22.5459 53.9139L27.023 50.3189L31.5 53.9139L31.6334 53.5819L32.3766 30.9701L31.6419 11.3564L31.5 11.2783Z" fill="white"/>
                                 <path d="M40.454 11.2824L35.977 7.68753L31.5 11.2783V53.9139L35.977 50.3189L40.454 53.9139L44.931 50.3189V7.68753L40.454 11.2824Z" fill="white"/>
                                 <path d="M21.681 17.808V21.364H31.642L31.9964 19.5859L31.642 17.808H21.681Z" fill="#C5C5C5"/>
                                 <path d="M31.5051 17.808H35.6749V21.364H31.5051V17.808Z" fill="#C5C5C5"/>
                                 <path d="M21.681 31.2109H29.7102V34.7669H21.681V31.2109Z" fill="#C5C5C5"/>
                                 <path d="M21.681 38.3227H29.7102V41.8786H21.681V38.3227Z" fill="#4A84F6"/>
                                 <path d="M21.6597 24.3728V27.9286H31.6419L31.9964 26.0385L31.6419 24.3728H21.6597Z" fill="#C5C5C5"/>
                                 <path d="M31.5051 24.3728H41.3404V27.9287H31.5051V24.3728Z" fill="#C5C5C5"/>
                                 <path d="M37.7163 40.5659C36.3815 40.4515 35.4027 39.943 34.7035 39.2438L35.6951 37.8327C36.1655 38.3285 36.8647 38.7734 37.7164 38.926V36.9555C36.407 36.6376 34.9832 36.1419 34.9832 34.413C34.9832 33.1291 36.0002 32.0358 37.7164 31.8578V30.6756H38.9114V31.8833C39.941 31.9977 40.8182 32.379 41.5047 33.0146L40.5005 34.3622C40.0429 33.9427 39.4835 33.6757 38.9114 33.5358V35.2901C40.2335 35.6206 41.7082 36.1292 41.7082 37.8707C41.7082 39.2819 40.7801 40.3751 38.9114 40.5658V41.7099H37.7164V40.5659H37.7163ZM37.7163 34.9979V33.4597C37.157 33.536 36.8391 33.841 36.8391 34.2733C36.8392 34.6419 37.1951 34.8326 37.7163 34.9979ZM38.9114 37.248V38.9514C39.5597 38.8242 39.8648 38.4556 39.8648 38.0488C39.8648 37.6294 39.4707 37.426 38.9114 37.248Z" fill="#F2C71C"/>
                             </svg>
                         </span>
                         <h1><a href="#" class="<?php print $status;?>">₦ <?php print number_format($payment['amount'],2);?></a></h1>
                         <h3 class="mt-2 fw_6">Payment Amount</h3>
                         <p class="fw_4 mt-2 <?php print $status;?>"><?php if($payment['admin_approved']=="yes" || $payment['admin_approved']=="virtual"){?> Payment has been confirmed by Bliss Legacy <?php }else{?>Payment is yet to be confirmed by Bliss Legacy <?php }?></p>
                     </div>
                     <div class="dashed-line"></div>
                     <div class="archive-bottom">
                         <h2 class="text-center">Payment Information</h2>
                         <ul>
                           <li class="list-info-bill">Payment Reference </li>
                           <li class="list-info-bill">#<?php print $payment['payref'];?> </li>
                           <li class="list-info-bill">Amount Due <span>₦ <?php print number_format($planamt,2);?></span> </li>
                             <li class="list-info-bill">Amount Paid <span>₦ <?php print number_format($payment['amount'],2);?></span> </li>
                            <!-- <li class="list-info-bill">Amount Remaining <span> <?php print $remainda;?></span> </li>-->
                             <li class="list-info-bill">Payment For <span> #<a href="<?php print $proppylink;?>" class="text-danger"><?php print $propuid;?></a></span> </li>
                             <li class="list-info-bill">Date <span><?php print date('l, F d, Y',strtotime($payment['date_paid']));?></span> </li>
                             <li class="list-info-bill">Customer's Name <span><?php print $owner['fname'].' '.$owner['lname'];?></span> </li>
                             <li class="list-info-bill">Paid by <span><?php print $paidby;?></span> </li>
                             <li class="list-info-bill">Payment Method <span class="text-end">Transfer</span> </li> 
							 
 <li class="list-info-bill">Sender Bank <span class="text-end"><?php print ucwords($payment['senderbank']);?></span> </li> 

 <li class="list-info-bill">Sender Account <span class="text-end"><?php print ucwords($payment['senderaccno']);?></span> </li> 
 <li class="list-info-bill">Sender Name <span class="text-end" style="font-size:10px"><?php print ucwords($payment['sendername']);?></span> </li> 
							 
							 <li class="list-info-bill">Branch Code <span class="text-end"><?php print ucwords($payment['branchid']);?></span> </li>
							 
							 <li class="list-info-bill">Agent Code <span class="text-end"><?php print ucwords($payment['agentid']);?></span> </li>
                         </ul>
                     </div>
     
                 </div>
				 
				 <div>
				 <a href="/support/"  class="mt-2 mb-9 bill-topbar">
                   <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M256 48C141.1 48 48 141.1 48 256v40c0 13.3-10.7 24-24 24s-24-10.7-24-24V256C0 114.6 114.6 0 256 0S512 114.6 512 256V400.1c0 48.6-39.4 88-88.1 88L313.6 488c-8.3 14.3-23.8 24-41.6 24H240c-26.5 0-48-21.5-48-48s21.5-48 48-48h32c17.8 0 33.3 9.7 41.6 24l110.4 .1c22.1 0 40-17.9 40-40V256c0-114.9-93.1-208-208-208zM144 208h16c17.7 0 32 14.3 32 32V352c0 17.7-14.3 32-32 32H144c-35.3 0-64-28.7-64-64V272c0-35.3 28.7-64 64-64zm224 0c35.3 0 64 28.7 64 64v48c0 35.3-28.7 64-64 64H352c-17.7 0-32-14.3-32-32V240c0-17.7 14.3-32 32-32h16z"/></svg>
                     <h4 class="fw_6">Raise Dispute</h4>
					 <i class="icon-right" style="position:absolute;right:15px"></i>
					 </a>
                 </div>
<?php mysqli_close($conn);?>