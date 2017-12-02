<?php
require_once('userstatus.php');
if($loggedin==1) {
    $query = "insert into moproducts (Name,Descriptions,MerchantId,CreatedDate,CreatedBy,Edited) VALUES ('新商品','商品介紹'," . $_SESSION['momerchantid'] . ",NOW()," . $_SESSION['mouserid'] . ",0)";
    $mysqli->query($query);
    header('Location: product.php?id=' . $mysqli->insert_id);
} else {
    header('Location: ' . $_SESSION['mocurrenturl']);
}