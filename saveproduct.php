<?php
require_once('userstatus.php');
$output = "";
if($loggedin==1) {
    if(isset($_REQUEST['productid']) && isset($_REQUEST['productname']) && isset($_REQUEST['productshortdesc']) && isset($_REQUEST['productprice']) && isset($_REQUEST['productlongdesc'])) {
        $query = "UPDATE moproducts SET Name='" . $mysqli->real_escape_string($_REQUEST['productname']) . "',Descriptions='" . $mysqli->real_escape_string($_REQUEST['productshortdesc']) . "',ProductInfo='" . $mysqli->real_escape_string($_REQUEST['productlongdesc']) . "',ModifiedDate=NOW(),ModifiedBy=" . $_SESSION['mouserid'] . ",Edited=1,Price=" . $mysqli->real_escape_string($_REQUEST['productprice']) . " WHERE Id=" . $_REQUEST['productid'];
        if ($mysqli->query($query) === TRUE) {
            $output = "success";
        } else {
            $output = "failed";
        }
    } else {
        $output = "failed";
    }
} else {
    $output = "failed";
}
echo $output;