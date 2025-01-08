<?php
include_once("conn.php");
$check = mysqli_query($conn, "SELECT * FROM users WHERE address!='' AND lat='' AND lat!='none' AND account_type='customer' AND wallet_bal>0 AND account_no!='' LIMIT 400");
if(mysqli_num_rows($check)>0){
while($user=mysqli_fetch_array($check)){
$address = urlencode($user['address']);
$apiKey = 'AIzaSyCl2wtzcjTd1ekKgpNNgQRNuqRjtM8qRic';
$url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$apiKey}";

$response = file_get_contents($url);
$json = json_decode($response, true);

if ($json['status'] === 'OK') {
    $location = $json['results'][0]['geometry']['location'];
    echo 'Latitude: ' . $location['lat'] . ', Longitude: ' . $location['lng'].'<br>';

mysqli_query($conn, "UPDATE users SET lat='$location[lat]',lng='$location[lng]' WHERE id='$user[id]'");
} else {
mysqli_query($conn, "UPDATE users SET lat='none',lng='none' WHERE id='$user[id]'");
    echo 'Geocode was not successful for the following reason: ' . $json['status'];
}
}
}
?>