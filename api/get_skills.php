<?php
header("Content-Type: application/json");
require_once "classes/Skill.php";

$skill  = new Skill();
$skills = $skill->getAll();

echo json_encode(array("success" => true, "data" => $skills));
?>