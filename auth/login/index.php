<?php
session_start();
$page_title = 'Fried Frenzy • Login';
$page_css = 'login.css';
include '../../includes/header.php';

$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if ($email == "123@gmail.com") {
        $error_message = "Invalid!";
    }
}
?>

<body>
    <div class="login-container">
        <form class="login-form" action="index.php" method="POST">
            <h1>Login</h1>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="login-btn">Login</button>

            <p class="register-link">
                Don't have an account? <a href="../register">Sign up</a>
            </p>
        </form>
    </div>
</body>
</html>