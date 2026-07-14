<?php
class Database {

    private $host = "localhost";
    private $user = "myuser";
    private $pass = "MyPass@123";
    private $db   = "mydb";
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->db
        );

        if ($this->conn->connect_error) {
            die(json_encode(array(
                "success" => false,
                "message" => "Connection failed"
            )));
        }
    }

    public function getConn() {
        return $this->conn;
    }

    public function close() {
        $this->conn->close();
    }
}
?>