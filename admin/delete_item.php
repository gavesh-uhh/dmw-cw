<?php
session_start();
include "../includes/utility.php";
$page_title = 'Fried Frenzy • Delete Menu Item';
$page_css = getRootPath('css/admin/dashboard.css');
include '../includes/header.php';
include '../includes/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php');
    exit();
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $item_id = filter_input(INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT);
    
    if ($item_id) {
        try {

            $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $stmt->bind_param("i", $item_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $item = $result->fetch_assoc();

            if ($item) {
                $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
                if (!$stmt) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                
                $stmt->bind_param("i", $item_id);
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Item successfully deleted!";
                    header('Location: delete_item.php');
                    exit();
                } else {
                    if ($stmt->errno === 1451) {
                        throw new Exception("Cannot delete this product (" . $item["name"] .  ") because it is referenced in existing orders.");
                    }
                    throw new Exception("Failed to delete item: " . $stmt->error);
                }
            } else {
                $error = "Item not found!";
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            error_log("Database error: " . $e->getMessage());
        }
    }
}

try {
    $result = $conn->query("SELECT * FROM products ORDER BY name");
    if (!$result) {
        throw new Exception("Failed to fetch items: " . $conn->error);
    }
    $items = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    $error = "Error fetching items: " . $e->getMessage();
    error_log("Database error: " . $e->getMessage());
    $items = [];
}
?>

<body>
    <div class="admin-dashboard">
        <nav class="admin-nav">
            <div class="admin-nav-header">
                <img src="../assets/logo-header.png" alt="Fried Frenzy" class="admin-logo">
                <h2>Admin Panel</h2>
            </div>
            <ul class="admin-nav-links">
                <li><a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li><a href="add_item.php"><i class="bi bi-plus-circle"></i> Add Item</a></li>
                <li class="active"><a href="delete_item.php"><i class="bi bi-trash"></i> Delete Item</a></li>
                <li><a href="feedback.php"><i class="bi bi-chat-dots"></i> View Feedback</a></li>
                <li><a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </nav>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Delete Items</h1>
            </header>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="success-message">
                    <i class="bi bi-check-circle"></i>
                    <?php 
                    echo htmlspecialchars($_SESSION['success']);
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="error-message">
                    <i class="bi bi-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="items-list">
                <?php if (empty($items)): ?>
                    <p>No items found.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['product_id']); ?></td>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td>Rs. <?php echo htmlspecialchars(number_format($item['price'], 2)); ?></td>
                                        <td><?php echo htmlspecialchars($item['current_stock']); ?></td>
                                        <td>
                                            <form method="POST" onsubmit="return confirmDelete(<?php echo $item['product_id']; ?>, '<?php echo htmlspecialchars($item['name']); ?>')">
                                                <input type="hidden" name="item_id" value="<?php echo $item['product_id']; ?>">
                                                <button type="submit" name="delete_item" class="btn-danger">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function confirmDelete(itemId, itemName) {
            return confirm(`Are you sure you want to delete "${itemName}"? This action cannot be undone.`);
        }
    </script>
</body>
</html> 