<?php
$lifetime=1800;
if (session_id() === "") {
    session_start([
        'cookie_lifetime' => $lifetime,
    ]);
}
setcookie(session_name(),session_id(),time()+$lifetime);
$output = "";
$output = "failed";
if(isset($_POST['logout'])) {
    if($_POST['logout']=='true') {
        unset($_SESSION['mousername']);
        unset($_SESSION['mouserid']);
        unset($_SESSION['mousertype']);
        unset($_SESSION['mocurrenturl']);
        $output = "success";
    }
}
echo $output;