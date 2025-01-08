<?php
include('conn.php');
$check = mysqli_query($conn,"SELECT id, userid, COUNT(userid) FROM users GROUP BY userid HAVING COUNT(userid) > 1");
while($ddup = mysqli_fetch_array($check)){
echo $ddup['id'].' - ';
echo $ddup['userid'].' <br>';
//mysqli_query($conn, "UPDATE users SET userid=userid+4567 WHERE id='$ddup[id]'");
}