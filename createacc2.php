<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header('Cache-Control: no-cache');
include_once("conn.php");
$me = mysqli_real_escape_string($conn,$_POST['me']);
$fname = mysqli_real_escape_string($conn,$_POST['fname']);
$lname = mysqli_real_escape_string($conn,$_POST['lname']);
$email = mysqli_real_escape_string($conn,$_POST['email']);
$phone = mysqli_real_escape_string($conn,$_POST['phone']);

$paystack_key = $payapi;
$account_name = $fname.' '.$lname;
$options = array(
    'type' => 'nuban',
    'name' => $account_name,
    'description' => 'Bliss Legacy Lagos',
    'integration' => 1
);
$options_json = json_encode($options);
$url = "https://api.paystack.co/transferrecipient";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $options_json);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer {$paystack_key}",
    'Content-Type: application/json'
));

$result = curl_exec($ch);


// Check for errors and handle the response
if ($result === false) {
    echo 'Error: ' . curl_error($ch);
} else {
    $response = json_decode($result, true);
    if ($response['status'] === true) {
		$accno = $response['data']['account_number'];
		
		mysqli_query($conn, "INSERT INTO banks VALUES(NULL,'$fname $lname','in progress','Wema Bank','$accno','$me',now(),'$email','')");
		
        echo 'Virtual account created successfully: ' . $response['data']['account_number'];
    } else {
        echo 'Error creating virtual account: ' . $response['message'];
    }
}

// Close the cURL handle
curl_close($ch);

?>