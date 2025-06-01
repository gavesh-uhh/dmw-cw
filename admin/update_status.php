<?php
session_start();
require_once '../includes/db_connect.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = ucfirst(strtolower($_POST['status'])); 
    
    $valid_statuses = ['Pending', 'Processing', 'Completed', 'Cancelled'];
    if (!in_array($status, $valid_statuses)) {
        $_SESSION['error'] = "Invalid status value";
        header('Location: dashboard.php');
        exit();
    }
    
    try {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
        $stmt->bind_param("si", $status, $order_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Order status updated successfully";
        } else {
            $_SESSION['error'] = "Failed to update order status";
        }
    } catch (Exception $e) {
        error_log("Error updating order status: " . $e->getMessage());
        $_SESSION['error'] = "An error occurred while updating the order status";
    }
} else {
    $_SESSION['error'] = "Invalid request";
}

header('Location: dashboard.php');
exit(); 