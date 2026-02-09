<?php
require_once __DIR__ . '/../backend/config/config.php';

$conn = getConnection();
$productId = $_GET['id'] ?? 0;

// Lấy thông tin sản phẩm
$stmt = $conn->prepare("SELECT p.*, c.name as category_name FROM products p 
                        LEFT JOIN categories c ON p.category_id = c.id 
                        WHERE p.id = ? AND p.status = 'active'");
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: ' . BASE_URL . '/products.php');
    exit;
}

$price = $product['sale_price'] ?? $product['price'];
$oldPrice = $product['sale_price'] ? $product['price'] : null;
$discount = $oldPrice ? round((($oldPrice - $price) / $oldPrice) * 100) : 0;

// Lấy sản phẩm liên quan
$relatedStmt = $conn->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? AND status = 'active' LIMIT 4");
$relatedStmt->execute([$product['category_id'], $productId]);
$relatedProducts = $relatedStmt->fetchAll();

require_once FRONTEND_PATH . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>">Trang chủ</a> / 
            <a href="<?= BASE_URL ?>/products.php">Sản phẩm</a> / 
            <span><?= htmlspecialchars($product['name']) ?></span>
        </nav>
    </div>
</section>

<section class="product-detail">
    <div class="container">
        <div class="product-detail-grid">
            <div class="product-gallery">
                <div class="main-image">
                    <img src="/shopcaulong/images/<?= $product['image'] ?: 'product-placeholder.jpg' ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>"
                         onerror="this.src='/shopcaulong/images/product-placeholder.jpg'"
                         id="mainImage">
                    <?php if ($discount > 0): ?>
                    <span class="sale-badge">-<?= $discount ?>%</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="product-info-detail">
                <h1><?= htmlspecialchars($product['name']) ?></h1>
                
                <div class="product-meta">
                    <span class="category"><i class="fas fa-tag"></i> <?= htmlspecialchars($product['category_name'] ?? 'Chưa phân loại') ?></span>
                    <span class="stock <?= $product['stock'] > 0 ? 'in-stock' : 'out-stock' ?>">
                        <i class="fas fa-<?= $product['stock'] > 0 ? 'check' : 'times' ?>-circle"></i>
                        <?= $product['stock'] > 0 ? 'Còn hàng (' . $product['stock'] . ')' : 'Hết hàng' ?>
                    </span>
                </div>
                
                <div class="product-price-box">
                    <?php if ($oldPrice): ?>
                    <span class="old-price"><?= number_format($oldPrice, 0, ',', '.') ?>đ</span>
                    <?php endif; ?>
                    <span class="current-price"><?= number_format($price, 0, ',', '.') ?>đ</span>
                    <?php if ($discount > 0): ?>
                    <span class="discount-tag">Tiết kiệm <?= number_format($oldPrice - $price, 0, ',', '.') ?>đ</span>
                    <?php endif; ?>
                </div>
                
                <div class="product-description">
                    <h3>Mô tả sản phẩm</h3>
                    <p><?= nl2br(htmlspecialchars($product['description'] ?: 'Chưa có mô tả cho sản phẩm này.')) ?></p>
                </div>
                
                <?php if (!empty($product['brand']) || !empty($product['color']) || !empty($product['sizes']) || !empty($product['material'])): ?>
                <div class="product-specs">
                    <h3>Thông số sản phẩm</h3>
                    <table class="specs-table">
                        <?php if (!empty($product['brand'])): ?>
                        <tr><td>Thương hiệu</td><td><?= htmlspecialchars($product['brand']) ?></td></tr>
                        <?php endif; ?>
                        <?php if (!empty($product['color'])): ?>
                        <tr><td>Màu sắc</td><td><?= htmlspecialchars($product['color']) ?></td></tr>
                        <?php endif; ?>
                        <?php if (!empty($product['sizes'])): ?>
                        <tr><td>Kích cỡ</td><td><?= htmlspecialchars($product['sizes']) ?></td></tr>
                        <?php endif; ?>
                        <?php if (!empty($product['material'])): ?>
                        <tr><td>Chất liệu</td><td><?= htmlspecialchars($product['material']) ?></td></tr>
                        <?php endif; ?>
                    </table>
                </div>
                <?php endif; ?>
                
                <?php if ($product['stock'] > 0): ?>
                <div class="product-actions">
                    <div class="quantity-selector">
                        <button type="button" class="qty-btn minus">-</button>
                        <input type="number" id="quantity" value="1" min="1" max="<?= $product['stock'] ?>">
                        <button type="button" class="qty-btn plus">+</button>
                    </div>
                    <button class="btn-add-cart" data-id="<?= $product['id'] ?>">
                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                    </button>
                    <button class="btn-buy-now" data-id="<?= $product['id'] ?>">
                        <i class="fas fa-bolt"></i> Mua ngay
                    </button>
                </div>
                <?php endif; ?>
                
                <div class="product-services">
                    <div class="service-item">
                        <i class="fas fa-truck"></i>
                        <span>Giao hàng toàn quốc</span>
                    </div>
                    <div class="service-item">
                        <i class="fas fa-sync-alt"></i>
                        <span>Đổi trả trong 7 ngày</span>
                    </div>
                    <div class="service-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Bảo hành chính hãng</span>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($relatedProducts)): ?>
        <div class="related-products">
            <h2>Sản phẩm liên quan</h2>
            <div class="products-grid">
                <?php foreach ($relatedProducts as $rp): 
                    $rpPrice = $rp['sale_price'] ?? $rp['price'];
                    $rpOldPrice = $rp['sale_price'] ? $rp['price'] : null;
                ?>
                <a href="<?= BASE_URL ?>/product-detail.php?id=<?= $rp['id'] ?>" class="product-card">
                    <div class="product-image">
                        <img src="/shopcaulong/images/<?= $rp['image'] ?: 'product-placeholder.jpg' ?>" 
                             alt="<?= htmlspecialchars($rp['name']) ?>"
                             onerror="this.src='/shopcaulong/images/product-placeholder.jpg'">
                    </div>
                    <div class="product-info">
                        <h3><?= htmlspecialchars($rp['name']) ?></h3>
                        <?php if ($rpOldPrice): ?>
                        <p class="old-price"><?= number_format($rpOldPrice, 0, ',', '.') ?>đ</p>
                        <?php endif; ?>
                        <p class="price"><?= number_format($rpPrice, 0, ',', '.') ?>đ</p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
