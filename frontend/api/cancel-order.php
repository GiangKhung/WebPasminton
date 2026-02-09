<?php
require_once __DIR__ . '/../../backend/config/config.php';

header('Content-Type: application/json');

if (!User::isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập!']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$orderId = $data['order_id'] ?? 0;
$userId = $_SESSION['user_id'];

if (!$orderId) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin đơn hàng!']);
    exit;
}

$conn = getConnection();

// Kiểm tra đơn hàng thuộc về user và đang ở trạng thái pending
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ? AND status = 'pending'");
$stmt->execute([$orderId, $userId]);
$order = $stmt->fetch();

if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Không thể hủy đơn hàng này!']);
    exit;
}

// Cập nhật trạng thái đơn hàng
$stmt = $conn->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
$stmt->execute([$orderId]);

// Hoàn lại số lượng tồn kho
$stmt = $conn->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = ?");
$stmt->execute([$orderId]);
$items = $stmt->fetchAll();

foreach ($items as $item) {
    $conn->prepare("UPDATE products SET stock = stock + ? WHERE id = ?")
         ->execute([$item['quantity'], $item['product_id']]);
}

echo json_encode(['success' => true, 'message' => 'Đã hủy đơn hàng thành công!']);
