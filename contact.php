<?php
header("Content-Type: application/json");

$host = "localhost";
$user = "myuser";
$pass = "MyPass@123";
$db   = "mydb";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(array("success" => false, "message" => "Connection failed"));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(array("success" => false, "message" => "Invalid request"));
    exit;
}

if (isset($_POST["first_name"])) { $first_name = trim($_POST["first_name"]); } else { $first_name = ""; }
if (isset($_POST["last_name"]))  { $last_name  = trim($_POST["last_name"]);  } else { $last_name  = ""; }
if (isset($_POST["email"]))      { $email      = trim($_POST["email"]);      } else { $email      = ""; }
if (isset($_POST["subject"]))    { $subject    = trim($_POST["subject"]);    } else { $subject    = ""; }
if (isset($_POST["message"]))    { $message    = trim($_POST["message"]);    } else { $message    = ""; }

if ($first_name == "" || $last_name == "" || $email == "" || $subject == "" || $message == "") {
    echo json_encode(array("success" => false, "message" => "All fields are required"));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(array("success" => false, "message" => "Invalid email address"));
    exit;
}

$sql  = "INSERT INTO messages (first_name, last_name, email, subject, message) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $first_name, $last_name, $email, $subject, $message);

if ($stmt->execute()) {
    echo json_encode(array("success" => true, "message" => "Message saved!"));
} else {
    echo json_encode(array("success" => false, "message" => "Failed to save"));
}

$stmt->close();
$conn->close();
?>