.product-detail {
    padding: 30px 0 50px;
}

.product-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-bottom: 50px;
}

.product-gallery .main-image {
    position: relative;
    background: #f8f9fa;
    border-radius: 12px;
    overflow: hidden;
    width: 100%;
    height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-gallery .main-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
}

.product-gallery .sale-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: #e74c3c;
    color: white;
    padding: 6px 12px;
    border-radius: 4px;
    font-weight: 600;
}

.product-info-detail h1 {
    font-size: 24px;
    margin: 0 0 15px;
    color: #333;
}

.product-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    font-size: 14px;
}

.product-meta .category {
    color: #666;
}

.product-meta .in-stock {
    color: #27ae60;
}

.product-meta .out-stock {
    color: #e74c3c;
}

.product-price-box {
    background: #fff8f0;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 25px;
}

.product-price-box .old-price {
    text-decoration: line-through;
    color: #999;
    font-size: 16px;
    margin-right: 10px;
}

.product-price-box .current-price {
    font-size: 28px;
    font-weight: 700;
    color: #e74c3c;
}

.product-price-box .discount-tag {
    display: block;
    margin-top: 8px;
    color: #27ae60;
    font-size: 14px;
}

.product-description {
    margin-bottom: 25px;
}

.product-description h3 {
    font-size: 16px;
    margin: 0 0 10px;
    color: #333;
}

.product-description p {
    color: #666;
    line-height: 1.6;
}

.product-specs {
    margin-bottom: 25px;
}

.product-specs h3 {
    font-size: 16px;
    margin: 0 0 12px;
    color: #333;
}

