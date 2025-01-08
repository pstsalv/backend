<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");
?>
<div class="alert alert-danger p-2"><i class="icon-info" style="font-size:19px;position:absolute;margin-top:3px;"></i><h4 style="padding-left:20px"> Notice</h4> Make sure you are Subscribed to a property first before making payment. Otherwise, your payment will not reflect on your payment history.</div>
<?php
$me = mysqli_real_escape_string($conn,$_GET['me']);
//check if client has selected property

//check if terms js signed
$checkterms = mysqli_query($conn, "SELECT * FROM terms WHERE userid='$me'");
if(mysqli_num_rows($checkterms)>0){

$checkprop = mysqli_query($conn, "SELECT id FROM myproperty WHERE userid='$me'");
if(mysqli_num_rows($checkprop)>0){
$chwho = mysqli_query($conn, "SELECT id,accname,accno,bankname,userid,date,customer_code,customer_id FROM banks WHERE userid ='$me'");
if(mysqli_num_rows($chwho)>0){
$bank = mysqli_fetch_array($chwho);
		?>

  <!---<div class="my-3 tf-card-block">
  <a href="/paybybank/" class=" d-flex align-items-center justify-content-between">
                <div class="inner d-flex align-items-center" style="gap:5px">
                    <i class="logo icon-wallet-filled-money-tool"></i>
                    <div class="content">
                        <h4><?php print $bank['bankname'];?></h4>
                        <p class=""><?php print ucwords(strtolower($bank['accname']));?></p>
                        <p class=""><?php print $bank['accno'];?></p>
                    </div>
                </div>
                <input type="radio"  name="radio">
				
			</a>
            </div>--->
  <div class="my-3 tf-card-block">
  <a href="/paybybank/" class=" d-flex align-items-center justify-content-between">
                <div class="inner d-flex align-items-center" style="gap:5px">
                    <i class="logo icon-wallet-filled-money-tool"></i>
                    <div class="content">
                        <h4>Bank Transfer</h4>
                        <p class="">Pay to your generated Virtual Account</p>
                    </div>
                </div>
                <input type="radio"  name="radio">
				
			</a>
            </div>
<?php }else{?>
<div class="my-3 tf-card-block">
  <a href="/newaccount/" class=" d-flex align-items-center justify-content-between">
                <div class="inner d-flex align-items-center" style="gap:5px">
                    <i class="logo icon-wallet-filled-money-tool"></i>
                    <div class="content">
                        <h4>Activate Virtual Account</h4>
                        <p class="">Activate Bank Account Transfers</p>
                    </div>
                </div>
            </a>
            </div>
<?php }}else{?>
<div class="my-3 tf-card-block">
  <a href="/allproperty/" class=" d-flex align-items-center justify-content-between">
                <div class="inner d-flex align-items-center" style="gap:5px">
                  <svg width="49px" height="49px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M19 9.77806V16.2C19 17.8801 19 18.7202 18.673 19.3619C18.3854 19.9264 17.9265 20.3854 17.362 20.673C17.2111 20.7499 17.0492 20.8087 16.868 20.8537M5 9.7774V16.2C5 17.8801 5 18.7202 5.32698 19.3619C5.6146 19.9264 6.07354 20.3854 6.63803 20.673C6.78894 20.7499 6.95082 20.8087 7.13202 20.8537M21 12L15.5668 5.96393C14.3311 4.59116 13.7133 3.90478 12.9856 3.65138C12.3466 3.42882 11.651 3.42887 11.0119 3.65153C10.2843 3.90503 9.66661 4.59151 8.43114 5.96446L3 12M7.13202 20.8537C7.65017 18.6447 9.63301 17 12 17C14.367 17 16.3498 18.6447 16.868 20.8537M7.13202 20.8537C7.72133 21 8.51495 21 9.8 21H14.2C15.485 21 16.2787 21 16.868 20.8537M14 12C14 13.1045 13.1046 14 12 14C10.8954 14 10 13.1045 10 12C10 10.8954 10.8954 9.99996 12 9.99996C13.1046 9.99996 14 10.8954 14 12Z" stroke="#533dea" stroke-width="1.464" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                    <div class="content">
                        <h4>Select Property First</h4>
                        <p class="">You must subscribe to a property before creating virtual account</p>
                    </div>
                </div>
            </a>
            </div>
			
<?php }?>
<div class="my-3 tf-card-block">
  <a href="/paybycard/" class=" d-flex align-items-center justify-content-between">
                <div class="inner d-flex align-items-center" style="gap:5px">
                    <div class="logo-img">
                        <img src="images/logo-banks/card-visa2.png" alt="image">
                    </div>
                    <div class="content">
                        <h4>Pay with Card</h4>
                        <p>Pay with Card or Transfer</p>
                    </div>
                </div>
                <input type="radio" name="radio">
				</a>
            </div>
	<style>
	.anoda::before {
  content: "\e91e";
  color: #533dea !important;
}
</style>	

			
			<div class="my-3 tf-card-block">
  <a href="/paybycash/" data-reload-current="true" class=" d-flex align-items-center justify-content-between">
 
                <div class="inner d-flex align-items-center"  style="gap:5px">
                   <i class="logo icon-group-dollar anoda" style=""></i>
                    <div class="content">
                        <h4>Cash Deposit</h4>
                        <p>Deposit at our offices or to our agents</p>
                    </div>
                </div>
                <input type="radio" name="radio">
				</a>
            </div>

			
			<div class="my-3 tf-card-block">
  <a href="/recurring-payment/" class=" d-flex align-items-center justify-content-between">
 
                <div class="inner d-flex align-items-center" style="gap:5px">
                   <i class="logo icon-add-card"></i>
                    <div class="content">
                        <h4>Auto Debit </h4>
                        <p>Recurring Payment: Daily, Weekly etc.</p>
                    </div>
                </div>
                <input type="radio" name="radio">
				</a>
            </div>
			
<?php }else{?>	



<div class="my-3 tf-card-block">
  <a href="#" class=" d-flex align-items-center justify-content-between openterms">
                <div class="inner d-flex align-items-center" style="gap:5px">
                    <i class="logo icon-wallet-filled-money-tool"></i>
                    <div class="content">
                        <h4>Activate Virtual Account</h4>
                        <p class="">Activate Bank Account Transfers</p>
                    </div>
                </div>
            </a>
            </div>

<div class="my-3 tf-card-block">
  <a href="#" class=" d-flex align-items-center justify-content-between openterms">
                <div class="inner d-flex align-items-center" style="gap:5px">
                    <div class="logo-img">
                        <img src="images/logo-banks/card-visa2.png" alt="image">
                    </div>
                    <div class="content">
                        <h4>Pay with Card</h4>
                        <p>Pay with Card or Transfer</p>
                    </div>
                </div>
                <input type="radio" name="radio">
				</a>
            </div>
	<style>
	.anoda::before {
  content: "\e91e";
  color: #533dea !important;
}
</style>	

			
			<div class="my-3 tf-card-block">
  <a href="#" data-reload-current="true" class=" d-flex align-items-center justify-content-between openterms">
 
                <div class="inner d-flex align-items-center"  style="gap:5px">
                   <i class="logo icon-group-dollar anoda" style=""></i>
                    <div class="content">
                        <h4>Cash Deposit</h4>
                        <p>Deposit at our offices or to our agents</p>
                    </div>
                </div>
                <input type="radio" name="radio">
				</a>
            </div>

			
			<div class="my-3 tf-card-block">
  <a href="#" class=" d-flex align-items-center justify-content-between openterms">
 
                <div class="inner d-flex align-items-center" style="gap:5px">
                   <i class="logo icon-add-card"></i>
                    <div class="content">
                        <h4>Auto Debit </h4>
                        <p>Recurring Payment: Daily, Weekly etc.</p>
                    </div>
                </div>
                <input type="radio" name="radio">
				</a>
            </div>


			
	 <div class="tf-panel regpolicy2 panel-open">
        <div class="panel_overlay"></div>
          <div class="panel-box panel-center" style="height:82vh;width:90%;overflow-y:scroll;z-index:99999">
            <div class="heading rulesofengage" style="">
                                <div class="bg-primary-subtle position-relative">
                                    <div class="card-body ">
                                        <div class="text-center">
                                            <h3 class="fw-semibold">Bliss Legacy LTD</h3>
                                            <p class="mb-0 text-muted">Subscription/Agreement Form <span class="myname2"></span></p>

											<p class="mb-0 text-danger">Outright/Initial Deposit Form <span class="myname2"></span></p>
                                        </div>
                                    </div>
                               
                                </div>
                                <div class="card-body pt-0">
                                    <div class="PT-2">
                                        <h5>SECTION 1: SUBSCRIBERS DETAILS
 </h5>
                                        <p class="text-muted">TITLE: <div class="underlined"></div>
										.</p>
                                         <p class="text-muted">NAME: <div class="underlined"></div>
										.</p>
                                      <div class="row">  
                                      <div class="col-6">  
                                        <p class="text-muted">DATE OF BIRTH: <div class="underlined"></div>
										.</p>
                                    </div>

									<div class="col-6">  
                                        <p class="text-muted">GENDER: <div class="underlined"></div>
										.</p>
                                    </div>
                                    </div>
									
								<p class="text-muted">ADDRESS: <div class="underlined"></div>
										.</p>
										
										<h5>RESIDENTIAL ADDRESS IN CASE OF INDIVIDUAL AND REGISTERED BUSINESS ADDRESS IN CASE OF CORPORATE ORGANISATION</h5>
										
										                                         <p class="text-muted">ROAD/STREET: <div class="underlined"></div>
										.</p>
										                                         <p class="text-muted">TOWN/CITY/DISTRICT/STATE: <div class="underlined"></div>
										.</p>
										
										                                         <p class="text-muted">OCCUPATION: <div class="underlined"></div>
										.</p>
										                                         <p class="text-muted">ORGANISATION NAME: <div class="underlined"></div>
										.</p>
										
										                                         <p class="text-muted">EMAIL ADDRESS: <div class="underlined"></div>
										.</p>
										
 <div class="row">  
                                      <div class="col-6">  
                                        <p class="text-muted">MARITAL STATUS: <div class="underlined"></div>
										.</p>
                                    </div>

									<div class="col-6">  
                                        <p class="text-muted">NATIONALITY: <div class="underlined"></div>
										.</p>
                                    </div>
                                    </div>
																			
 <div class="row">  
                                      <div class="col-6">  
                                        <p class="text-muted">MOBILE NUMBER: <div class="underlined"></div>
										.</p>
                                    </div>

									<div class="col-6">  
                                        <p class="text-muted">ALTERNATE NUMBER: <div class="underlined"></div>
										.</p>
                                    </div>
                                    </div>
								
                                         <p class="text-muted">MODE OF IDENTIFICATION: <div class="underlined"></div>
										.</p>
<i>Drop a copy of any id with the office</i>
									
									<h5>SECTION: IDENTITY PROOF OF NEXT OF KIN</h5>
                                    </div>
									
									                                         <p class="text-muted">IDENTITY PROOF NAME: <div class="underlined"></div>
										.</p>
										
										                                         <p class="text-muted">ADDRESS: <div class="underlined"></div>
										.</p>
										
										                                         <p class="text-muted">PHONE: <div class="underlined"></div>
										.</p>
										
										                                         <p class="text-muted">RELATIONSHIP TO NEXT OF KIN: <div class="underlined"></div>
										.</p>
										
										<h5>SECTION 3: CHOICE OF PROPERTY</h5>
										                                         <p class="text-muted">ESTATE NAME: <div class="underlined"></div>
										.</p>
										
										 <div class="row">  
                                      <div class="col-6">  
                                        <p class="text-muted">PLOT SIZE: <div class="underlined"></div>
										.</p>
                                    </div>

									<div class="col-6">  
                                        <p class="text-muted">AMOUNT PAID: <div class="underlined"></div>
										.</p>
                                    </div>
                                    </div>
      <style>
	  .underlined{
		  min-width:100%;
		  border-bottom:1px;
		  border-bottom-style: solid;
	  }
	  .hered li{
	  list-style-type: circle;
	  list-style-position: inside;
	  }
	  </style>                              
                                    <div class="pt-2">
                                      
                                        <p class="text-muted pt-1" style="font-weight:700">TYPE OF DISTRICT::</p>
                                        <ul class="text-muted vstack gap-2 hered">
                                            <li>
                                               SERVICED DISTRICT ____________________
                                            </li>
                                            <li>
                                               NON-SERVICED DISTRICT: ________
                                            </li>
                                            
                                           
                                        </ul>
                                        <p class="text-muted pt-1" style="font-weight:700">PAYMENT PLAN::</p>
                                        <ul class="text-muted vstack gap-2 hered">
                                            <li>
                                               OUTRIGHT 3 MONTHS PLAN: _________
                                            </li>
                                            
                                            <li>
                                               OUTRIGHT 6 MONTHS PLAN: __________
                                            </li>
                                            
                                           
                                        </ul>
                                      <p class="text-muted pt-1" style="font-weight:700">HOW DID YOU HEAR ABOUT US::</p>
                                        <ul class="text-muted vstack gap-2 hered">
                                            <li>
                                               RADIO: ________________________
                                            </li>
                                            
                                            <li>
                                               SOCIAL MEDIA: __________________
                                            </li>
                                            
                                            <li>
                                               BILLBOARD: _____________________
                                            </li>
                                            
                                            <li>
                                               STAFF/REALTOR: ___________________
                                            </li>
                                            
                                            <li>
                                               OTHERS: ___________________________
                                            </li>
                                            
                                           
                                        </ul>
                                      
                                    </div>
 <div class="row">  
                                      <div class="col-6">  
                                        <p class="text-muted">REFERRED NY: <div class="underlined"></div>
										.</p>
                                    </div>

									<div class="col-6">  
                                        <p class="text-muted">PHONE NO: <div class="underlined"></div>
										.</p>
                                    </div>
                                    </div>
                               
							    <div class="text-center">
                                            <h3 class="fw-semibold">Bliss Legacy LTD</h3>
                                            <p class="mb-0 text-muted">Agreement Form <span class="myname2"></span></p>

                                        </div>
										
										<hr>
                                    <div class="pt-2">
                                      
                                        <p class="text-muted"><p><b class="text-danger">OUTRIGHT</b> (strictly for outright clients)
That I have agreed to make the total payment of the sum of (in words) ____________________ (in figures) ______________________ as the complete. That I paid this money into payment for______ plot(s) of land in  _________________estate located in __________ community.</p>

<p>Note that the amount of _________________ paid STRICTLY entitles you to only the ownership of the plot of land as this excludes other features in the estate.</p>

<b class="text-danger">PART PAYMENT</b> (strictly for part payment clients)
<ul class="text-muted vstack gap-2 hered">
<li>That I have agreed to make part payment of the sum of
N_____________ (if others , please specify) _________________ in accordance with plan _____________________________ as contained in ______________________.</li>

<li>That this payment plan terminates: __________________
That there shall be a grace period of two week(s) only after the expiration of my agreed payment period. </li> 

<li>That failure to make full payments within the stipulated time (grace period included), shall attract a 5% of the total money added as default on the actual sum of the plot monthly.</li>

<li>That if I commence payment during promo time, I shall ensure I complete the payments before the promo elapses. Failure to complete the payments during promo time, I shall be bound to pay the exact amount for the plot of land (non-promo price). </li>

<li>In times where gifts are attached to promo periods, clients are to know that gifts will be delivered at least two (2) months after payment.</li>

<li>That this contract terminates___________________________</li>

<li>There is an external grace period of two(2) weeks  only
From ________________________________ this contract has been terminated; penalty starts applying from this day.</li>
</ul>

<h5 class="text-success">FOR ALL (BOTH PART PAYMENT/OUTRIGHT)</h5>
<ul class="text-muted vstack gap-2 hered">
<li>That I understand that allocation of land shall be done once in six months. </li>
<li>That I paid the sum of (in words) _______________________ in figures __________________________
<br>Into the following account details;
<br>BANK DETAILS:
<br>BANK NAME: ___________________________________________________________
<br>ACCOUNT NO.__________________________________________________________
<br>ACCOUNT NAME________________________________________________________

</li>
<li><b class="text-danger">CAUTION:</b> I know that I am not supposed to pay money into any private account, no matter who the officer is, Other than BLISS LEGACY LTD account or virtual account generated from the company app (BLISS PAY APP) carrying BLISS LEGACY/client’s name. Money paid into any private account or cash paid in the office is not recognized by BLISS LEGACY LTD and it’s at payer’s risk. </li>

<li>That upon payment, I hereby agree to abide by the rules and regulations guiding the ESTATES of Bliss Legacy Limited as enumerated below:</li>
</ul>

<p>Terms and Conditions;</p>
<ul class="text-muted vstack gap-2 hered">
<li>
That upon allocation, I shall take full possession and undertake the construction of my building to lintel level within but not later than six (6) months in compliance with the development policy of BLISS LEGACY LTD to foster rapid appreciation of the value of the land.</li>

<li>That in the event I leave my piece of land unkept, Bliss Legacy Limited reserves the right to enter into my piece of land to keep it tidy and all expenses incurred will be borne by me.</li>

<li>If I am acquiring this land for the purpose of investment, I shall not seek instant allocation, except in cases where I want to start building or to resell. But in any event that I am allocated to, BLISS LEGACY LTD is given the right to reallocate me to any other parts of the ESTATE in order to give space to those who want to build, so as to enforce rapid development of the ESTATE.</li>

<li>That the Company’s representative must be present during the ground breaking, and foundation laying in order to avoid encroachment.</li>

<li>That I shall not build any commercial building on this plot as it is strictly a residential plot.</li>

<li>That I shall not build any form of tenement structure (that is, face-me-I-face-you) in the residential area.</li>

<li>That I shall submit my Architectural plan to Bliss Legacy’s Limited Architectural Department for inspection and approval before commencing building.
That I shall pay ten percent (10%) of the total amount paid to Bliss Legacy Limited for documentation/processing.</li>

<li>SERVICE DISTRICT(applies only to client that chose service districts)</li>
</ul>
<ul class="text-muted vstack gap-2 hered">
<li>That if I choose the service district of the Estate, upon readiness to build, you are to pay the sum of Four Million Naira (4,000,000) infrastructure fee which covers, good access roads, light, clean water, perimeter fence, drainage system, horticulture designs and layouts including infrastructure and developments, estate facilities, recreational center, children play ground, drainage within the perimeter of your plot(s), various kinds of roads including earth road, inter-locking and main drains on major roads.</li>

<li>Upon completion of my 4,000,000 development fee, I shall give Bliss Legacy Limited 1 to 2 years to deliver the estate feature I paid for.</li>

<li>I understand that the service district of the ESTATE comes with restrictions and service charge (security, cleaning and waste management, laundry services and many more) which makes the ESTATE habitable and conducive for all residents.</li>
</ul>
<p>Note that residents are responsible for the maintenance of the facilities provided within the ESTATE.</p>

<li>NON SERVICE DISTRICT (applies only to clients that chose non-service districts)</li>
<ul class="text-muted vstack gap-2 hered">

<li>That the N4,000,000 development fee does not apply to the non- service district subscribers. However, subscribers are taking full responsibility of the development and maintenance of the district.</li>

<li>BLISS LEGACY LTD is to create access roads for the purpose of allocation afterwards; subscribers are to maintain the road and plots by themselves. If the roads are abandoned for a long time, BLISS LEGACY LTD reserves the right to maintain the roads and plots while subscribers pay the cost of maintenance so as to avoid untidiness and  constituting security risk to other ESTATE residents. </li>

<li>Upon the desire to switch to service district area of the Estate, Clients are free to change their district before allocation but changes after allocation; However, changes after allocation attracts an additional fee of #1,000,000, making the sum of the development fee to be #5,000,000. (And this depends on the availability of the plots)</li>
</ul>

<p>(L). CONDITION FOR RESELLING PLOTS IN SERVICE AND NON SERVICE DISTRICTS</p>
<ul class="text-muted vstack gap-2 hered">
<li>That if I decide to resell (whole or part) of my land, all rules and regulations on the subscription form/deed of transfer shall be transferred hence, binding on the new buyer.</li>

<li>That in the event of a transfer of ownership (under any circumstances) or outright sale of either part or whole of my plot of land to a subsequent purchaser, the new owner shall pay ten percent (10%) of the current amount of purchase to Bliss Legacy Limited for processing relevant documents.
Residential plots can only be sold as residential plots and cannot be converted to commercial plots under any circumstance.</li>
</ul>
<p>GENERALLY; </p>
<ul class="text-muted vstack gap-2 hered">
<li>That Bliss Legacy Limited shall not make any refund after payment. But in the event that I wish to rescind the contract after payment, I shall notify Bliss legacy Ltd within one week after payment (either payment in installments or outright) and I shall forfeit forty percent (40%) of such amount to Bliss Legacy Limited for administrative charges; and such refund shall be made after ninety(90) days.</li>

<li>That the amount I paid makes me the owner of the plot but BLISS LEGACY LIMITED remains the owner and sole regulator of the ESTATE,</li>

<li>That I agree to resolve all dispute that may arise from my transaction with Bliss Legacy Limited amicably or refer the matter to Arbitration at the Multi-door Court in the state or city where the transaction was consummated. </li>    

</ul>




____________________						_____________________
Client’s Signature							Date

.</p>
                                        
                                    </div>

                              
                                </div>
                            </div>
							
							 <div class="bottom" style="display:flex;position:relative;bottom:0px;right:0;left:0">
                      <button class="btn w-50 btn-secondary engagebtn" style="border-radius:0px">Decline</button>
               <button class="btn btn-block btn-primary w-50 engagebtn rulesengage" style="border-radius:0px"> I Accept</button> 
                </div>
          </div>
    </div>


<?php }?>