<?php
$lifetime=1800;
if (session_id() === "") {
    session_start([
        'cookie_lifetime' => $lifetime,
    ]);
}
setcookie(session_name(),session_id(),time()+$lifetime);
require_once('db.php');

$loggedin = 0;
if(isset($_SESSION['mouserid'])) {
    $query = "select SessionId from mousers where Id=" . $_SESSION['mouserid'];
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();
    if($row['SessionId']==session_id()) {
        $loggedin = 1;
    } else {
        unset($_SESSION['mousername']);
        unset($_SESSION['mouserid']);
        unset($_SESSION['mousertype']);
        unset($_SESSION['mocurrenturl']);
        unset($_SESSION['momerchantid']);
    }
}