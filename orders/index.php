<?php
session_start();
$page_title = 'Fried Frenzy • My Orders';
$page_css = 'orders.css';
include '../includes/header.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login');
    exit();
}

require_once '../includes/db_connect.php';

$user_id = $_SESSION['user']['user_id'];

// sql injections lol
$stmt = $conn->prepare("
    SELECT o.*, 
           COUNT(oi.order_item_id) as total_items,
           GROUP_CONCAT(CONCAT(p.name, ' (', oi.quantity, ')') SEPARATOR ', ') as items
    FROM orders o
    LEFT JOIN order_item oi ON o.order_id = oi.order_id
    LEFT JOIN products p ON oi.product_id = p.product_id
    WHERE o.user_id = ?
    GROUP BY o.order_id
    ORDER BY o.order_date DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<body>
    <script src="../js/orders.js"></script>
    <header class="header">
        <img src="../assets/logo-header.png" id="nav_logo">
        <div class="header-right">
            <nav class="option-btns">
                <a href="../index.php"><i class="bi bi-house"></i> Home</a>
                <a href="<?php echo getRootPath('contact'); ?>"><i class="bi bi-person-lines-fill"></i>Contact Us</a>
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

    <div class="orders-container">
        <h1>My Orders</h1>

        <?php if ($result->num_rows === 0): ?>
            <div class="no-orders">
                <i class="bi bi-bag-x"></i>
                <p>You haven't placed any orders yet.</p>
                <a href="<?php echo getRootPath('products'); ?>" class="btn">Start Shopping</a>
            </div>
        <?php else: ?>
            <div class="orders-list">
                <?php while ($order = $result->fetch_assoc()): ?>
                    <div class="order-card status-<?php echo strtolower($order['status']); ?>">
                        <div class="order-header">
                            <div class="order-info">
                                <h3>Order #<?php echo $order['order_id']; ?></h3>
                                <span class="order-date">
                                    <i class="bi bi-calendar"></i>
                                    <?php echo date('F j, Y', strtotime($order['order_date'])); ?>
                                </span>
                            </div>
                            <div class="order-status">
                                <span class="status-badge"><?php echo $order['status']; ?></span>
                            </div>
                        </div>

                        <div class="order-details">
                            <div class="detail-row">
                                <span class="label">Items:</span>
                                <span class="value"><?php echo $order['items']; ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Total Items:</span>
                                <span class="value"><?php echo $order['total_items']; ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Total Amount:</span>
                                <span class="value">Rs. <?php echo number_format($order['total_price'], 2); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Delivery Address:</span>
                                <span class="value"><?php echo htmlspecialchars($order['delivery_address']); ?></span>
                            </div>
                            <?php if (!empty($order['delivery_notes'])): ?>
                                <div class="detail-row">
                                    <span class="label">Notes:</span>
                                    <span class="value"><?php echo htmlspecialchars($order['delivery_notes']); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($order['status'] === 'Pending'): ?>
                                <div class="order-actions">
                                    <button class="cancel-btn" onclick="cancelOrder(<?php echo $order['order_id']; ?>)">
                                        <i class="bi bi-x-circle"></i>
                                        Cancel Order
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>