<?php
require_once 'db_connect.php';

function getProducts($limit = null) {
    global $conn;
    
    $sql = "SELECT * FROM products";
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
        error_log("No products found in database");
        return [];
    }
    
    $products = array();
    while ($row = $result->fetch_assoc()) {
        $products[] = array(
            'id' => $row['product_id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'image' => $row['image_path'],
            'price' => $row['price'],
            'per_portion' => $row['per_portion'],
            'stock' => $row['current_stock'],
            'last_updated' => $row['last_updated']
        );
    }
    return $products;
}


function getProductsRaw($limit = null) {
    global $conn;
    
    $sql = "SELECT * FROM products";
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
        error_log("No products found in database");
        return [];
    }
    
    $products = array();
    while ($row = $result->fetch_assoc()) {
        $products[] = array(
            'id' => $row['product_id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'image' => base64_encode($row['image_path']),
            'price' => $row['price'],
            'per_portion' => $row['per_portion'],
            'stock' => $row['current_stock'],
            'last_updated' => $row['last_updated']
        );
    }
    return $products;
}

function getProductById($id) {
    global $conn;
    
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return array(
            'id' => $row['product_id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'image' => $row['image_path'],
            'price' => $row['price'],
            'per_portion' => $row['per_portion'],
            'stock' => $row['current_stock'],
            'last_updated' => $row['last_updated']
        );
    }
    
    return false;
}
?> 