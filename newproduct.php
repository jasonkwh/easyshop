<?php
require_once('userstatus.php');
if($loggedin==1) {

} else {
    header('Location: ' . $_SESSION['mocurrenturl']);
}