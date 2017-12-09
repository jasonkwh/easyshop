<?php
$lifetime=1800;
if (session_id() === "") {
    session_start([
        'cookie_lifetime' => $lifetime,
    ]);
}
setcookie(session_name(),session_id(),time()+$lifetime);
require_once('db.php');

$output = '<button class="col-md-4 moproducts grow" data-toggle="tooltip" data-placement="bottom" data-original-title="點擊管理商品圖片" onclick="openproductimgmanager()" background:url(';
$query = "select Id,FileName,FileUrl,selected from moproductimgs where TrashedDate is null and ProductId=" . $_REQUEST['id'];
$result = $mysqli->query($query);
$mainimgurls = '';
$imageurls = array();
if(($result) && ($result->num_rows)) {
    while($row = $result->fetch_assoc()) {
        if($row['selected']==1) {
            $mainimgurls = $row['FileUrl'];
        } else {
            array_push($imageurls,$row['FileUrl']);
        }
    }
}
if($mainimgurls=='') {
    $mainimgurls = '/img/emptyimage.jpg';
}
if(isset($imageurls[0])) {
    $imageurls[0] = '/img/emptyimage.jpg';
}
if(isset($imageurls[1])) {
    $imageurls[1] = '/img/emptyimage.jpg';
}
if(isset($imageurls[2])) {
    $imageurls[2] = '/img/emptyimage.jpg';
}
$output .= $mainimgurls . ');background-repeat:no-repeat;background-size:cover;max-width:380px;max-height:380px;width:auto;height:auto;border-top-left-radius:5px;border-bottom-left-radius:5px;border:none"></button>
<div class="col-md-2"><div class="row"><button data-toggle="tooltip" data-placement="bottom" data-original-title="點擊管理商品圖片" onclick="openproductimgmanager()" class="col-md-12 mosubproducts grow-sm" style="background:url('
. $imageurls[0] . ');background-repeat:no-repeat;background-size:cover;border:none"></button><button data-toggle="tooltip" data-placement="bottom" data-original-title="點擊管理商品圖片" onclick="openproductimgmanager()" class="col-md-12 mosubproducts grow-sm"
style="background:url(' . $imageurls[1] . ');background-repeat:no-repeat;background-size:cover;border:none"></button><button data-toggle="tooltip" data-placement="bottom" data-original-title="點擊管理商品圖片" onclick="openproductimgmanager()" class="col-md-12 mosubproducts grow-sm"
style="background:url(' . $imageurls[2] . ');background-repeat:no-repeat;background-size:cover;border:none"></button></div></div>';
echo $output;
?>