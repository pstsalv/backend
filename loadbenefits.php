<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');
include_once("conn.php");
$me = mysqli_real_escape_string($conn, $_GET['me']);
?>
<option selected="" disabled="">Choose Benefitiary</option>
<?php
$check = mysqli_query($conn, "SELECT * FROM benefitiary WHERE owner_id='$me'");
while($bank = mysqli_fetch_array($check)){
?>
<option value="<?php print $bank['acc_no'];?>" data-accname="<?php print $bank['acc_name'];?>" data-bankname="<?php print $bank['bank_nane'];?>" data-bankcode="<?php print $bank['bankcode'];?>" data-recipent="<?php print $bank['recipient_code'];?>"><?php print $bank['acc_name'];?> - <?php print $bank['bank_nane'];?> </option>
<?php }?>