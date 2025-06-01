<?php
session_start();
$page_title = 'Fried Frenzy • Order Failed';
$page_css = 'checkout.css';
include '../includes/header.php';

$error_message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'An error occurred while processing your order.';
?>

<body>
    <header class="header">
        <img src="../assets/logo-header.png" id="nav_logo">
        <div class="header-right">
            <nav class="option-btns">
                <a href="../index.php"> <i style="margin-right: 0.5rem;" class="bi bi-house"></i> Home</a>
                <a href=""> <i style="margin-right: 0.5rem;" class="bi bi-person-lines-fill"></i>Contact Us</a>
            </nav>

            <nav class="nav-btns">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="../orders">My Orders</a>
                    <a class="icon" href="../auth/logout"><i class="bi bi-box-arrow-right"></i></a>
                <?php else: ?>
                    <a href="../auth/login">Login</a>
                    <a href="../auth/register">Sign up</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="checkout-container">
        <div class="error-message">
            <i class="bi bi-exclamation-circle-fill"></i>
            <h1>Order Failed</h1>
            <p><?php echo $error_message; ?></p>
            <div class="error-actions">
                <a href="<?php echo getRootPath('index.php'); ?>" class="btn">Return Home</a>
                <a href="javascript:history.back()" class="btn">Try Again</a>
            </div>
        </div>
    </div>

    <style>
        .error-message {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }

        .error-message i {
            font-size: 4rem;
            color: var(--text-red);
            margin-bottom: 1rem;
        }

        .error-message h1 {
            color: var(--text-red);
            margin-bottom: 1rem;
            font-family: 'Inter', sans-serif;
        }

        .error-message p {
            color: var(--text-gray);
            margin-bottom: 2rem;
            font-family: 'Inter', sans-serif;
            white-space: pre-line;
        }

        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .error-actions .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 20px;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s ease;
        }

        .error-actions .btn:first-child {
            background-color: var(--primary-red);
            color: white;
        }

        .error-actions .btn:last-child {
            background-color: var(--bg-transparent-red);
            color: var(--text-red);
        }

        .error-actions .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</body>
</html> 