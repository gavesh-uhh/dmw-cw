<?php
session_start();
include "../includes/utility.php";
$page_title = 'Fried Frenzy • Add Menu Item';
$page_css = 'admin/dashboard.css';
include '../includes/header.php';
include '../includes/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $per_portion = intval($_POST['per_portion']);
    $current_stock = intval($_POST['current_stock']);
    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required";
    }
    if (empty($description)) {
        $errors[] = "Description is required";
    }
    if ($price <= 0) {
        $errors[] = "Price must be greater than 0";
    }
    if ($per_portion <= 0) {
        $errors[] = "Portions must be greater than 0";
    }
    if ($current_stock < 0) {
        $errors[] = "Stock cannot be negative";
    }

    $file_tmp = $_FILES['image']['tmp_name'];
    $image_data = file_get_contents($file_tmp);
    if ($image_data === false || strlen($image_data) === 0) {
        $errors[] = "Failed to read image file or file is empty";
        error_log("Image read failed or empty. tmp_name: " . $file_tmp);
    } else {
        error_log("Image size: " . strlen($image_data) . " bytes");
    }

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO products (name, description, price, image_path, per_portion, current_stock, last_updated)
                VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
            ");
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $stmt->bind_param("ssssii", $name, $description, $price, $image_data, $per_portion, $current_stock);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Menu item added successfully!";
                header('Location: dashboard.php');
                exit();
            } else {
                $errors[] = "Failed to add menu item: " . $stmt->error;
            }
        } catch (Exception $e) {
            $errors[] = "An error occurred: " . $e->getMessage();
            error_log("Database error: " . $e->getMessage());
        }
    }
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
                <li class="active"><a href="add_item.php"><i class="bi bi-plus-circle"></i> Add Item</a></li>
                <li><a href="delete_item.php"><i class="bi bi-trash"></i> Delete Item</a></li>
                <li><a href="feedback.php"><i class="bi bi-chat-dots"></i> View Feedback</a></li>
                <li><a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </nav>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Add Menu Item</h1>
            </header>

            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <i class="bi bi-exclamation-circle"></i>
                    <?php echo implode("<br>", $errors); ?>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form method="POST" enctype="multipart/form-data" class="add-item-form">
                    <div class="form-group">
                        <label for="name">Item Name</label>
                        <input type="text" id="name" name="name" required
                            value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" required
                            rows="3"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">Price (Rs.)</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" required
                            value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="per_portion">Portions per Item</label>
                        <input type="number" id="per_portion" name="per_portion" min="1" required
                            value="<?php echo isset($_POST['per_portion']) ? htmlspecialchars($_POST['per_portion']) : '1'; ?>">
                    </div>

                    <div class="form-group">
                        <label for="current_stock">Current Stock</label>
                        <input type="number" id="current_stock" name="current_stock" min="0" required
                            value="<?php echo isset($_POST['current_stock']) ? htmlspecialchars($_POST['current_stock']) : '0'; ?>">
                    </div>

                    <div class="form-group">
                        <label for="image">Item Image</label>
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp" required>
                        <small>Max file size: 5MB. Allowed formats: JPG, PNG, WebP</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Add Item</button>
                        <a href="dashboard.php" class="btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>

</html>