<?php
header("access-control-allow-origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$trans = mt_rand(100000,999999);
$billerscode = mysqli_real_escape_string($conn, $_POST['billerscode']);
$serviceid = mysqli_real_escape_string($conn, $_POST['serviceid']);

// Get the current date and time in Africa/Lagos timezone
date_default_timezone_set('Africa/Lagos');
$currentDateTime = new DateTime();

// Format the date and time as YYYYMMDDHHII
$requestId = $currentDateTime->format('YmdHi');

// Concatenate any other alphanumeric string as desired
$requestId .= 'ad8ef08acd8fc0f'; // Example additional string

// Ensure the request_id is 12 characters or more
if (strlen($requestId) < 12) {
    echo "Error: Request ID must be at least 12 characters.";
	$request_id =0;
} else {
    $request_id = $requestId;
}


// VTpass API endpoint
$apiUrl = 'https://sandbox.vtpass.com/api/merchant-verify'; // Replace with the actual API endpoint

// Your API keys
$apiKey = '58a4990c81806e9c9a6af0bea85b3f17';
$secretKey = 'SK_5347441ff72098adc918aa4d95459fdb17292a7a64c';

// Data to send (example payload)
$data = [
    'request_id' => $request_id,
    'serviceID' => $serviceid,
    'billersCode' => $billerscode,
];
// Convert data to JSON
$jsonData = json_encode($data);

// Initialize cURL session
$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'api-key: ' . $apiKey,
    'secret-key: ' . $secretKey,
]);

// Execute the request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    // Process the response (e.g., decode JSON)
    $decodedResponse = json_decode($response, true);
	echo $response;

}

// Close cURL session
curl_close($ch);
?>