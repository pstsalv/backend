<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache'); 

include_once("conn.php");

$me = mysqli_real_escape_string($conn,$_GET['me']);
$region = mysqli_real_escape_string($conn,$_GET['region']);

$checkwho = mysqli_query($conn,"SELECT * FROM users WHERE id='$me'");
$agent = mysqli_fetch_array($checkwho);
?>
<div class="trading-month border">
                 
                        <div class="group-trading-history mb-1">
<?php
$check = mysqli_query($conn, "SELECT * FROM users WHERE account_no='$me' AND (region LIKE '%$region%' OR state='$agent[state]')");
if(mysqli_num_rows($check)>0){
	while($users = mysqli_fetch_array($check)){
?>
<a class=" tf-trading-history bg-white p-2 w-100 shadow-xs sheet-open" data-sheet=".propoptt<?php print $users['id'];?>" href="#" style="border-radius:5px">
<div class="inner-left">
                                  
									<div class="thumb" style="object-fit:cover">

                                        <img src="<?php print $kaylink;?>/pix/thumb/<?php print $users['pix'];?>" alt="image" style="object-fit:cover;height:40px;width:40px" />
                                    </div>
                                    <div class="content">
                                      
 <h4><?php print ucwords($users['fname'].' '.$users['lname']);?></h4>


                                        <p class="clientadd"><?php print $users['address'];?></p>
                                    </div>
                                </div>
                                <span class="num-val "><i class="icon-more1"></i></span>
                            </a>

	<div class="sheet-modal propoptt<?php print $users['id'];?>" style="height:70%;background:transparent">
      <div class="sheet-modal-inner" style="background:transparent">
 <div class="tf-panel up panel-open">
   
          <form class="tf-form verifypayment">
		  
		  <div class="panel-box panel-up wrap-panel-clear panel-change-profile">
            <div class="heading">
              <a class="external sheet-close" href="tel:<?php print $users['phone'];?>">Call <?php print $users['fname'];?></a>

			
				<a class="external sheet-close " href="geo:<?php print $users['lat'];?>,<?php print $users['lng'];?>">Directions to <?php print $users['fname'];?></a>
				
				<a class="iamhere sheet-close" data-customer="<?php print $users['id'];?>">I am Here!</a>
	
            </div>
            

			<div class="bottom">
                <a class=" sheet-close" href="#">Dismiss</a>
            </div>
          </div>
		  </form>
    </div>
	
	
  </div>
</div>



<?php }}?>
</div>
</div>