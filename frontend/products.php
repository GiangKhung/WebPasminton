<?php
require_once __DIR__ . '/../backend/config/config.php';

$conn = getConnection();

// Lấy danh mục từ URL (slug)
$catSlug = $_GET['cat'] ?? '';
$currentCategory = null;
$parentCategory = null;
$pageTitle = 'Tất cả sản phẩm';

// Nếu có category slug, tìm category trong database
if ($catSlug) {
    $stmt = $conn->prepare("SELECT c.*, p.name as parent_name, p.slug as parent_slug 
                            FROM categories c 
                            LEFT JOIN categories p ON c.parent_id = p.id 
                            WHERE c.slug = ? AND c.status = 'active'");
    $stmt->execute([$catSlug]);
    $currentCategory = $stmt->fetch();
    
    if ($currentCategory) {
        $pageTitle = $currentCategory['name'];
        if ($currentCategory['parent_name']) {
            $parentCategory = [
                'name' => $currentCategory['parent_name'],
                'slug' => $currentCategory['parent_slug']
            ];
        }
    }
}

// Lấy tất cả danh mục để hiển thị sidebar
$categoriesQuery = $conn->query("
    SELECT c.*, p.name as parent_name 
    FROM categories c 
    LEFT JOIN categories p ON c.parent_id = p.id 
    WHERE c.status = 'active' 
    ORDER BY COALESCE(c.parent_id, c.id), c.parent_id IS NULL DESC, c.name
");
$allCategories = $categoriesQuery->fetchAll();

// Tổ chức danh mục theo nhóm
$parentCategories = [];
$childCategories = [];
foreach ($allCategories as $cat) {
    if ($cat['parent_id'] === null) {
        $parentCategories[$cat['id']] = $cat;
    } else {
        $childCategories[$cat['parent_id']][] = $cat;
    }
}

// Query sản phẩm từ database
$sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.status = 'active'";
$params = [];

if ($currentCategory) {
    // Nếu là danh mục cha, lấy tất cả sản phẩm của các danh mục con
    if ($currentCategory['parent_id'] === null) {
        $sql .= " AND (p.category_id = ? OR c.parent_id = ?)";
        $params[] = $currentCategory['id'];
        $params[] = $currentCategory['id'];
    } else {
        // Nếu là danh mục con, chỉ lấy sản phẩm của danh mục đó
        $sql .= " AND p.category_id = ?";
        $params[] = $currentCategory['id'];
    }
}

$sql .= " ORDER BY p.featured DESC, p.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

require_once FRONTEND_PATH . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1><?= htmlspecialchars($pageTitle) ?></h1>
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>">Trang chủ</a>
            <?php if ($parentCategory): ?>
            / <a href="<?= BASE_URL ?>/products.php?cat=<?= $parentCategory['slug'] ?>"><?= htmlspecialchars($parentCategory['name']) ?></a>
            <?php endif; ?>
            / <span><?= htmlspecialchars($pageTitle) ?></span>
        </nav>
    </div>
</section>

<section class="products-page">
    <div class="container">
        <div class="products-layout">
            <aside class="products-sidebar">
                <div class="filter-box">
                    <h3>Danh mục</h3>
                    <ul class="category-list">
                        <li>
                            <a href="<?= BASE_URL ?>/products.php" class="<?= !$catSlug ? 'active' : '' ?>">
                                Tất cả sản phẩm
                            </a>
                        </li>
                        <?php foreach ($parentCategories as $parentId => $parent): ?>
                        <li class="category-parent">
                            <a href="<?= BASE_URL ?>/products.php?cat=<?= $parent['slug'] ?>" 
                               class="parent-link <?= $catSlug == $parent['slug'] ? 'active' : '' ?>">
                                <?= htmlspecialchars($parent['name']) ?>
                            </a>
                            <?php if (isset($childCategories[$parentId])): ?>
                            <ul class="category-children">
                                <?php foreach ($childCategories[$parentId] as $child): ?>
                                <li>
                                    <a href="<?= BASE_URL ?>/products.php?cat=<?= $child['slug'] ?>" 
                                       class="<?= $catSlug == $child['slug'] ? 'active' : '' ?>">
                                        <?= htmlspecialchars($child['name']) ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </aside>
            <div class="products-main">
                <div class="products-header">
                    <p>Hiển thị <?= count($products) ?> sản phẩm</p>
                </div>
                <div class="products-grid">
                    <?php if (empty($products)): ?>
                    <p class="no-products">Chưa có sản phẩm nào trong danh mục này.</p>
                    <?php else: ?>
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once FRONTEND_PATH . '/includes/footer.php'; ?>
