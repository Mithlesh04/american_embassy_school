<?php

// do not print warnings
error_reporting(E_ERROR | E_PARSE);

// Database connection
$db_host = "13.234.112.173"; //change this to your database host name
$db_user = "root"; //change this to your database user name
$db_pass = "root123"; //change this to your database password
$db_name = "aes";

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_error) {
    die(json_encode(array("status" => 505, "message" =>
        $mysqli -> connect_error
    ."Error connecting to database")));
}
