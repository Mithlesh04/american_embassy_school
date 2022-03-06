<?php

// do not print warnings
error_reporting(E_ERROR | E_PARSE);

// Database connection
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "aes";

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_error) {
    die(json_encode(array("status" => 505, "message" => "Error connecting to database")));
}
