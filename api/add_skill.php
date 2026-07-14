<?php
header("Content-Type: application/json");
require_once "classes/Skill.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(array("success" => false, "message" => "Invalid request"));
    exit;
}

$icon  = isset($_POST["icon"])  ? trim($_POST["icon"])  : "";
$name  = isset($_POST["name"])  ? trim($_POST["name"])  : "";
$desc  = isset($_POST["desc"])  ? trim($_POST["desc"])  : "";
$level = isset($_POST["level"]) ? trim($_POST["level"]) : "";

$skill  = new Skill();
$result = $skill->add($icon, $name, $desc, $level);

echo json_encode($result);
?>