<?php
header("Content-Type: application/json");
require_once "classes/Message.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(array("success" => false, "message" => "Invalid request"));
    exit;
}

$first_name = isset($_POST["first_name"]) ? trim($_POST["first_name"]) : "";
$last_name  = isset($_POST["last_name"])  ? trim($_POST["last_name"])  : "";
$email      = isset($_POST["email"])      ? trim($_POST["email"])      : "";
$subject    = isset($_POST["subject"])    ? trim($_POST["subject"])    : "";
$message    = isset($_POST["message"])    ? trim($_POST["message"])    : "";

$msg    = new Message();
$result = $msg->save($first_name, $last_name, $email, $subject, $message);

echo json_encode($result);
?>