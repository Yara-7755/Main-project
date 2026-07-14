<?php
require_once __DIR__ . "/Database.php";

class Project {

    private $conn;

    public function __construct() {
        $db         = new Database();
        $this->conn = $db->getConn();
    }

    public function getAll() {
        $sql      = "SELECT * FROM projects ORDER BY created_at DESC";
        $result   = $this->conn->query($sql);
        $projects = array();

        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }

        return $projects;
    }

    public function add($title, $description, $tech, $link) {

        if ($title == "" || $description == "" || $tech == "" || $link == "") {
            return array("success" => false, "message" => "All fields are required");
        }

        $sql  = "INSERT INTO projects (title, description, tech, link) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $title, $description, $tech, $link);

        if ($stmt->execute()) {
            return array("success" => true, "message" => "Project added!");
        } else {
            return array("success" => false, "message" => "Failed to save");
        }
    }

    public function delete($id) {
        $sql  = "DELETE FROM projects WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return array("success" => true, "message" => "Project deleted!");
        } else {
            return array("success" => false, "message" => "Failed to delete");
        }
    }

    public function update($id, $title, $description, $tech, $link) {

        if ($title == "" || $description == "" || $tech == "" || $link == "") {
            return array("success" => false, "message" => "All fields are required");
        }

        $sql  = "UPDATE projects SET title=?, description=?, tech=?, link=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $title, $description, $tech, $link, $id);

        if ($stmt->execute()) {
            return array("success" => true, "message" => "Project updated!");
        } else {
            return array("success" => false, "message" => "Failed to update");
        }
    }
}
?>