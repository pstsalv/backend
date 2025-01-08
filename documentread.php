<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");
include("numberwords.php");
?>
<style>
.notouch{
	-moz-user-select: none;
-webkit-user-select: none;
user-select: none;
}
</style>
<?php
$me = mysqli_real_escape_string($conn,$_GET['me']);
$docid = mysqli_real_escape_string($conn,$_GET['docid']);

$checkp =mysqli_query($conn, "SELECT id,userid,propertyid,amount,status,date,planid,propuid,payduration,initial_deposit,amt_due,propamount,amt_paid,amt_remain FROM myproperty WHERE id='$docid'");
$myproppy = mysqli_fetch_array($checkp);


$checkpp =mysqli_query($conn, "SELECT id,title,description,amount,images,location,status,date,provider,popular,prop_uid,estate_name,unit_available,plots,prop_category,prop_full_desc,canpay_installment,state,axis,promotitle,promocode,promogift,promoprice,type,propertycode,dailyones FROM property WHERE prop_uid='$myproppy[propuid]'");
$actualprop = mysqli_fetch_array($checkpp);


$checkdocp =mysqli_query($conn, "SELECT id,plan_name,amount,plancode,status,type,duration,amt_to_pay,location,plot_size,payplancode FROM payment_plan WHERE id='$myproppy[planid]'");

if(mysqli_num_rows($checkdocp)>0){
	$myplan = mysqli_fetch_array($checkdocp);
		$myplanz = $myplan['plan_name'];
		$planamt = $myplan['amount'];
	}else{
		$myplanz = ucwords(str_replace('-',' ',$myproppy['planid'])).' Payment';
		$planamt = $myproppy['initial_deposit'];
	}

$checks =mysqli_query($conn, "SELECT signature FROM mysigns WHERE owner_id='$me'");
if($checks && mysqli_num_rows($checks)>0){
	$mysign = mysqli_fetch_array($checks);
$mysigned = $mysign[0];
}else{
	$mysigned = "";
}

$checksg =mysqli_query($conn, "SELECT * FROM guaranntor_info WHERE owner='$me'");
if($checksg && mysqli_num_rows($checksg)>0){
	$mysigng = mysqli_fetch_array($checksg);
$mysignedg = $mysigng['signature'];
$fullnm = $mysigng['fullnames'];
$phoneg = $mysigng['phone'];
$addressg = $mysigng['address'];
$dateg = $mysigng['date'];
}else{
	$mysignedg = "";
	$fullnm = "";
$phoneg ="";
$addressg = "";
$dateg = "";
}
$checkuser =mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$me'");
$user = mysqli_fetch_array($checkuser);

$checkdoc =mysqli_query($conn, "SELECT * FROM contracts WHERE owner_id='$me'");
$mydoc = mysqli_fetch_array($checkdoc);
?>
<div class="w-100 notouch">
<div class="text-center">
<img src="images/blisslogo.png" alt="" />
</div>
<h2 class="text-center">Contract of Sales</h2>

<div class="p-2 border mx-2 mt-3 bg-white shadow-sm" id="middle" style="min-height:450px;border-radius:10px">
<div class="p-2 border">
<p class="text-center pt-3" style="font-size:15px;font-weight:700">CONTRACT OF
<?php print strtoupper($myplanz);?>/DEPOSIT<br>
FOR <?php print strtoupper($actualprop['prop_category']);?> </p>
<div class="text-center">
<p style="font-size:15px;font-weight:700;margin-top:25px">
BETWEEN
</p>
<p style="font-size:15px;font-weight:700;margin-top:25px">
BLISS LEGACY LIMITED <br>
(VENDOR)
</p>
<p style="font-size:15px;font-weight:700;margin-top:25px">
AND
</p>
<p style="font-size:15px;font-weight:700;margin-top:25px">
<?php print strtoupper($user['fname'].' '.$user['lname']);?><br>
	(DEPOSITOR)	 
</p>
<p style="font-size:13px;font-weight:700;margin-top:20px">
PREPARED BY:</p>
<p style="font-size:15px;font-weight:700;margin-top:20px">

	<img src="<?php print $kaylink;?>/pix/lawyer.png" alt="" style="height:35px;width:auto" />								 
