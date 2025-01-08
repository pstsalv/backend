<?php
include('conn.php');
//check customer account
$checkprop = mysqli_query($conn, "SELECT id,userid,amount,status,prev_amt,paid_by,admin_approved,date_paid,payment_method,notes,paidfor,payref,state,zoneid,agentid,propert_type FROM payment WHERE paidfor!='' AND paidfor!='null'");
if(mysqli_num_rows($checkprop)>0){
	while($user=mysqli_fetch_array($checkprop)){
		//check property details
		$checkprod = mysqli_query($conn, "SELECT id,title,description,amount,images,location,status,date,provider,popular,prop_uid,estate_name,unit_available,plots,prop_category,prop_full_desc,canpay_installment,state,axis,promotitle,promocode,promogift,promoprice,type,propertycode,dailyones FROM property WHERE id='$user[paidfor]'");
		$propdetails = mysqli_fetch_array($checkprod);
//update customer payment
mysqli_query($conn,"UPDATE payment SET propert_type='$propdetails[canpay_installment]' WHERE id='$user[id]'");
echo 'done for '.$user['amount'].' - '.$propdetails['canpay_installment'].'<br>';
	}
}
?>