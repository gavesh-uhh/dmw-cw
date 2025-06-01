<?php
session_start();
$page_title = 'Fried Frenzy • Admin Login';
$page_css = 'admin.css';
require_once '../includes/db_connect.php';
include '../includes/header.php';


if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header('Location: dashboard.php');
    exit();
}

$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT admin_id, password FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if ($password === $admin["password"]) {
            $_SESSION['admin'] = true;
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_username'] = $username;
            header('Location: dashboard.php');
            exit();
        }
    }
    
    $error_message = 'Invalid username or password';
}
?>

<body>
    <div class="admin-login-container">
        <form class="admin-login-form" action="index.php" method="POST">
            <div class="login-header">
                <img style="filter: invert(); opacity: 60%;" src="../assets/logo-header.png" alt="Fried Frenzy" class="login-logo">
                <h1>Admin Login</h1>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <i class="bi bi-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="username">
                    <i class="bi bi-person"></i>
                    Username
                </label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       required 
                       autocomplete="username"
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                       placeholder="Enter your username">
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="bi bi-lock"></i>
                    Password
                </label>
                <div class="password-input">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           autocomplete="current-password"
                           placeholder="Enter your password">
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="login-btn">
                <i class="bi bi-box-arrow-in-right"></i>
                Login
            </button>

            <a href="<?php echo getRootPath('index.php'); ?>" class="back-link">
                <i class="bi bi-arrow-left"></i> Back to Home
            </a>
        </form>
    </div>

    <style>
        .admin-login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-light-red));
            padding: 1rem;
        }

        .admin-login-form {
            background: var(--white);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo {
            width: 150px;
            margin-bottom: 1rem;
        }

        .login-header h1 {
            color: var(--primary-red);
            font-size: 1.5rem;
            margin: 0;
        }

        .error-message {
            background-color: var(--bg-transparent-red);
            color: var(--primary-red);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            color: var(--text-red);
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--primary-light-red);
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 3px var(--bg-transparent-red);
        }

        .password-input {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-red);
            cursor: pointer;
            padding: 0;
        }

        .login-btn {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary-red);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .login-btn:hover {
            background-color: var(--primary-light-red);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-red);
            text-decoration: none;
            font-weight: 500;
        }

        .back-link:hover {
            color: var(--primary-orange);
        }

        .back-link i {
            margin-right: 0.5rem;
        }
    </style>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.querySelector('.toggle-password i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.classList.remove('bi-eye');
                toggleButton.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleButton.classList.remove('bi-eye-slash');
                toggleButton.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>