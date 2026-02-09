<?php
require_once __DIR__ . '/../backend/config/config.php';

$conn = getConnection();

// Lấy sản phẩm mới nhất từ database
$stmt = $conn->query("SELECT * FROM products WHERE status = 'active' ORDER BY created_at DESC LIMIT 10");
$products = $stmt->fetchAll();

require_once FRONTEND_PATH . '/includes/header.php';
?>

    <!-- Hero Banner Slider -->
    <section class="hero-banner">
        <div class="banner-slider">
            <div class="banner-slide active">
                <img src="/shopcaulong/images/banner2.jpg" alt="VNB Sports Banner 1">
            </div>
            <div class="banner-slide">
                <img src="/shopcaulong/images/banner3.jpg" alt="VNB Sports Banner 2">
            </div>
            <div class="banner-slide">
                <img src="/shopcaulong/images/banner4.jpg" alt="VNB Sports Banner 3">
            </div>
        </div>
        <button class="slider-btn prev"><i class="fas fa-chevron-left"></i></button>
        <button class="slider-btn next"><i class="fas fa-chevron-right"></i></button>
        <div class="slider-dots">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </section>

    <!-- Features Bar -->
    <section class="features-bar">
        <div class="container">
            <div class="features-grid">
                <div class="feature-item">
                    <i class="fas fa-truck"></i>
                    <div>
                        <span>Vận chuyển <strong>TOÀN QUỐC</strong></span>
                        <small>Thanh toán khi nhận hàng</small>
                    </div>
                </div>
                <div class="feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <div>
                        <span><strong>Bảo đảm chất lượng</strong></span>
                        <small>Sản phẩm bảo đảm chất lượng.</small>
                    </div>
                </div>
                <div class="feature-item">
                    <i class="fas fa-credit-card"></i>
                    <div>
                        <span>Tiện hành <strong>THANH TOÁN</strong></span>
                        <small>Với nhiều PHƯƠNG THỨC</small>
                    </div>
                </div>
                <div class="feature-item">
                    <i class="fas fa-sync-alt"></i>
                    <div>
                        <span><strong>Đổi sản phẩm mới</strong></span>
                        <small>nếu sản phẩm lỗi</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- New Products Section -->
    <section class="products-section">
        <div class="container">
            <h2 class="section-title">Sản phẩm mới</h2>
            <div class="product-tabs">
                <button class="tab-btn active">Tất cả</button>
                <button class="tab-btn">Vợt Cầu Lông</button>
                <button class="tab-btn">Giày Cầu Lông</button>
                <button class="tab-btn">Áo Cầu Lông</button>
                <button class="tab-btn">Váy cầu lông</button>
                <button class="tab-btn">Quần Cầu Lông</button>
            </div>
            <div class="products-grid">
                <?php foreach ($products as $product): 
                    $price = $product['sale_price'] ?? $product['price'];
                    $oldPrice = $product['sale_price'] ? $product['price'] : null;
                ?>
                <a href="<?= BASE_URL ?>/product-detail.php?id=<?= $product['id'] ?>" class="product-card">
                    <div class="product-image">
                        <img src="/shopcaulong/images/<?= $product['image'] ?: 'product-placeholder.jpg' ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             onerror="this.src='/shopcaulong/images/product-placeholder.jpg'">
                        <?php if ($oldPrice): ?>
                        <span class="sale-badge">-<?= round((($oldPrice - $price) / $oldPrice) * 100) ?>%</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-info">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <?php if ($oldPrice): ?>
                        <p class="old-price"><?= number_format($oldPrice, 0, ',', '.') ?>đ</p>
                        <?php endif; ?>
                        <p class="price"><?= number_format($price, 0, ',', '.') ?>đ</p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Sale Off Section -->
    <section class="sale-section">
        <div class="container">
            <h2 class="section-title">Sale off</h2>
            <div class="sale-banners">
                <div class="sale-banner">
                    <a href="products.php?cat=vot">
                        <img src="../images/anh5.jpg" alt="Sale Vợt Cầu Lông">
                    </a>
                </div>
                <div class="sale-banner">
                    <a href="products.php?cat=giay">
                        <img src="../images/anh6.jpg" alt="Giảm Giá">
                    </a>
                </div>
                <div class="sale-banner">
                    <a href="products.php?cat=ao">
                        <img src="../images/anh7.jpg" alt="Sale Áo Cầu Lông">
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Section -->
    <section class="category-section">
        <div class="container">
            <h2 class="section-title">Sản phẩm cầu lông</h2>
            <div class="category-grid">
                <?php
                $categories = [
                    ['name' => 'VỢT CẦU LÔNG', 'image' => '../images/anh8.jpeg', 'link' => 'products.php?cat=vot'],
                    ['name' => 'GIÀY CẦU LÔNG', 'image' => '../images/anh9.jpg', 'link' => 'products.php?cat=giay'],
                    ['name' => 'ÁO CẦU LÔNG', 'image' => '../images/anh10.jpg', 'link' => 'products.php?cat=ao'],
                    ['name' => 'VÁY CẦU LÔNG', 'image' => '../images/anh11.jpg', 'link' => 'products.php?cat=vay'],
                    ['name' => 'QUẦN CẦU LÔNG', 'image' => '../images/anh12.jpg', 'link' => 'products.php?cat=quan'],
                    ['name' => 'TÚI VỢT CẦU LÔNG', 'image' => '../images/anh13.jpg', 'link' => 'products.php?cat=tui'],
                    ['name' => 'BALO CẦU LÔNG', 'image' => '../images/anh14.jpg', 'link' => 'products.php?cat=balo'],
                    ['name' => 'PHỤ KIỆN CẦU LÔNG', 'image' => '../images/anh15.jpg', 'link' => 'products.php?cat=phukien'],
                ];
                foreach ($categories as $cat): ?>
                <a href="<?= $cat['link'] ?>" class="category-card">
                    <img src="<?= $cat['image'] ?>" alt="<?= $cat['name'] ?>">
                    <span><?= $cat['name'] ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section class="news-section">
        <div class="container">
            <h2 class="section-title">Tin tức mới</h2>
            <div class="news-grid">
                <a href="<?= BASE_URL ?>/news.php?id=1" class="news-card">
                    <div class="news-image">
                        <img src="/shopcaulong/images/anh1.jpg" alt="Sân cầu lông City Sports">
                    </div>
                    <div class="news-content">
                        <h3>Review Sân Cầu Lông City Sports - Quận 12, TP.HCM</h3>
                        <span class="news-date">01-01-2026</span>
                    </div>
                </a>
                <a href="<?= BASE_URL ?>/news.php?id=2" class="news-card">
                    <div class="news-image">
                        <img src="/shopcaulong/images/anh3.jpg" alt="Sân cầu lông Ecosport Gò Dầu">
                    </div>
                    <div class="news-content">
                        <h3>Review Sân Cầu Lông Ecosport Gò Dầu - Quận Tân Phú</h3>
                        <span class="news-date">01-01-2026</span>
                    </div>
                </a>
                <a href="<?= BASE_URL ?>/news.php?id=3" class="news-card">
                    <div class="news-image">
                        <img src="/shopcaulong/images/anh2.jpg" alt="Sân cầu lông Văn Hiền">
                    </div>
                    <div class="news-content">
                        <h3>Review Sân Cầu Lông Văn Hiền - Thủ Dầu Một, Bình Dương</h3>
                        <span class="news-date">01-01-2026</span>
                    </div>
                </a>
                <a href="<?= BASE_URL ?>/news.php?id=4" class="news-card">
                    <div class="news-image">
                        <img src="/shopcaulong/images/anh4.jpg" alt="Sân cầu lông Bảo Khang">
                    </div>
                    <div class="news-content">
                        <h3>Review Sân Cầu Lông Bảo Khang - Quận Tân Phú, TP.HCM</h3>
                        <span class="news-date">01-01-2026</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

<?php require_once FRONTEND_PATH . '/includes/footer.php'; ?>
