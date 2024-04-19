<?php

$ini_array = parse_ini_file("./config.ini", true);

$servername = $ini_array['SQL_HOST'];
$username = $ini_array['SQL_USER'];
$password = $ini_array['SQL_PASS'];
$dbname = $ini_array['SQL_DB'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