</br>
Samuel Akpologun Esq<br>
Ace & Vanguard Legal Practitioners<br>
1B Akin Osiyemi Street,
Off Allen Avenue, Lagos.<br>
234 806 526 4570<br>
info@aceandvanguard.com
</p>
</div>
</div>


</div>
<style>
.diff{
	font-family: "freehand521 bt";
	font-weight:700;
	padding-right:5px;
}
</style>
<div class="border mx-2 mt-3 bg-white shadow-sm px-3" id="doc1" style="font-size:11px;min-height:450px;border-radius:10px;text-align: justify;text-justify:inter-word;">
<p class="text-center pt-3" style="font-size:15px;font-weight:700">CONTRACT OF <?php print strtoupper($myplanz);?>/DEPOSIT
FOR LAND</p>

<p class="py-2"><i class="diff">This Agreement</i> is made the <u><b><?php print date('jS',strtotime($myproppy['date']));?></b></u> day of <u><b><?php print date('F',strtotime($myproppy['date']));?></b></u> <?php print date('Y',strtotime($myproppy['date']));?>. BETWEEN BLISS LEGACY LIMITED of Plot 102 Ihama, Off Adesuwa Road,GRA, Benin City
(hereinafter called the Vendor) which expression shall where the context so admits
include her Heirs, Administrators, Executors and Assigns) <b>of the one part</b>.AND <b><u><?php print strtoupper($user['fname'].' '.$user['lname']);?></u></b> of <u><b><?php print ucwords($user['address']);?></b>, <b><?php print $user['state'];?></b></u>. (hereinafter called the Depositor) which expression shall where the context so admits
include his Heirs, Administrators, Executors and Assigns) <b>of the other part</b>.

<p class="pt-2"><u><b>WHEREAS:</b></u></p>
<ul class="pb-2">
<li>1. The Vendor is a registered company in the business of landed properties, estates
and construction.</li>
<li>2. The Depositor is desirous of making <?php print ucwords($myplanz);?> towards acquiring a land(s) in
the estate of the Vendor.</li>
</ul>

<p class=" pb-2"><u><b>IT IS AGREED BETWEEN THE PARTIES AS FOLLOWS:</b></u></p>
<p class=" pb-1"><u><b>NUMBER OF PLOT(S)</b></u></p>
<p class=" pb-2"><?php print $actualprop['plots'];?> of land.</p>
<p class=" pb-1"><u><b>PAYMENT PLAN</b></u></p>
<p class=" pb-2">Head/Total Sum – <b><?php print strtoupper(convertNumber($myproppy['propamount']));
?>
  (<?php print $myproppy['amount'];?>)</b></p>

<p class=" pb-2"><?php print ucwords($myplanz);?>/deposit of the sum of <b><?php print strtoupper(convertNumber($myproppy['amt_due']));
?> (<?php print number_format($myproppy['amt_due'],2);?>)</b></p>
<p class=" pb-1"><u><b>DURATION OF PAYMENT</b></u></p>

<p class="pb-2">Payment shall span within <?php print $myproppy['payduration'];?></p>

<p class=" pb-1"><u><b>DEFAULT FEE</b></u></p>
<ul class="">
<li>1. Every Depositor is entitled to a month grace after the due date before attracting the
default fee.</li>
<li>2. Any Default after the due date and the one month of grace will attract the sum of
N50, 000 monthly.</li>
<li>3. All default fees must be paid before any depositor is entitled to a Deed of Transfer. </li></p>
</div>


<div class="border mx-2 mt-3 bg-white shadow-sm px-3" id="doc2" style="font-size:11px;min-height:450px;border-radius:10px;text-align: justify;text-justify:inter-word;">

