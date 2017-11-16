<?php
require_once('userstatus.php');
$target_dir = "img/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$target_file = $target_dir . "mo_" . $_SESSION['mouserid'] . "_" . time() . "." . $imageFileType;
$output="";
$query="";
$imagetype = $_REQUEST['imagetype'];
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $output = "上傳失敗，只支持JPG、PNG、與GIF圖片文件";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    $output = "上傳失敗，文件已存在";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    $output = "上傳失敗，文件超過 5MB 大小";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
    $output = "上傳失敗，只支持JPG、PNG、與GIF圖片文件";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    header('Location: ' . $_SESSION['mocurrenturl'] . '?adminbgupload=0&msg=' . $output);
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        if($imagetype=="homepagebg") {
            $query = "insert into mohomepagebg (FileName,FileUrl,CreatedDate) VALUES ('" . basename( $_FILES["fileToUpload"]["name"]) . "','/" . $target_file . "',NOW())";
        } else if($imagetype=="merchantbg") {
            $query = "update momerchantbg set selected=0 where MerchantId=" . $_SESSION['mouserid'];
            $mysqli->query($query);
            $query = "insert into momerchantbg (MerchantId,FileName,FileUrl,CreatedDate,selected) VALUES (" . $_SESSION['mouserid'] . ",'" . basename( $_FILES["fileToUpload"]["name"]) . "','/" . $target_file . "',NOW(),1)";
        }
        $mysqli->query($query);
        header('Location: ' . $_SESSION['mocurrenturl'] . $_SERVEr['HTTP_REFERER'] . $mark . '?adminbgupload=1&msg=您的文件 '. basename( $_FILES["fileToUpload"]["name"]) . ' 已上傳成功');
    }
}
?>