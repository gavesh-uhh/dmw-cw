<?php
session_start();
require_once '../includes/db_connect.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to cancel orders']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$order_id = filter_input(INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT);
if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
    exit();
}

try {
    $conn->begin_transaction();
    $stmt = $conn->prepare("SELECT status FROM orders WHERE order_id = ? AND user_id = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $user_id = $_SESSION['user']['user_id'];
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception("Order not found or unauthorized");
    }
    $order = $result->fetch_assoc();
    if ($order['status'] !== 'Pending') {
        throw new Exception("Only pending orders can be cancelled");
    }
    $stmt = $conn->prepare("
        SELECT oi.product_id, oi.quantity 
        FROM order_item oi 
        WHERE oi.order_id = ?
    ");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    // Updating the stock back to its orignal price
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $items = $stmt->get_result();
    $update_stock = $conn->prepare("
        UPDATE products 
        SET current_stock = current_stock + ? 
        WHERE product_id = ?
    ");
    if (!$update_stock) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    while ($item = $items->fetch_assoc()) {
        $update_stock->bind_param("ii", $item['quantity'], $item['product_id']);
        if (!$update_stock->execute()) {
            throw new Exception("Failed to update stock: " . $update_stock->error);
        }
    }
    
    
    // Delete each order_item
    $stmt = $conn->prepare("DELETE FROM order_item WHERE order_id = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $order_id);
    if (!$stmt->execute()) {
        throw new Exception("Failed to delete order items: " . $stmt->error);
    }

    // Delete the actual order
    $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $order_id);
    if (!$stmt->execute()) {
        throw new Exception("Failed to delete order: " . $stmt->error);
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Order cancelled successfully']);

} catch (Exception $e) {
    if ($conn->connect_errno === 0) {
        $conn->rollback();
    }
    error_log("Order cancellation error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 