<?php
session_start();
require_once '../includes/db_connect.php';
header('Content-Type: application/json');

function sendResponse($success, $message, $data = []) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

if ($conn->connect_error) {
    sendResponse(false, "Database connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user'])) {
    sendResponse(false, 'Please login to place an order');
}

if (!isset($_POST['address']) || !isset($_POST['phone'])) {
    sendResponse(false, 'Please provide delivery details');
}

$cart = json_decode($_POST['cart'] ?? '{}', true);
if (empty($cart)) {
    sendResponse(false, 'Your cart is empty');
}

$user_id = $_SESSION['user']['user_id'];
$address = trim($_POST['address']);
$phone = trim($_POST['phone']);
$notes = trim($_POST['notes'] ?? '');

try {
    $conn->begin_transaction();

    $product_ids = array_keys($cart);
    $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
    
    $stmt = $conn->prepare("
        SELECT product_id, name, price, current_stock 
        FROM products 
        WHERE product_id IN ($placeholders)
    ");
    
    if (!$stmt) {
        throw new Exception("Failed to prepare product query: " . $conn->error);
    }
    
    $stmt->bind_param(str_repeat('i', count($product_ids)), ...$product_ids);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    $total_price = 0;
    $out_of_stock = [];
    
    while ($product = $result->fetch_assoc()) {
        $quantity = $cart[$product['product_id']];
        
        if ($product['current_stock'] < $quantity) {
            $out_of_stock[] = [
                'name' => $product['name'],
                'available' => $product['current_stock'],
                'requested' => $quantity
            ];
        }
        
        $products[$product['product_id']] = $product;
        $total_price += $product['price'] * $quantity;
    }
    
    if (!empty($out_of_stock)) {
        $message = "Some items are out of stock:\n";
        foreach ($out_of_stock as $item) {
            $message .= "- {$item['name']}: Available {$item['available']}, Requested {$item['requested']}\n";
        }
        throw new Exception($message);
    }
    
    $stmt = $conn->prepare("
        INSERT INTO orders (
            user_id, 
            total_price,
            status,
            phone_number,
            delivery_address,
            delivery_notes
        ) VALUES (?, ?, 'Pending', ?, ?, ?)
    ");
    
    if (!$stmt) {
        throw new Exception("Failed to prepare order query: " . $conn->error);
    }
    
    $stmt->bind_param("idsss", $user_id, $total_price, $phone, $address, $notes);
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute order query: " . $stmt->error);
    }
    
    $order_id = $conn->insert_id;
    
    $stmt = $conn->prepare("
        INSERT INTO order_item (
            order_id, 
            product_id, 
            quantity, 
            price_at_order_time
        ) VALUES (?, ?, ?, ?)
    ");
    
    if (!$stmt) {
        throw new Exception("Failed to prepare order items query: " . $conn->error);
    }
    
    $stock_stmt = $conn->prepare("
        UPDATE products 
        SET current_stock = current_stock - ? 
        WHERE product_id = ?
    ");
    
    if (!$stock_stmt) {
        throw new Exception("Failed to prepare stock update query: " . $conn->error);
    }
    
    foreach ($cart as $product_id => $quantity) {
        $product = $products[$product_id];
        
        $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $product['price']);
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert order item: " . $stmt->error);
        }
        
        $stock_stmt->bind_param("ii", $quantity, $product_id);
        if (!$stock_stmt->execute()) {
            throw new Exception("Failed to update stock: " . $stock_stmt->error);
        }
    }
    
    $conn->commit();
    sendResponse(true, 'Order placed successfully', ['order_id' => $order_id]);
    
} catch (Exception $e) {
    if ($conn->connect_errno === 0) {
        $conn->rollback();
    }
    error_log("Order processing error: " . $e->getMessage());
    sendResponse(false, $e->getMessage());
} 