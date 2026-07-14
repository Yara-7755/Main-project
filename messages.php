<?php
session_start();
require_once "classes/Message.php";

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$msg      = new Message();
$messages = $msg->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dash-page">
<div class="dash-main">

    <div class="dash-topbar">
        <h1 class="dash-title">📬 <span>Messages</span></h1>
        <a href="dashboard.php" class="btn-logout">← Back</a>
    </div>

    <div class="messages-table-wrap">
        <table class="messages-table">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            <?php if (count($messages) == 0) { ?>
                <tr>
                    <td colspan="6" style="text-align:center; color:#9b9b9b;">
                        No messages yet.
                    </td>
                </tr>
            <?php } else { ?>
                <?php foreach ($messages as $row) { ?>
                    <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo $row["first_name"] . " " . $row["last_name"]; ?></td>
                        <td><?php echo $row["email"]; ?></td>
                        <td><?php echo $row["subject"]; ?></td>
                        <td><?php echo $row["message"]; ?></td>
                        <td><?php echo $row["sent_at"]; ?></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>