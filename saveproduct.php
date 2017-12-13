<?php
require_once('userstatus.php');
$output = "";
if($loggedin==1) {
    if(isset($_REQUEST['productid']) && isset($_REQUEST['productname']) && isset($_REQUEST['productshortdesc']) && isset($_REQUEST['productprice']) && isset($_REQUEST['productquantities'])) {

    } else {
        $output = $failed;
    }
} else {
    $output = $failed;
}