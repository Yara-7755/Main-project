<?php
require_once __DIR__ . "/Database.php";

class Message {

    private $conn;

    public function __construct() {
        $db         = new Database();
        $this->conn = $db->getConn();
    }

    public function getAll() {
        $sql    = "SELECT * FROM messages ORDER BY sent_at DESC";
        $result = $this->conn->query($sql);
        $msgs   = array();

        while ($row = $result->fetch_assoc()) {
            $msgs[] = $row;
        }

        return $msgs;
    }

    public function save($first_name, $last_name, $email, $subject, $message) {

        if ($first_name == "" || $last_name == "" || $email == "" || $subject == "" || $message == "") {
            return array("success" => false, "message" => "All fields are required");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array("success" => false, "message" => "Invalid email address");
        }

        $sql  = "INSERT INTO messages (first_name, last_name, email, subject, message) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $subject, $message);

        if ($stmt->execute()) {
            return array("success" => true, "message" => "Message saved!");
        } else {
            return array("success" => false, "message" => "Failed to save");
        }
    }
}
?>