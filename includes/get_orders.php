<?php
require_once 'db_connect.php';

function getOrders($limit = null) {
    global $conn;
    
    $sql = "SELECT * FROM orders";
    if ($limit !== null) {
        $sql .= " LIMIT " . (int)$limit;
    }
    
    error_log("Executing SQL query: " . $sql);
    $result = $conn->query($sql);
    
    if (!$result) {
        error_log("Database query error: " . $conn->error);
        return false;
    }
    
    if ($result->num_rows === 0) {
        error_log("No orders found in database");
        return [];
    }
    
    $orders = array();
    while ($row = $result->fetch_assoc()) {
        $orders[] = array(
            'id' => $row['order_id'],
            'user_id' => $row['user_id'],
            'status' => $row['status'],
            'total_price' => $row['total_price'],
            'order_date' => $row['order_date'],
            'delivery_notes' => $row['delivery_notes'],
            'phone_number' => $row['phone_number'],
        );
    }
    return $orders;
}