<?php
require_once __DIR__ . "/Database.php";

class Skill {

    private $conn;

    public function __construct() {
        $db         = new Database();
        $this->conn = $db->getConn();
    }

    public function getAll() {
        $sql    = "SELECT * FROM skills";
        $result = $this->conn->query($sql);
        $skills = array();

        while ($row = $result->fetch_assoc()) {
            $skills[] = $row;
        }

        return $skills;
    }

    public function add($icon, $name, $desc, $level) {

        if ($icon == "" || $name == "" || $desc == "" || $level == "") {
            return array("success" => false, "message" => "All fields are required");
        }

        if ($level < 0 || $level > 100) {
            return array("success" => false, "message" => "Level must be between 0 and 100");
        }

        $sql  = "INSERT INTO skills (icon, name, description, level) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssi", $icon, $name, $desc, $level);

        if ($stmt->execute()) {
            return array("success" => true, "message" => "Skill added!");
        } else {
            return array("success" => false, "message" => "Failed to save");
        }
    }

    public function delete($id) {
        $sql  = "DELETE FROM skills WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return array("success" => true, "message" => "Skill deleted!");
        } else {
            return array("success" => false, "message" => "Failed to delete");
        }
    }

    public function update($id, $icon, $name, $desc, $level) {

        if ($icon == "" || $name == "" || $desc == "" || $level == "") {
            return array("success" => false, "message" => "All fields are required");
        }

        $sql  = "UPDATE skills SET icon=?, name=?, description=?, level=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssii", $icon, $name, $desc, $level, $id);

        if ($stmt->execute()) {
            return array("success" => true, "message" => "Skill updated!");
        } else {
            return array("success" => false, "message" => "Failed to update");
        }
    }
    public function getById($id) {
        $sql  = "SELECT * FROM skills WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>