<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');
include_once("conn.php");

$id = mysqli_real_escape_string($conn, $_GET['id']);
if(isset($_POST['uid'])){
$uid = mysqli_real_escape_string($conn, $_GET['uid']);
}
$check = mysqli_query($conn, "SELECT * FROM tickets WHERE id='$id'");
$tickets = mysqli_fetch_array($check);
?>
 <div class="app-header st1">
        <div class="tf-container">
            <div class="tf-topbar d-flex justify-content-center align-items-center">
               <a href="#" class="back-btn back"><i class="icon-left white_color"></i></a> 
                <h3 class="white_color">Reply to ticket</h3>
				 <a href="javascript:intercom.displayMessenger();" style="font-size:30px;background:none;position:absolute;right:10px"><span class="material-icons text-white">headset_mic</span></a>
				 
				 <a href="/tech-support/" style="font-size:30px;background:none;position:absolute;right:50px"><span class="material-icons text-white">receipt</span></a>
            </div>
        </div>
    </div>
     <div class="card-secton">
        <div class="tf-container">
            <div class="tf-balance-box">
                <div class="balance">
                    <div class="row">
                        <div class="col-6 br-right">
						<a href="#">
                            <div class="inner-left">
                                <p>Ticket ID:</p>
                                <h3 class="text-success">#<?php print $tickets['ticketno'];?></h3>
                            </div>
							</a>
                        </div>
                        <div class="col-6">
						<a href="#">
                            <div class="inner-right">
                                <p>Date Created</p>
                                <h3 class="text-danger">
                                <?php print date('d/m/Y',strtotime($tickets['created_date']));?>
                               
                                </h3>
                            </div>
							</a>
                        </div>
                    </div>
                </div>
                <div class="wallet-footer">
                    <div class="justify-content-between align-items-center">
                   
					 <h2 class="text-center"><?php print ucwords($tickets['category']);?></h2>
                        <div class=" text-dark text-center"><?php print ucwords($tickets['title']);?></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
	
	

    <div class="card mt-5">
	<span class="badge bg-danger" style="position:absolute">The Issue</span>
    <div class="card-body text-dark text-center">
	<?php print ucfirst($tickets['message']);?>
	</div>
	</div>
	
	
    <div class="transfer-card mt-5">
        <div class="tf-container receivepay">
         
 <form class="tf-form submitissueform2">
                  
				<input type="hidden" name="fullnames" class="fullnames" />
				<input type="hidden" name="me" class="me" />
				
                <div class="group-input">
                    <label class="text-dark">Agent UID</label>
                    <input type="tel" value="<?php print $tickets['userid'];?>" class="myuid" name="uid" required readonly />
                    <div class="credit-card">
                        <i class="icon-user"></i>
                    </div>
    
                </div>
                
              
			  <div class="group-input">
                    <label class="text-dark" for="">Your reply</label>
                    <textarea rows="4" name="descr" required placeholder="Has the issue been resolved"></textarea>
					 <div class="credit-card">
                        <i class="icon-edit"></i>
                    </div>
                </div>
            
              
                <div class="bottom-navigation-bar bottom-btn-fixed st2">
                    <button type="submit" class="tf-btn accent large" id="btn-popup-down">Submit Reply</button>
                </div>

             </form>
			 
			 
        </div>
      
    </div>
    