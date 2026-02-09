<?php
require_once __DIR__ . '/../backend/config/config.php';

// Kiểm tra đăng nhập
if (!User::isLoggedIn()) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$conn = getConnection();

// Lấy danh sách đơn hàng của user
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();

require_once FRONTEND_PATH . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1>Đơn hàng của tôi</h1>
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>">Trang chủ</a> / <span>Đơn hàng</span>
        </nav>
    </div>
</section>

<section class="orders-page">
    <div class="container">
        <?php if (empty($orders)): ?>
        <div class="empty-orders">
            <i class="fas fa-shopping-bag"></i>
            <h3>Bạn chưa có đơn hàng nào</h3>
            <p>Hãy mua sắm và quay lại đây để theo dõi đơn hàng của bạn.</p>
            <a href="<?= BASE_URL ?>/products.php" class="btn-primary">Mua sắm ngay</a>
        </div>
        <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
            <div class="order-card">
                <div class="order-header">
                    <div class="order-info">
                        <span class="order-id">Đơn hàng #<?= $order['id'] ?></span>
                        <span class="order-date"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                    </div>
                    <span class="order-status status-<?= $order['status'] ?>">
                        <?php
                        $statusLabels = [
                            'pending' => 'Chờ xác nhận',
                            'confirmed' => 'Đã xác nhận',
                            'shipping' => 'Đang giao',
                            'completed' => 'Hoàn thành',
                            'cancelled' => 'Đã hủy'
                        ];
                        echo $statusLabels[$order['status']] ?? $order['status'];
                        ?>
                    </span>
                </div>
                <div class="order-body">
                    <div class="order-details">
                        <p><strong>Người nhận:</strong> <?= htmlspecialchars($order['fullname']) ?></p>
                        <p><strong>Điện thoại:</strong> <?= htmlspecialchars($order['phone']) ?></p>
                        <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></p>
                        <p><strong>Thanh toán:</strong> <?= $order['payment_method'] == 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản' ?></p>
                    </div>
                    <div class="order-total">
                        <span class="total-label">Tổng tiền:</span>
                        <span class="total-amount"><?= number_format($order['total'], 0, ',', '.') ?>đ</span>
                    </div>
                </div>
                <div class="order-footer">
                    <a href="<?= BASE_URL ?>/order-detail.php?id=<?= $order['id'] ?>" class="btn-view">
                        <i class="fas fa-eye"></i> Xem chi tiết
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
.orders-page {
    padding: 40px 0;
    min-height: 400px;
}

.empty-orders {
    text-align: center;
    padding: 60px 20px;
    background: #f8f9fa;
    border-radius: 12px;
}

.empty-orders i {
    font-size: 60px;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-orders h3 {
    margin: 0 0 10px;
    color: #333;
}

.empty-orders p {
    color: #666;
    margin-bottom: 20px;
}

.btn-primary {
    display: inline-block;
    background: linear-gradient(135deg, #ff8c00, #ff6600);
    color: white;
    padding: 12px 30px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.order-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
}

.order-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.order-id {
    font-weight: 600;
    color: #333;
}

.order-date {
    font-size: 13px;
    color: #666;
}

.order-status {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #cce5ff; color: #004085; }
.status-shipping { background: #d4edda; color: #155724; }
.status-completed { background: #d1e7dd; color: #0f5132; }
.status-cancelled { background: #f8d7da; color: #721c24; }

.order-body {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.order-details p {
    margin: 0 0 8px;
    font-size: 14px;
    color: #555;
}

.order-total {
    text-align: right;
}

.total-label {
    display: block;
    font-size: 13px;
    color: #666;
    margin-bottom: 4px;
}

.total-amount {
    font-size: 20px;
    font-weight: 700;
    color: #e74c3c;
}

.order-footer {
    padding: 15px 20px;
    border-top: 1px solid #eee;
    text-align: right;
}

.btn-view {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #667eea;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
}

.btn-view:hover {
    background: #5a6fd6;
}

@media (max-width: 600px) {
    .order-body {
        flex-direction: column;
        gap: 15px;
    }
    .order-total {
        text-align: left;
    }
}
</style>

<?php require_once FRONTEND_PATH . '/includes/footer.php'; ?>
