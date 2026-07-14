<?php
require_once __DIR__ . "/Database.php";

class User {

    private $conn;

    public function __construct() {
        $db         = new Database();
        $this->conn = $db->getConn();
    }

    public function login($username, $password) {

        if ($username == "" || $password == "") {
            return array("success" => false, "message" => "Please fill in all fields.");
        }

        $sql  = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row["password"])) {
                return array("success" => true, "username" => $row["username"]);
            } else {
                return array("success" => false, "message" => "Wrong password.");
            }
        } else {
            return array("success" => false, "message" => "User not found.");
        }
    }
}
?>