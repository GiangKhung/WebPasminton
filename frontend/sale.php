<?php
require_once __DIR__ . '/../backend/config/config.php';

$conn = getConnection();

// Lấy sản phẩm đang giảm giá (có sale_price và sale_price < price)
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.status = 'active' 
        AND p.sale_price IS NOT NULL 
        AND p.sale_price < p.price
        ORDER BY (p.price - p.sale_price) DESC";
$stmt = $conn->query($sql);
$saleProducts = $stmt->fetchAll();

require_once FRONTEND_PATH . '/includes/header.php';
?>

<section class="page-banner sale-banner-bg">
    <div class="container">
        <h1>🔥 Sale Off - Giảm Giá Sốc</h1>
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>">Trang chủ</a> / <span>Sale Off</span>
        </nav>
    </div>
</section>

<section class="sale-page">
    <div class="container">
        <div class="sale-highlight">
            <h2>⚡ Flash Sale - Giảm đến 50%</h2>
            <p>Chương trình khuyến mãi có thời hạn. Nhanh tay đặt hàng!</p>
        </div>
        
        <?php if (empty($saleProducts)): ?>
        <div class="no-sale">
            <i class="fas fa-tags"></i>
            <h3>Hiện chưa có sản phẩm giảm giá</h3>
            <p>Hãy quay lại sau để xem các chương trình khuyến mãi mới nhất!</p>
            <a href="<?= BASE_URL ?>/products.php" class="btn-primary">Xem tất cả sản phẩm</a>
        </div>
        <?php else: ?>
        <div class="products-grid">
            <?php foreach ($saleProducts as $product): 
                $discount = round((($product['price'] - $product['sale_price']) / $product['price']) * 100);
            ?>
            <a href="<?= BASE_URL ?>/product-detail.php?id=<?= $product['id'] ?>" class="product-card sale-card">
                <span class="discount-badge">-<?= $discount ?>%</span>
                <div class="product-image">
                    <img src="/shopcaulong/images/<?= $product['image'] ?: 'product-placeholder.jpg' ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>"
                         onerror="this.src='/shopcaulong/images/product-placeholder.jpg'">
                </div>
                <div class="product-info">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="old-price"><?= number_format($product['price'], 0, ',', '.') ?>đ</p>
                    <p class="price"><?= number_format($product['sale_price'], 0, ',', '.') ?>đ</p>
                    <p class="save-amount">Tiết kiệm: <?= number_format($product['price'] - $product['sale_price'], 0, ',', '.') ?>đ</p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
.no-sale {
    text-align: center;
    padding: 60px 20px;
    background: #f8f9fa;
    border-radius: 12px;
}

.no-sale i {
    font-size: 60px;
    color: #ddd;
    margin-bottom: 20px;
}

.no-sale h3 {
    margin: 0 0 10px;
    color: #333;
}

.no-sale p {
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

.save-amount {
    color: #27ae60;
    font-size: 12px;
    font-weight: 500;
    margin-top: 5px;
}
</style>

<?php require_once FRONTEND_PATH . '/includes/footer.php'; ?>
