<?php
require_once('userstatus.php');
$output = "";
if($loggedin==1) {
    if(isset($_REQUEST['productid']) && isset($_REQUEST['productlongdesc'])) {
        $query = "UPDATE moproducts SET ProductInfo='" . $mysqli->real_escape_string($_REQUEST['productlongdesc']) . "',ModifiedDate=NOW(),ModifiedBy=" . $_SESSION['mouserid'] . " WHERE Id=" . $_REQUEST['productid'];
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