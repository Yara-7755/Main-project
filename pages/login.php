<?php
session_start();
require_once "classes/User.php";

if (isset($_SESSION["user"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $user   = new User();
    $result = $user->login($username, $password);

    if ($result["success"] == true) {
        $_SESSION["user"] = $result["username"];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = $result["message"];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="login-page">

<div class="login-box">
    <h1 class="login-title">Welcome Back 👋</h1>
    <p class="login-sub">Login to manage your portfolio</p>

    <?php if ($error != "") { ?>
        <div class="error-msg"><?php echo $error; ?></div>
    <?php } ?>

    <form method="POST">
        <input class="form-field" type="text"     name="username" placeholder="Username" />
        <input class="form-field" type="password" name="password" placeholder="Password" />
        <button class="btn-login" type="submit">Login →</button>
    </form>
</div>

</body>
</html>