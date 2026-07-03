<?php
$host = "localhost";
$user = "myuser";
$pass = "MyPass@123";
$db   = "mydb";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(array("success" => false, "message" => "Connection failed")));
}
?>