<p class="py-2">
<p><u><b>MODE OF PAYMENT</b></u></p>
<ul>
<li>1. By obtaining first a MEMBERSHIP CARD FOR <?php print strtoupper($myplanz);?>/CONTRIBUTION
OF LAND from the Vendor.
Payment shall be made to any staff of the Vendor with the Depositor signing and
the Staff counter-signing or the depositor shall give a standing order to his or her
bank to credit BLISS LEGACY LIMITED account either on daily, weekly or
monthly depending on the Depositor’s plan.</li>
<li>2. TAKE NOTICE that the Vendor shall not bear any responsibility and is not liable
to any Depositor who claims to have been making any payment to any of the
Vendor’s staff, without having to sign the Contract of Deposit for Land.</li>
<li>3. Every Account Officer on behalf of the company MUST show his or her ID card (so
valid for 1 year) and  must be registered on the Compnaies Bliss Legacy Pay App. Ensure that every money you pay is always registered against your account in the company's official app. Where this is not possibe becuase of issues of bad network, a hydrogen account backed by access bank will be created  as an alternative means of payment. Bliss legacy does not recognize any payments outside either of these two payment methods. And therefore will not be held responsible for it.</li>

<li>4. There MUST be a MONTHLY BALANCING of the Depositor’s Account,
which must be done in the company’s office, either by his/her physical presence or
through phone calls. 07000325477</li>

<li>6. TAKE FURTHER NOTICE that every deposit made to the company’s
authorized Collector MUST be recorded on your Daily/Weekly/Monthly Deposit
Card.</li>

<li>8. TAKE NOTICE, Other the official account generated from the company's Bliss pay app (which always has your name and the compnay name together) or the account generated from access bank supported hydrogen account (which caries your name and the company name together). Transfer to any other personal account is highly prohibited.</li>

<li>9. NOTE: Always insist to double check your payment history on your client app or directly from the company through the account officer assigned to you or from the company's collector.</li>

<li>9. Upon completion of <?php print ucwords($myplanz);?>/installment for land,
three weeks will be required y Bliss legacy limited to enable the office carry out verification and auditing process before final allocation and deed of transfer will be issued.</li>
</ul>

<p><u><b>ALLOCATION OF LAND</b></u></p>
<ul>
<li>1. The Depositor shall be entitled to provisional allocation of land upon 80% deposit
of the total sum, and a deed of transfer upon full payment.</li>

<?php if($actualprop['state']=="Lagos"){?>
<li>2. <?php print strtoupper(convertNumber($myproppy['propamount']));
?> (N<?php print $myproppy['amount'];?>) is payment for <?php print $actualprop['plots'];?> in Mowe/Shagamu Axis
</li>
<?php }else{?>
<li>2. Any of the Estates, along the outskirt of Benin-Lagos road axis (Evoneka), Upper
Siluko axis,Upper Sakponba, and Ekenwan road axis are ONLY allocated to
Depositors of <?php print strtoupper(convertNumber($actualprop['amount']));
?> (<?php print $myproppy['amount'];?>) for 1 PLOT (100ft by 100ft), WHILE
Depositors of SEVEN HUNDRED AND FORTY FOUR THOUSAND NAIRA
(N744, 000) for HALF PLOT (50ft by 100ft) respectively.</li>


<li>3. Any of the Estates in the outskirt of Agbor road axis, Sapele, Benin-Auchi axis
and Airport road axes are ONLY allocated to Depositors of TWO MILLION
THREE HUNDRED AND TWENTY THOUSAND NAIRA (2,320,000) for 1
PLOT (100ft by 100ft), WHILE Depositors between ONE MILLION ONE
HUNDRED AND SIXTY THOUSAND NAIRA (N1, 160, 000) for HALF
PLOT (50ft by 100ft) respectively.</li>


<?php }?>
<?php if($actualprop['state']=="Lagos"){?>
<li>3. Note that allocation of land is done on a first come first served basis.</li>
<?php }else{?>
<li>4. Note that allocation of land is done on a first come first served basis.</li>
<?php }?>
</ul>
</p>
</div>


<div class="border mx-2 mt-3 bg-white shadow-sm px-3" id="doc3" style="font-size:11px;min-height:450px;border-radius:10px;margin-bottom:60px;text-align: justify;text-justify:inter-word;">

<p class="py-2">
<ul>
<li><?php if($actualprop['state']=="Lagos"){?>4<?php }else{?>5<?php }?>. Should the Depositor reject the plot of land allocated to him/her by the Company,
he/she may so choose from any of our available estates that is within the same price
range, of what he/she paid; as there SHALL NOT be any refund.<li>
</ul>

