<?php
$conn = mysqli_connect("localhost","root","","ogabliss");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to Database: " . mysqli_connect_error();
  }

$payapi = 'sk_live_250c0f0d8ca26ff0f78723498fe18e748431c84b';
$payhapi = 'sk_live_7303e6ad47bf4ef9d00c598b7a3091ddbb4ca8c0';

$llf =  basename(dirname(__FILE__));
if($llf==""){
	$llf2 = "app";
}else{
	$llf2 = $llf;
}
  $kaylink = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].'/'.$llf2;
?>