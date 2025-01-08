<?php
$checkme = mysqli_query($conn, "SELECT * FROM users WHERE USERID='$me'");
$myacc = mysqli_fetch_array($checkme);

$checkp =  mysqli_query($conn, "SELECT * FROM users WHERE account_type='customer' AND position='unpaid' AND (agent='$me' OR account_no='$me')");
$payingcl = mysqli_num_rows($checkp);

$checkpun =  mysqli_query($conn, "SELECT * FROM users WHERE account_type='customer' AND position='paid' AND (agent='$me' OR account_no='$me')");
$unpayingcl = mysqli_num_rows($checkpun);


$checkrevt = mysqli_query($conn,"SELECT SUM(amount) AS targetsamt FROM targets WHERE status='ongoing' AND branchid='$myacc[branchid]'");
$rowppt = mysqli_fetch_assoc($checkrevt);
$sumppt = $rowppt['targetsamt'];
$totalpaydt = "$sumppt";
if($totalpaydt !==""){
$mytarg = $totalpaydt;
}else{
$mytarg = "0.00";
};


$checkexp = mysqli_query($conn,"SELECT SUM(initial_deposit) AS expecto FROM myproperty WHERE type='daily' AND amt_paid<propamount AND userid!='null' AND userid!='' AND branchid='$myacc[branchid]'");
$rowex = mysqli_fetch_assoc($checkexp);
$sumex = $rowex['expecto'];
$totalex = "$sumex";
if($totalex !==""){
$paidex = $totalex;
}else{
$paidex = "0.00";
};


$checkrev1 = mysqli_query($conn,"SELECT SUM(amount) AS paidtodayg FROM payment WHERE status='approved' AND DATE(date_paid) = CURDATE() AND admin_approved='virtual' AND (agentid='$myacc[userid]' OR collectorid='$myacc[userid]' OR custcare='$myacc[id]')");

$rowpp1 = mysqli_fetch_assoc($checkrev1);
$sumpp1 = $rowpp1['paidtodayg'];
$totalpayd1 = "$sumpp1";
if($totalpayd1 !==""){
$paidtoday = $totalpayd1;
}else{
$paidtoday = "0.00";
};
?>
<h2 class="px-3 pb-2">My Statistics</h2>
<div class="row row-cols-xxl-5 row-cols-md-3 row-cols-1 g-0">
                                     
                                        <div class="col-6 border">
                                            <div class="mt-3 mt-md-0 py-2 px-3">
                                                <h5 class="text-muted text-uppercase fs-13">Paying Client <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                       <i class="ri-exchange-dollar-line display-6 text-muted"></i>

                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h2 class="mb-0"><?php print $payingcl;?><span class="counter-value">%</span></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col-6 border">
                                            <div class="mt-3 mt-md-0 py-2 px-3">
                                                <h5 class="text-muted text-uppercase fs-13">Unpaying <i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i></h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                    <i class="ri-pulse-line display-6 text-muted"></i>

                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h2 class="mb-0"><span class="" ><?php print $unpayingcl;?></span>%</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end col -->

   <div class="col border">
                                            <div class="py-3 px-3">
                                                <h5 class="text-muted text-uppercase fs-13">Branch Targets <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                      <i class="ri-space-ship-line display-6 text-muted display-6 text-muted"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h2 class="mb-0"><span style="font-size:12px">₦</span> <?php print number_format($mytarg);?></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end col -->


                                        <div class="col border">
                                            <div class="mt-3 mt-lg-0 py-2 px-3">
                                                <h5 class="text-muted text-uppercase fs-13">Income Today <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <img src="<?php print $kaylink;?>/pix/dollaricon.png" alt="" style="height:20px;width:20px" />
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h2 class="mb-0"><span style="font-size:12px">₦</span><?php print number_format($paidtoday);?></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end col -->
                                        <div class="col">
                                            <div class="mt-3 mt-lg-0 py-3 px-3">
                                                <h5 class="text-muted text-uppercase fs-13">Expected Income Today <i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i></h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                       <img src="<?php print $kaylink;?>/pix/expected.png" alt="" style="height:20px;width:20px" />
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h2 class="mb-0"><span style="font-size:12px">₦</span> <?php print number_format($paidex);?></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- end col -->
                                    </div>