<p><u><b>REVOCATION OF CONTRACT</b></u></p>
<ul>
<li>1. That all money paid to the said company, shall not be refunded back to Depositor
under any circumstances.</li>
<li>2. That in cases of acute illness or a verifiable financial bankruptcy of the Depositor,
the said contract shall be put on hold for at most 6 months depending on the
exigency, after which normal default fee begins to apply. A pause begins with an
official letter issued by the company.</li>

<li> A deed of tranfer is only issued by a lawyer (appointed by the company to do so) after payment has been fully made and 10% of the total fee paid for the land is paid as legal fee for deed of transfer. Also note that sms charges applyfor each sms sent to you. </li>
</ul>

<p><u><b>DISPUTE RESOLUTION</b></u></p>
<ul>
<li>1. All cases of disputes <b>MUST FIRST</b> be tendered before the Company’s <b>DISPUTE
RESOLUTION TEAM</b>, made up of Credible and Reputable elders of the society;
failure upon resolving the said dispute, before the Depositor may now approach
the Edo State Multidoor Court.</li>
</ul>

<p><b>THE COMMON SEAL OF BLISS LEGACY LIMITED WAS AFFIXED TO THIS
DEED AND WAS DULY DELIVERED IN THE PRESENCE OF:</b></p>

<div style="display:flex;margin-top:30px" class="text-center pb-1">

<img src="<?php print $kaylink;?>/pix/director.png" alt="Director Signature" class="oldsign" style="height:60px;width:auto;mix-blend-mode: multiply;position:absolute;margin-top:-25px;margin-left:20px" />
<div style="flex:1">

<div>________________</div>

<div>DIRECTOR</div>
</div>

<img src="<?php print $kaylink;?>/pix/secretary.png" alt="Director Signature" class="oldsign" style="height:60px;width:auto;mix-blend-mode: multiply;position:absolute;margin-top:-25px;right:0px" />
<div style="flex:1">
<div>________________</div>

<div>SECRETARY</div>
</div>
</div>
<p>Signed, Sealed and Delivered by within named</p>

<p class="pb-3"><b>Depositor</b></p>
<?php if($mysigned!==""){?>
<a href="/signature/">
		 <img src="<?php print $mysigned;?>" class="oldsign" style="height:60px;width:auto;mix-blend-mode: multiply;position:absolute;margin-top:-25px" />
</a>
 <?php } ?>
<p> --------------------------</p> <a href="/signature/" class="badge bg-primary" style="float:right">Edit</a>
<p> <b><?php print strtoupper($user['fname'].' '.$user['lname']);?></b></p>


<div class="pt-3 pb-2">
<p class="py-2">In the Presence of:</p>
Name: ___<b><u><?php if($fullnm!==""){ echo $fullnm;}else{ echo '________________';}?></u></b>__<a href="/addgurrantor/" class="badge bg-primary" style="float:right">Edit</a><br>
Address: <b><u><?php if($addressg!==""){ echo $addressg;}else{ echo '________________';}?></u></b><a href="/addgurrantor/" class="badge bg-primary" style="float:right">Edit</a><br><br>

<?php if($mysignedg!==""){?>
<a href="/addguarrantor/">
		 <img src="<?php print $mysignedg;?>" class="oldsign2" style="height:60px;width:auto;mix-blend-mode: multiply;position:absolute;margin-top:-25px;margin-left:70px" />
</a>
 <?php } ?>
 
Signature: _______________________<br>
Date: __<b><u><?php if($dateg!==""){ echo date('d-m-Y',strtotime($dateg));}else{ echo '________________';}?></u></b>__<a href="/addgurrantor/" class="badge bg-primary" style="float:right">Edit</a>
</div>
<div class="text-center">
<img src="images/seal.png" style="height:60px;width:auto" alt="" />
</div>
<p>PREPARED BY: ___________________________</p>
<p class="text-center">
<b>
Samuel Akpologun Esq
Ace & Vanguard Legal Practitioners<br>
1B Akin Osiyemi Street,
Off Allen Avenue, Lagos.<br>
234 806 526 4570<br>
info@aceandvanguard.com
 </b>
</p>
</p>
</div>
</div>