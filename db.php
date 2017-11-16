<?php
$servername = "localhost";
$username = "root";
$password = "Abc987654";
$dbname = "moshop";
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}