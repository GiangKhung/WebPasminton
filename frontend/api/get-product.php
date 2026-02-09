<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../backend/config/config.php';

$id = $_GET['id'] ?? 0;

if (!$id) {
    echo json_encode(['error' => 'Missing product ID']);
    exit;
}

try {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND status = 'active'");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    
    if ($product) {
        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Product not found']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
