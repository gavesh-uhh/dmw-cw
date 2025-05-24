<?php
session_start();
require_once '../../includes/db_connect.php';
$page_title = 'Fried Frenzy • Login';
$page_css = 'login.css';
include '../../includes/header.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST["email"];
    $password = $_POST["password"];


    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($password === $user["password"]) {
            $_SESSION["user"] = $user;
            $_SESSION["email"] = $user["email"];
            header("Location: ../../index.php");
            exit();
        }
    } else {
        $error_message = "Invalid email or password";
    }
}
?>
<body>
    <div class="login-container">
        <form class="login-form" action="index.php" method="POST">
            <h1>Login</h1>

            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

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