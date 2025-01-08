<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400");
include_once("conn.php");

$me = mysqli_real_escape_string($conn, $_POST['me']);
$dpixx = mysqli_real_escape_string($conn, $_POST['dpixx']);

function createThumbnail($sourcePath, $targetPath, $file_type, $thumbWidth, $thumbHeight) {
    $max_size = 400;
    $size = getimagesize($sourcePath);
    $ratio = $size[0] / $size[1];
    $widthy = ($ratio >= 1) ? $max_size : round($max_size * $ratio);
    $heighty = ($ratio >= 1) ? round($max_size / $ratio) : $max_size;

    $source = ($file_type == "png") ? imagecreatefrompng($sourcePath) : imagecreatefromjpeg($sourcePath);
    $width = imagesx($source);
    $height = imagesy($source);

    $tnumbImage = imagecreatetruecolor($widthy, $heighty);
    imagecopyresampled($tnumbImage, $source, 0, 0, 0, 0, $widthy, $heighty, $width, $height);

    if (imagejpeg($tnumbImage, $targetPath, 90)) {
        imagedestroy($tnumbImage);
        imagedestroy($source);
        return TRUE;
    } else {
        return FALSE;
    }
}

if (is_array($_FILES)) {
    foreach ($_FILES['file']['name'] as $name => $value) {
        if (is_uploaded_file($_FILES['file']['tmp_name'][$name])) {
            $sourcePath = $_FILES['file']['tmp_name'][$name];
            $targetPath = "pix/" . $_FILES['file']['name'][$name];
            $targetPath2 = "pix/thumb/" . $_FILES['file']['name'][$name];
            $imagename = $_FILES['file']['name'][$name];
            $imagen_ext = strtolower(pathinfo($imagename, PATHINFO_EXTENSION));
            if (in_array($imagen_ext, ["png", "jpg", "jpeg"])) {
                $thumb2 = createThumbnail($sourcePath, $targetPath2, $imagen_ext, 300, 185);
            } else {
                die('error');
                exit();
            }

            $dimage = move_uploaded_file($sourcePath, $targetPath) ? $imagename : $dpixx;
        } else {
            $dimage = $dpixx;
        }
    }
} else {
    $dimage = $dpixx;
}

$check = mysqli_query($conn, "SELECT id FROM users WHERE id='$me'");
if (mysqli_num_rows($check) > 0) {
    mysqli_query($conn, "UPDATE users SET pix='$dimage' WHERE id='$me'") or die(mysqli_error($conn));
    echo 'success';
} else {
    echo 'error';
}
?>
