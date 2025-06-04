<?php
session_start();
include "../includes/utility.php";
$page_title = 'Fried Frenzy • View Feedback';
$page_css = 'admin/dashboard.css';
include '../includes/header.php';
include '../includes/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php');
    exit();
}

$stmt = $conn->prepare("
    SELECT * FROM feedback 
    ORDER BY created_at DESC
");

if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    $error = "Failed to load feedback entries";
} else {
    $stmt->execute();
    $result = $stmt->get_result();
    $feedback_entries = $result->fetch_all(MYSQLI_ASSOC);
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
                <li><a href="delete_item.php"><i class="bi bi-trash"></i> Delete Item</a></li>
                <li class="active"><a href="feedback.php"><i class="bi bi-chat-dots"></i> View Feedback</a></li>
                <li><a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </nav>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Customer Feedback</h1>
            </header>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <i class="bi bi-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="feedback-list">
                <?php if (empty($feedback_entries)): ?>
                    <p class="no-feedback">No feedback entries found.</p>
                <?php else: ?>
                    <?php foreach ($feedback_entries as $feedback): ?>
                        <div class="feedback-card">
                            <div class="feedback-header">
                                <div class="feedback-info">

                                    <span class="feedback-date">
                                        <i class="bi bi-calendar"></i>
                                        <?php echo date('F j, Y g:i A', strtotime($feedback['created_at'])); ?>
                                    </span>
                                </div>
                                <span class="feedback-id">FID#<?php echo $feedback['feedback_id']; ?></span>
                            </div>
                            <div class="feedback-content"><?php echo trim(nl2br(htmlspecialchars($feedback['content']))); ?>
                            </div>
                            <div class="feedback-footer">
                                <span class="feedback-email">
                                    Reviwed By ~ <i class="bi bi-envelope"></i>
                                    <?php echo htmlspecialchars($feedback['email']); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>