<?php
$lifetime=1800;
if (session_id() === "") {
    session_start([
        'cookie_lifetime' => $lifetime,
    ]);
}
setcookie(session_name(),session_id(),time()+$lifetime);
require_once('db.php');

$output = '';

echo $output;
?>