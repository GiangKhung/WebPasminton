<?php
require_once __DIR__ . '/../backend/config/config.php';

// Kiểm tra đăng nhập
if (!User::isLoggedIn()) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$conn = getConnection();
$orderId = $_GET['id'] ?? 0;
$userId = $_SESSION['user_id'];

// Lấy thông tin đơn hàng (chỉ lấy đơn của user hiện tại)
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$orderId, $userId]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: ' . BASE_URL . '/orders.php');
    exit;
}

// Lấy chi tiết sản phẩm trong đơn hàng
$stmt = $conn->prepare("SELECT oi.*, p.image FROM order_items oi 
                        LEFT JOIN products p ON oi.product_id = p.id 
                        WHERE oi.order_id = ?");
$stmt->execute([$orderId]);
$orderItems = $stmt->fetchAll();

// Tính tạm tính (tổng - phí ship)
$shippingFee = $order['shipping_fee'] ?? 0;
$subtotal = $order['total'] - $shippingFee;

function getOrderStatus($status) {
    $statuses = [
        'pending' => 'Chờ xử lý',
        'confirmed' => 'Đã xác nhận',
        'shipping' => 'Đang giao hàng',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Đã hủy'
    ];
    return $statuses[$status] ?? $status;
}

function getStatusClass($status) {
    $classes = [
        'pending' => 'warning',
        'confirmed' => 'info',
        'shipping' => 'primary',
        'completed' => 'success',
        'cancelled' => 'danger'
    ];
    return $classes[$status] ?? 'secondary';
}

require_once FRONTEND_PATH . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1>Chi tiết đơn hàng #<?= $order['id'] ?></h1>
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>">Trang chủ</a> / 
            <a href="<?= BASE_URL ?>/orders.php">Đơn hàng</a> / 
            <span>Chi tiết đơn hàng</span>
        </nav>
    </div>
</section>

<section class="order-detail-page">
    <div class="container">
        <div class="order-detail-grid">
            <!-- Thông tin đơn hàng -->
            <div class="order-info-card">
                <h3><i class="fas fa-info-circle"></i> Thông tin đơn hàng</h3>
                <div class="info-row">
                    <span class="label">Mã đơn hàng:</span>
                    <span class="value"><strong>#<?= $order['id'] ?></strong></span>
                </div>
                <div class="info-row">
                    <span class="label">Ngày đặt:</span>
                    <span class="value"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Trạng thái:</span>
                    <span class="value">
                        <span class="status-badge status-<?= getStatusClass($order['status']) ?>">
                            <?= getOrderStatus($order['status']) ?>
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">Thanh toán:</span>
                    <span class="value"><?= $order['payment_method'] == 'cod' ? 'Thanh toán khi nhận hàng' : 'Chuyển khoản ngân hàng' ?></span>
                </div>
            </div>

            <!-- Thông tin giao hàng -->
            <div class="order-info-card">
                <h3><i class="fas fa-truck"></i> Thông tin giao hàng</h3>
                <div class="info-row">
                    <span class="label">Người nhận:</span>
                    <span class="value"><?= htmlspecialchars($order['fullname']) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Số điện thoại:</span>
                    <span class="value"><?= htmlspecialchars($order['phone']) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Email:</span>
                    <span class="value"><?= htmlspecialchars($order['email']) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Địa chỉ:</span>
                    <span class="value"><?= htmlspecialchars($order['address']) ?></span>
                </div>
                <?php if (!empty($order['note'])): ?>
                <div class="info-row">
                    <span class="label">Ghi chú:</span>
                    <span class="value"><?= htmlspecialchars($order['note']) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="order-products-card">
            <h3><i class="fas fa-shopping-bag"></i> Sản phẩm đã đặt</h3>
            <table class="order-items-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td class="product-cell">
                            <img src="/shopcaulong/images/<?= $item['image'] ?: 'product-placeholder.jpg' ?>" 
                                 alt="<?= htmlspecialchars($item['product_name']) ?>"
                                 onerror="this.src='/shopcaulong/images/product-placeholder.jpg'">
                            <span><?= htmlspecialchars($item['product_name']) ?></span>
                        </td>
                        <td><?= number_format($item['price']) ?>đ</td>
                        <td><?= $item['quantity'] ?></td>
                        <td class="item-total"><?= number_format($item['price'] * $item['quantity']) ?>đ</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Tổng tiền -->
            <div class="order-totals">
                <div class="total-row">
                    <span>Tạm tính:</span>
                    <span><?= number_format($subtotal) ?>đ</span>
                </div>
                <div class="total-row">
                    <span>Phí vận chuyển:</span>
                    <span><?= $shippingFee > 0 ? number_format($shippingFee) . 'đ' : 'Miễn phí' ?></span>
                </div>
                <div class="total-row total-final">
                    <span>Tổng cộng:</span>
                    <span><?= number_format($order['total']) ?>đ</span>
                </div>
            </div>
        </div>

        <div class="order-actions">
            <a href="<?= BASE_URL ?>/orders.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
            <?php if ($order['status'] == 'pending'): ?>
            <button class="btn-cancel" onclick="cancelOrder(<?= $order['id'] ?>)">
                <i class="fas fa-times"></i> Hủy đơn hàng
            </button>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.order-detail-page {
    padding: 40px 0;
}

.order-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-bottom: 25px;
}

