<?php
include_once("conn.php");
echo '<h1>Delete your account from the app </h1>
<h2>or enter your email in the field below </h2>
<h3>to deactivate your account.</h3>';

if(isset($_POST['email'])){
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	mysqli_query($conn,"UPDATE users SET status='deleted',account_type='deleted' WHERE email='$email'");
	if(email){
		echo '<script>alert("User account deleted");</script>';
	}
}
?>

<form action="" method="post">
<input type="email" name="email" placeholder="Enter your email" />
<button type="submit">Delete my account</button>
</form>