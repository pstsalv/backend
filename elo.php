<?php
$smscode = mt_rand(1000,9999);
$tokend = mt_rand(10000,99999);
include_once("conn.php");
define('API_ACCESS_KEY','AAAA6Grioxw:APA91bG054m9n6XW9NkTgyDvhTXgX1QOqsCSlmk7kO_UIPlcccwvEb9u7pbakDjVZ2uYEDD3ACOGYACpddRvOS5uIqZOQR18bC17BlA7DEAzHeVjBdMw5LcdBLIFYaCdyZY46puaeXXe');
 $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

$checkads = mysqli_query($conn, "SELECT id,owner,type,title,message,status,date FROM notification WHERE status='unread' AND owner!=''");
if(mysqli_num_rows($checkads)>0){
while($ads = mysqli_fetch_array($checkads)){
$doit =$ads['id'];

	$check2 = mysqli_query($conn, "SELECT id,owner,token,date,type,owner_token,name FROM tokens WHERE owner='$ads[owner]'");
	if(mysqli_num_rows($check2)>0){
		$dtokens = mysqli_fetch_array($check2);
	$token = $dtokens[2];



//$checkuser = mysqli_query($conn, "SELECT id,fname,lname,email,phone,userid,pix,state,region,address,dob,account_no,planid,status,password,account_type,date,otpcode,wallet_bal,uncleared,pincode,gender,agent,position FROM users WHERE id='$ads[owner]'");
//$user = mysqli_num_rows($checkuser);


$dpage = '/alerts/';


	$imglink = $kaylink.'/pix/logo.png';

    $notification = [
            'id' =>  $ads['id'],
            'title' =>  $ads['title'],
            'body' =>  $ads['message'],
			'vibrate'	=> '500, 200, 500',
			 "content-available" => '1',
			'light' => true,
			'visibility' =>1,
			'image' => $imglink
        ];
        $extraNotificationData = ["notification_foreground" => "true","message" => $notification,"moredata" =>$dpage];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);

		$results = json_decode($result, true);

		if($results['success']==true){
			mysqli_query($conn, "UPDATE notification SET status='notified' WHERE id='$doit'");

		}
}
}
}
?>