.order-info-card, .order-products-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.order-info-card h3, .order-products-card h3 {
    margin: 0 0 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #eee;
    font-size: 18px;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

.order-info-card h3 i, .order-products-card h3 i {
    color: #ff6600;
}

.info-row {
    display: flex;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-row:last-child {
    border-bottom: none;
}

.info-row .label {
    width: 140px;
    color: #666;
    font-size: 14px;
}

.info-row .value {
    flex: 1;
    color: #333;
    font-size: 14px;
}

.status-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-warning { background: #fff3cd; color: #856404; }
.status-info { background: #d1ecf1; color: #0c5460; }
.status-primary { background: #cce5ff; color: #004085; }
.status-success { background: #d4edda; color: #155724; }
.status-danger { background: #f8d7da; color: #721c24; }

.order-items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.order-items-table th {
    background: #f8f9fa;
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.order-items-table td {
    padding: 15px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
}

.product-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.product-cell img {
    width: 60px;
    height: 60px;
    object-fit: contain;
    background: #f8f9fa;
    border-radius: 8px;
}

.item-total {
    font-weight: 600;
    color: #e74c3c;
}

.order-totals {
    border-top: 2px solid #eee;
    padding-top: 15px;
}

.total-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    font-size: 14px;
}

.total-final {
    font-size: 18px;
    font-weight: 700;
    color: #e74c3c;
    border-top: 1px solid #eee;
    margin-top: 10px;
    padding-top: 15px;
}

.order-actions {
    display: flex;
    gap: 15px;
    margin-top: 25px;
}

.btn-back, .btn-cancel {
    padding: 12px 25px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-back {
    background: #f8f9fa;
    color: #333;
    border: 1px solid #ddd;
}

.btn-back:hover {
    background: #e9ecef;
}

.btn-cancel {
    background: #fee;
    color: #c00;
    border: 1px solid #fcc;
}

.btn-cancel:hover {
    background: #fdd;
}

@media (max-width: 768px) {
    .order-detail-grid {
        grid-template-columns: 1fr;
    }
    
    .order-items-table th:nth-child(2),
    .order-items-table td:nth-child(2) {
        display: none;
    }
    
    .product-cell {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<script>
function cancelOrder(orderId) {
    if (confirm('Bạn có chắc muốn hủy đơn hàng này?')) {
        fetch('<?= BASE_URL ?>/api/cancel-order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ order_id: orderId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Đã hủy đơn hàng thành công!');
                location.reload();
            } else {
                alert(data.message || 'Có lỗi xảy ra!');
            }
        })
        .catch(() => alert('Có lỗi xảy ra!'));
    }
}
</script>

<?php require_once FRONTEND_PATH . '/includes/footer.php'; ?>
