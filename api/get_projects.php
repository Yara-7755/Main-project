<?php
header("Content-Type: application/json");
require_once "classes/Project.php";

$project  = new Project();
$projects = $project->getAll();

echo json_encode(array("success" => true, "data" => $projects));
?>