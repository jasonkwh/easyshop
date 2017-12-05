<?php
require_once('db.php');

$username = $mysqli->real_escape_string($_REQUEST['username']);
$firstname = $mysqli->real_escape_string($_REQUEST['firstname']);
$lastname = $mysqli->real_escape_string($_REQUEST['lastname']);
$pwd = hash('sha256',$mysqli->real_escape_string($_REQUEST['pwd']));
$emailaddress = $mysqli->real_escape_string($_REQUEST['emailaddress']);

$query = "insert into mousers (Username,FirstName,LastName,Pwd,Email) VALUES ('" . $username . "','" . $firstname . "','" . $lastname . "','" . $pwd . "','" . $emailaddress . "')";
if ($mysqli->query($query) === TRUE) {
    echo "success";
} else {
    echo "failed";
}