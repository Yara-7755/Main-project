<?php
header("Content-Type: application/json");
require_once "classes/Project.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(array("success" => false, "message" => "Invalid request"));
    exit;
}

$id = isset($_POST["id"]) ? trim($_POST["id"]) : "";

if ($id == "") {
    echo json_encode(array("success" => false, "message" => "ID is required"));
    exit;
}

$project = new Project();
$result  = $project->delete($id);

echo json_encode($result);
?>