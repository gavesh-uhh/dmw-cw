<?php
session_start();
$page_title = 'Fried Frenzy • Checkout';
$page_css = 'checkout.css';
include '../includes/header.php';
include '../includes/get_products.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login');
    exit();
}
?>

<body>
    <header class="header">
        <img src="../assets/logo-header.png" id="nav_logo">
        <div class="header-right">
            <nav class="option-btns">
                <a href="<?php echo getRootPath(path: 'index.php'); ?>"><i style="margin-right: 0.5rem;"class="bi bi-house"></i> Home</a>
                <a href="<?php echo getRootPath(path: 'contact'); ?>"><i style="margin-right: 0.5rem;"class="bi bi-person-lines-fill"></i> Contact Us</a>
            </nav>

            <nav class="nav-btns">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="<?php echo getRootPath(path: 'orders'); ?>">My Orders</a>
                    <a class="icon" href="auth/logout"><i class="bi bi-box-arrow-right"></i></a>
                <?php else: ?>
                    <a href="auth/login">Login</a>
                    <a href="auth/register">Sign up</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <div class="checkout-container">
        <h1 class="font-fancy title" style="color: var(--text-red);">Checkout</h1>

        <div class="checkout-content">
            <div class="order-summary">
                <h2>Order Summary</h2>
                <div id="cart-items">
                    <!-- Handled by JS -@Gavesh -->
                </div>
                <div class="order-total">
                    <h3>Total: Rs. <span id="cart-total">0.00</span></h3>
                </div>
            </div>

            <div class="checkout-form">
                <h2>Delivery Details</h2>
                <form id="checkoutForm" action="process_order.php" method="POST">
                    <div class="form-group">
                        <label for="address">Delivery Address</label>
                        <textarea id="address" name="address" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="notes">Order Notes (Optional)</label>
                        <textarea id="notes" name="notes"></textarea>
                    </div>
                    <button type="submit" class="checkout-btn" id="place-order-btn" disabled>
                        Place Order
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        <?php
        $products = getProductsRaw();
        if ($products === false) {
            error_log('Error: Failed to fetch products from database');
            $products = [];
        }
        ?>
        const products = <?php echo json_encode((object) $products, JSON_FORCE_OBJECT); ?>;
        window.products = products;
    </script>

    <script src="../js/checkout.js"></script>
</body>

</html>