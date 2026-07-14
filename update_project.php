<?php
header("Content-Type: application/json");
require_once "classes/Project.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(array("success" => false, "message" => "Invalid request"));
    exit;
}

$id    = isset($_POST["id"])    ? trim($_POST["id"])    : "";
$title = isset($_POST["title"]) ? trim($_POST["title"]) : "";
$desc  = isset($_POST["desc"])  ? trim($_POST["desc"])  : "";
$tech  = isset($_POST["tech"])  ? trim($_POST["tech"])  : "";
$link  = isset($_POST["link"])  ? trim($_POST["link"])  : "";

if ($id == "") {
    echo json_encode(array("success" => false, "message" => "ID is required"));
    exit;
}

$project = new Project();
$result  = $project->update($id, $title, $desc, $tech, $link);

echo json_encode($result);
?>