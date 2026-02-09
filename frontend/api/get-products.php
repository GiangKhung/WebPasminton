<?php
/**
 * API lấy thông tin sản phẩm theo IDs
 */
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../backend/config/config.php';

$ids = $_GET['ids'] ?? '';

if (empty($ids)) {
    echo json_encode([]);
    exit;
}

// Validate và sanitize IDs
$idArray = array_filter(array_map('intval', explode(',', $ids)));

if (empty($idArray)) {
    echo json_encode([]);
    exit;
}

try {
    $conn = getConnection();
    
    $placeholders = implode(',', array_fill(0, count($idArray), '?'));
    $sql = "SELECT id, name, slug, price, sale_price, image FROM products WHERE id IN ($placeholders) AND status = 'active'";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($idArray);
    $products = $stmt->fetchAll();
    
    echo json_encode($products);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
