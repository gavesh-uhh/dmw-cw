<?php
session_start();
$page_title = 'Fried Frenzy • Order Confirmed';
$page_css = 'checkout.css';
include '../includes/header.php';

if (!isset($_GET['order_id'])) {
    header('Location: ../index.php');
    exit();
}

$order_id = $_GET['order_id'];
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
                    <a href="orders">My Orders</a>
                    <a class="icon" href="auth/logout"><i class="bi bi-box-arrow-right"></i></a>
                <?php else: ?>
                    <a href="auth/login">Login</a>
                    <a href="auth/register">Sign up</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="checkout-container">
        <div class="success-message">
            <i class="bi bi-check-circle-fill"></i>
            <h1>Order Confirmed!</h1>
            <p>Thank you for your order. Your order number is: #<?php echo $order_id; ?></p>
            <p>We'll start preparing your order right away.</p>
            <div class="success-actions">
                <a href="../index.php" class="btn">Continue Shopping</a>
                <a href="<?php echo getRootPath(path: 'products'); ?>" class="btn">Browse</a>
            </div>
        </div>
    </div>

    <style>
        .success-message {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }

        .success-message i {
            font-size: 4rem;
            color: #2ecc71;
            margin-bottom: 1rem;
        }

        .success-message h1 {
            color: var(--text-red);
            margin-bottom: 1rem;
        }

        .success-message p {
            color: #666;
            margin-bottom: 0.5rem;
        }

        .success-actions {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            background: var(--primary-dark-red);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.2s ease;
        }

        .btn:hover {
            background: var(--primary-light-red);
        }

        @media screen and (max-width: 480px) {
            .success-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</body>
</html> 