.specs-table {
    width: 100%;
    border-collapse: collapse;
}

.specs-table tr {
    border-bottom: 1px solid #eee;
}

.specs-table td {
    padding: 10px 0;
    font-size: 14px;
}

.specs-table td:first-child {
    color: #666;
    width: 120px;
}

.specs-table td:last-child {
    color: #333;
    font-weight: 500;
}

.product-actions {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.quantity-selector {
    display: flex;
    align-items: center;
    border: 2px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
}

.qty-btn {
    width: 40px;
    height: 44px;
    border: none;
    background: #f5f5f5;
    font-size: 18px;
    cursor: pointer;
}

.qty-btn:hover {
    background: #e0e0e0;
}

.quantity-selector input {
    width: 60px;
    height: 44px;
    border: none;
    text-align: center;
    font-size: 16px;
    font-weight: 600;
}

.btn-add-cart, .btn-buy-now {
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.btn-add-cart {
    background: white;
    border: 2px solid #ff6600;
    color: #ff6600;
}

.btn-add-cart:hover {
    background: #fff5ee;
}

.btn-buy-now {
    background: linear-gradient(135deg, #ff8c00, #ff6600);
    color: white;
}

.btn-buy-now:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 102, 0, 0.4);
}

.product-services {
    display: flex;
    gap: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
}

.service-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #555;
}

.service-item i {
    color: #ff6600;
}

.related-products h2 {
    font-size: 22px;
    margin: 0 0 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #eee;
}

.related-products .products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

@media (max-width: 992px) {
    .product-detail-grid {
        grid-template-columns: 1fr;
    }
    .related-products .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .product-actions {
        flex-direction: column;
    }
    .product-services {
        flex-direction: column;
        gap: 12px;
    }
    .related-products .products-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const qtyInput = document.getElementById('quantity');
    const minusBtn = document.querySelector('.qty-btn.minus');
    const plusBtn = document.querySelector('.qty-btn.plus');
    
    if (minusBtn && plusBtn && qtyInput) {
        minusBtn.addEventListener('click', () => {
            let val = parseInt(qtyInput.value);
            if (val > 1) qtyInput.value = val - 1;
        });
        
        plusBtn.addEventListener('click', () => {
            let val = parseInt(qtyInput.value);
            let max = parseInt(qtyInput.max);
            if (val < max) qtyInput.value = val + 1;
        });
    }
    
    // Add to cart
    const addCartBtn = document.querySelector('.btn-add-cart');
    if (addCartBtn) {
        addCartBtn.addEventListener('click', function() {
            const productId = this.dataset.id;
            const qty = qtyInput ? qtyInput.value : 1;
            addToCart(productId, qty);
        });
    }
    
    // Buy now
    const buyNowBtn = document.querySelector('.btn-buy-now');
    if (buyNowBtn) {
        buyNowBtn.addEventListener('click', function() {
            const productId = this.dataset.id;
            const qty = qtyInput ? qtyInput.value : 1;
            addToCart(productId, qty, true);
        });
    }
    
    function addToCart(productId, qty, redirect = false) {
        // Lưu vào localStorage
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        let existing = cart.find(item => item.id == productId);
        
        if (existing) {
            existing.qty = parseInt(existing.qty) + parseInt(qty);
        } else {
            cart.push({ id: productId, qty: parseInt(qty) });
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        
        if (redirect) {
            window.location.href = '<?= BASE_URL ?>/cart.php';
        } else {
            alert('Đã thêm sản phẩm vào giỏ hàng!');
        }
    }
    
    function updateCartCount() {
        if (typeof window.updateAllCartCounts === 'function') {
            window.updateAllCartCounts();
        } else {
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            let total = cart.reduce((sum, item) => sum + item.qty, 0);
            document.querySelectorAll('.cart-count, .fixed-cart-count').forEach(el => {
                el.textContent = total;
            });
        }
    }
    
    updateCartCount();
});
</script>

<?php require_once FRONTEND_PATH . '/includes/footer.php'; ?>
