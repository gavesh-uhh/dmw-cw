<?php
session_start();
require_once '../../includes/db_connect.php';
$page_title = 'Fried Frenzy • Register';
$page_css = 'register.css';
include '../../includes/header.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match";
    } else {

        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Email already exists";
        } else {

            $stmt = $conn->prepare("INSERT INTO users (email, password, display_name) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $email, $name);
            
            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;
                $_SESSION["user"] = ["user_id" => $user_id, "email" => $email, "name" => $name];
                $_SESSION["email"] = $email;
                
                header("Location: ../../index.php");
                exit();
            } else {
                $error_message = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<body>
    <div class="register-container">
        <form class="register-form" action="index.php" method="POST">
            <h1>Create Account</h1>

            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="register-btn">Sign Up</button>

            <p class="login-link">
                Already have an account? <a href="../login">Login</a>
            </p>
        </form>
    </div>
</body>
</html>