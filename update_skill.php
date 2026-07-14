<?php
header("Content-Type: application/json");
require_once "classes/Skill.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(array("success" => false, "message" => "Invalid request"));
    exit;
}

$id   = isset($_POST["id"])   ? trim($_POST["id"])   : "";
$icon = isset($_POST["icon"]) ? trim($_POST["icon"]) : "";
$name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
$desc = isset($_POST["desc"]) ? trim($_POST["desc"]) : "";
$level= isset($_POST["level"])? trim($_POST["level"]): "";

if ($id == "") {
    echo json_encode(array("success" => false, "message" => "ID is required"));
    exit;
}

$skill  = new Skill();
$result = $skill->update($id, $icon, $name, $desc, $level);

echo json_encode($result);
?>