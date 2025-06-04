<?php
session_start();
include "../includes/utility.php";
$page_title = 'Fried Frenzy • Admin Dashboard';
$page_css = 'admin/dashboard.css';
include '../includes/header.php';
include '../includes/db_connect.php';
include '../includes/get_orders.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order'])) {
    $order_id = filter_input(INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT);
    
    if ($order_id) {
        try {
            $stmt = $conn->prepare("DELETE FROM order_item WHERE order_id = ?");
            if (!$stmt) {
                throw new Exception("[SQL:Error] Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("i", $order_id);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("i", $order_id);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Order successfully deleted!";
                header('Location: dashboard.php');
                exit();
            } else {
                throw new Exception("Failed to delete order: " . $stmt->error);
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            error_log("Database error: " . $e->getMessage());
        }
    }
}

$orders = getOrders();
if ($orders === false) {
    $error_message = "Failed to fetch orders. Please try again later.";
    $orders = [];
}

try {
    $stats = [
        'total_orders' => 0,
        'pending_orders' => 0,
        'total_products' => 0,
        'total_revenue' => 0
    ];

    $queries = [
        'total_orders' => "SELECT COUNT(*) as total FROM orders",
        'pending_orders' => "SELECT COUNT(*) as pending FROM orders WHERE status = 'pending'",
        'total_products' => "SELECT COUNT(*) as total FROM products",
        'total_revenue' => "SELECT SUM(total_price) as revenue FROM orders WHERE status != 'cancelled'"
    ];

    foreach ($queries as $key => $query) {
        $stmt = $conn->prepare($query);
        if (!$stmt->execute()) {
            throw new Exception("Failed to get $key");
        }
        $result = $stmt->get_result()->fetch_assoc();
        $stats[$key] = $result[array_key_first($result)] ?? 0;
    }

    extract($stats);

} catch (Exception $e) {
    $error_message = "Failed to load dashboard statistics. Please try again later.";
    extract(array_fill_keys(['total_orders', 'pending_orders', 'total_products', 'total_revenue'], 0));
}
?>

<body>
    <div class="admin-dashboard">
        <nav class="admin-nav">
            <div class="admin-nav-header">
                <a href="<?php echo getRootPath('index.php'); ?>">
                    <img src="<?php echo getRootPath('assets/logo-header.png'); ?>" alt="Fried Frenzy" class="admin-logo">
                </a>
                <h2>Admin Panel</h2>
            </div>
            <ul class="admin-nav-links">
                <li class="active"><a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="add_item.php"><i class="bi bi-plus-circle"></i> Add Item</a></li>
                <li><a href="delete_item.php"><i class="bi bi-trash"></i> Delete Item</a></li>
                <li><a href="feedback.php"><i class="bi bi-chat-dots"></i> View Feedback</a></li>
                <li><a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </nav>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Sales</h1>
                <div class="header-actions">
                    <a href="add_item.php" class="btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Menu Item
                    </a>
                </div>
            </header>

            <?php if (isset($error_message)): ?>
                <div class="error-message">
                    <i class="bi bi-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="success-message">
                    <i class="bi bi-check-circle"></i>
                    <?php 
                    echo htmlspecialchars($_SESSION['success']);
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message">
                    <i class="bi bi-exclamation-circle"></i>
                    <?php 
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Orders</h3>
                        <p><?php echo number_format($total_orders); ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Pending Orders</h3>
                        <p><?php echo number_format($pending_orders); ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-box"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Products</h3>
                        <p><?php echo number_format($total_products); ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <span>රු</span>
                    </div>
                    <div class="stat-info">
                        <h3>Total Revenue</h3>
                        <p>Rs. <?php echo number_format($total_revenue, 2); ?></p>
                    </div>
                </div>
            </div>

            <div class="recent-orders" id="orders-container">
                <h2>Recent Orders</h2>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="orders-table-body">
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td>
                                            <form method="POST" action="update_status.php" class="status-form" onsubmit="this.classList.add('loading')">
                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                <select name="status" onchange="this.form.submit()" class="status-select">
                                                    <option value="pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="processing" <?php echo $order['status'] === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                                    <option value="completed" <?php echo $order['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                    <option value="cancelled" <?php echo $order['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                                        <td><?php echo htmlspecialchars($order['user_id']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                        <td>Rs. <?php echo number_format($order['total_price'], 2); ?></td>
                                        <td>
                                            <form method="POST" class="delete-form" onsubmit="return confirmDelete(<?php echo $order['id']; ?>)">
                                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                <button type="submit" name="delete_order" class="btn-danger">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No recent orders found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="<?php echo getRootPath('js/admin/dashboard.js'); ?>"></script>
    <script>
        function confirmDelete(orderId) {
            return confirm(`Are you sure you want to delete order #${orderId}? This action cannot be undone.`);
        }
    </script>
</body>

</html>