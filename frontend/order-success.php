<?php
require_once __DIR__ . '/../backend/config/config.php';

$orderId = $_GET['id'] ?? 0;

if (!$orderId) {
    header('Location: ' . BASE_URL);
    exit;
}

$conn = getConnection();
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: ' . BASE_URL);
    exit;
}

// Lấy chi tiết đơn hàng
$stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$orderId]);
$items = $stmt->fetchAll();

require_once FRONTEND_PATH . '/includes/header.php';
?>

<section class="order-success-page">
    <div class="container">
        <div class="success-box">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Đặt hàng thành công!</h1>
            <p>Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được tiếp nhận.</p>
            
            <div class="order-info-box">
                <h3>Thông tin đơn hàng #<?= $order['id'] ?></h3>
                <div class="order-details">
                    <p><strong>Người nhận:</strong> <?= htmlspecialchars($order['fullname']) ?></p>
                    <p><strong>Điện thoại:</strong> <?= htmlspecialchars($order['phone']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
                    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></p>
                    <p><strong>Thanh toán:</strong> <?= $order['payment_method'] == 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản ngân hàng' ?></p>
                </div>
                
                <h4>Sản phẩm đã đặt:</h4>
                <div class="order-items">
                    <?php foreach ($items as $item): ?>
                    <div class="order-item">
                        <span class="item-name"><?= htmlspecialchars($item['product_name']) ?> x <?= $item['quantity'] ?></span>
                        <span class="item-price"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</span>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-total">
                    <span>Tổng cộng:</span>
                    <span class="total-amount"><?= number_format($order['total'], 0, ',', '.') ?>đ</span>
                </div>
            </div>
            
            <?php if ($order['payment_method'] == 'bank'): ?>
            <div class="bank-info">
                <h4>Thông tin chuyển khoản:</h4>
                <p><strong>Ngân hàng:</strong> Vietcombank</p>
                <p><strong>Số tài khoản:</strong> 1234567890</p>
                <p><strong>Chủ tài khoản:</strong> VNB SPORTS</p>
                <p><strong>Nội dung:</strong> DH<?= $order['id'] ?> - <?= $order['phone'] ?></p>
            </div>
            <?php endif; ?>
            
            <div class="success-actions">
                <a href="<?= BASE_URL ?>/orders.php" class="btn-orders">
                    <i class="fas fa-list"></i> Xem đơn hàng
                </a>
                <a href="<?= BASE_URL ?>/products.php" class="btn-continue">
                    <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.order-success-page {
    padding: 50px 0;
    min-height: 60vh;
}

.success-box {
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
    background: white;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 5px 30px rgba(0,0,0,0.1);
}

.success-icon {
    font-size: 80px;
    color: #27ae60;
    margin-bottom: 20px;
}

.success-box h1 {
    color: #27ae60;
    margin: 0 0 10px;
}

.success-box > p {
    color: #666;
    margin-bottom: 30px;
}

.order-info-box {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 12px;
    text-align: left;
    margin-bottom: 25px;
}

.order-info-box h3 {
    margin: 0 0 15px;
    color: #333;
    font-size: 18px;
}

.order-info-box h4 {
    margin: 20px 0 10px;
    color: #333;
    font-size: 15px;
}

.order-details p {
    margin: 8px 0;
    font-size: 14px;
    color: #555;
}

.order-items {
    border-top: 1px solid #ddd;
    padding-top: 10px;
}

.order-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    font-size: 14px;
}

.order-total {
    display: flex;
    justify-content: space-between;
    padding-top: 15px;
    margin-top: 10px;
    border-top: 2px solid #ddd;
    font-size: 18px;
    font-weight: 700;
}

.total-amount {
    color: #e74c3c;
}

.bank-info {
    background: #fff3cd;
    padding: 20px;
    border-radius: 10px;
    text-align: left;
    margin-bottom: 25px;
}

.bank-info h4 {
    margin: 0 0 10px;
    color: #856404;
}

.bank-info p {
    margin: 5px 0;
    font-size: 14px;
}

.success-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
}

.btn-orders, .btn-continue {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 25px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
}

.btn-orders {
    background: #667eea;
    color: white;
}

.btn-continue {
    background: linear-gradient(135deg, #ff8c00, #ff6600);
    color: white;
}
</style>

<script>
// Xóa giỏ hàng sau khi đặt hàng thành công
localStorage.removeItem('cart');
</script>

<?php require_once FRONTEND_PATH . '/includes/footer.php'; ?>
