<?php
$lifetime=1800;
if (session_id() === "") {
    session_start([
        'cookie_lifetime' => $lifetime,
    ]);
}
setcookie(session_name(),session_id(),time()+$lifetime);
require_once('db.php');

$username = $_POST['username'];
$password = hash('sha256',$_POST['password']);
$output = "";

$query = "select * from mousers where (Username='" . $username . "' or Email='" . $username . "') and Pwd='" . $password . "'";
$result = $mysqli->query($query);

if(($result) && ($result->num_rows !== 0)) {
    $row = $result->fetch_assoc();
    $_SESSION['mousername'] = $row['LastName'] . " " . $row['FirstName'];
    $_SESSION['mouserid'] = $row['Id'];
    $_SESSION['mousertype'] = $row['UserType'];
    $query = "UPDATE mousers SET SessionId='" . session_id() . "' WHERE Id=" . $row['Id'];
    $mysqli->query($query);
    $output = "success";
} else {
    $output = "failed";
}

echo $output;