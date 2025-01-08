<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");

include_once("conn.php");

$msd = mysqli_query($conn, "SELECT id,userid,amount,status,prev_amt,paid_by,admin_approved,date_paid,payment_method,notes,paidfor,payref,state,zoneid,agentid,propert_type FROM payment WHERE payment_method!='Paystack'");
echo mysqli_num_rows($msd);


// Find duplicate records in the 'employees' table 
//$result = mysqli_query($conn, 'SELECT payref, COUNT(*) FROM payment GROUP BY payref HAVING COUNT(*) > 1'); 
 
// Output the duplicate records 
//while ($row = mysqli_fetch_array($result)) { 
  //  echo 'Duplicate record: ' . $row['payref'] . ' (' . $row['COUNT(*)'] . ' copies) <br>'; 
//} 
?>