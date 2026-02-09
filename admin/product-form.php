<?php
require_once __DIR__ . '/../backend/config/config.php';

if (!User::isLoggedIn() || !User::isAdmin()) {
    header('Location: login.php');
    exit;
}

$conn = getConnection();
$product = [
    'id' => '', 'name' => '', 'slug' => '', 'description' => '',
    'price' => '', 'sale_price' => '', 'image' => '', 'category_id' => '',
    'stock' => 0, 'status' => 'active', 'featured' => 0,
    'sizes' => '', 'color' => '', 'material' => '', 'brand' => ''
];

$isEdit = isset($_GET['id']) && is_numeric($_GET['id']);
if ($isEdit) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $fetchedProduct = $stmt->fetch();
    if ($fetchedProduct) {
        $product = array_merge($product, $fetchedProduct);
    } else {
        header('Location: products.php?error=notfound');
        exit;
    }
}

function createSlug($str) {
    $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
    $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
    $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
    $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
    $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
    $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
    $str = preg_replace('/(đ)/', 'd', $str);
    $str = strtolower($str);
    $str = preg_replace('/[^a-z0-9\s-]/', '', $str);
    $str = preg_replace('/[\s-]+/', '-', $str);
    return trim($str, '-');
}

function uploadImage($file, $uploadDir = '../images/') {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if ($file['error'] !== UPLOAD_ERR_OK) return ['success' => false, 'message' => 'Lỗi upload'];
    if (!in_array($file['type'], $allowedTypes)) return ['success' => false, 'message' => 'Chỉ chấp nhận JPG, PNG, GIF, WEBP'];
    if ($file['size'] > 5 * 1024 * 1024) return ['success' => false, 'message' => 'File tối đa 5MB'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = 'product_' . time() . '_' . uniqid() . '.' . strtolower($ext);
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) return ['success' => true, 'filename' => $fileName];
    return ['success' => false, 'message' => 'Không thể lưu file'];
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product['name'] = trim($_POST['name'] ?? '');
    $product['slug'] = trim($_POST['slug'] ?? '') ?: createSlug($product['name']);
    $product['description'] = trim($_POST['description'] ?? '');
    $product['price'] = (int)($_POST['price'] ?? 0);
    $product['sale_price'] = !empty($_POST['sale_price']) ? (int)$_POST['sale_price'] : null;
    $product['category_id'] = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $product['stock'] = (int)($_POST['stock'] ?? 0);
    $product['status'] = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';
    $product['featured'] = isset($_POST['featured']) ? 1 : 0;
    $product['sizes'] = trim($_POST['sizes'] ?? '');
    $product['color'] = trim($_POST['color'] ?? '');
    $product['material'] = trim($_POST['material'] ?? '');
    $product['brand'] = trim($_POST['brand'] ?? '');
    
    if (empty($product['name'])) {
        $error = 'Vui lòng nhập tên sản phẩm!';
    } elseif ($product['price'] <= 0) {
        $error = 'Giá sản phẩm phải lớn hơn 0!';
    } elseif ($product['sale_price'] !== null && $product['sale_price'] >= $product['price']) {
        $error = 'Giá khuyến mãi phải nhỏ hơn giá gốc!';
    } else {
        if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = uploadImage($_FILES['image']);
            if ($uploadResult['success']) {
                $oldImage = $_POST['current_image'] ?? '';
                if ($oldImage && file_exists('../images/' . $oldImage)) @unlink('../images/' . $oldImage);
                $product['image'] = $uploadResult['filename'];
            } else {
                $error = $uploadResult['message'];
            }
        } else {
            $product['image'] = $_POST['current_image'] ?? '';
        }
        
        if (empty($error)) {
            try {
                $slugCheck = $conn->prepare("SELECT id FROM products WHERE slug = ? AND id != ?");
                $slugCheck->execute([$product['slug'], $isEdit ? $_GET['id'] : 0]);
                if ($slugCheck->fetch()) $product['slug'] .= '-' . time();
                
                if ($isEdit) {
                    $sql = "UPDATE products SET name=?, slug=?, description=?, price=?, sale_price=?, 
                            image=?, category_id=?, stock=?, status=?, featured=?, sizes=?, color=?, material=?, brand=?, updated_at=NOW() WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        $product['name'], $product['slug'], $product['description'],
                        $product['price'], $product['sale_price'], $product['image'],
                        $product['category_id'], $product['stock'], $product['status'],
                        $product['featured'], $product['sizes'], $product['color'], 
                        $product['material'], $product['brand'], $_GET['id']
                    ]);
                    header('Location: products.php?success=updated');
                    exit;
                } else {
                    $sql = "INSERT INTO products (name, slug, description, price, sale_price, image, category_id, stock, status, featured, sizes, color, material, brand, created_at) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([
                        $product['name'], $product['slug'], $product['description'],
                        $product['price'], $product['sale_price'], $product['image'],
                        $product['category_id'], $product['stock'], $product['status'],
                        $product['featured'], $product['sizes'], $product['color'],
                        $product['material'], $product['brand']
                    ]);
                    header('Location: products.php?success=added');
                    exit;
                }
            } catch (PDOException $e) {
                $error = 'Lỗi database: ' . $e->getMessage();
            }
        }
    }
}

// Lấy danh mục theo cấu trúc parent-child
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
require_once 'includes/header.php';
?>

<div class="admin-content">
    <div class="page-header">
        <div class="page-header-left">
            <h1 class="page-title">
                <i class="fas <?= $isEdit ? 'fa-edit' : 'fa-plus-circle' ?>"></i>
                <?= $isEdit ? 'Sửa sản phẩm' : 'Thêm sản phẩm mới' ?>
            </h1>
            <div class="breadcrumb">
                <a href="index.php">Dashboard</a>
                <i class="fas fa-chevron-right"></i>
                <a href="products.php">Sản phẩm</a>
                <i class="fas fa-chevron-right"></i>
                <span><?= $isEdit ? 'Sửa' : 'Thêm mới' ?></span>
            </div>
        </div>
        <a href="products.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>
    
    <?php if ($error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-triangle"></i>
        <span><?= htmlspecialchars($error) ?></span>
    </div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" id="productForm">
        <div class="form-container">
            <div class="form-main">
                <!-- Thông tin cơ bản -->
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Thông tin cơ bản</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="form-row two-cols">
                            <div class="form-group">
                                <label>Tên sản phẩm <span class="required">*</span></label>
                                <input type="text" name="name" id="productName" value="<?= htmlspecialchars($product['name']) ?>" placeholder="VD: Vợt Cầu Lông Yonex Astrox 99..." required>
                            </div>
                            <div class="form-group">
                                <label>Slug (URL)</label>
                                <input type="text" name="slug" id="productSlug" value="<?= htmlspecialchars($product['slug']) ?>" placeholder="Tự động tạo">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Mô tả sản phẩm</label>
                            <textarea name="description" rows="4" placeholder="Nhập mô tả chi tiết..."><?= htmlspecialchars($product['description']) ?></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Thông số kỹ thuật -->
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-list-alt"></i>
                        <h3>Thông số sản phẩm</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="form-row two-cols">
                            <div class="form-group">
                                <label>Thương hiệu</label>
                                <input type="text" name="brand" value="<?= htmlspecialchars($product['brand'] ?? '') ?>" placeholder="VD: Yonex, Victor, Lining...">
                            </div>
                            <div class="form-group">
                                <label>Màu sắc</label>
                                <input type="text" name="color" value="<?= htmlspecialchars($product['color'] ?? '') ?>" placeholder="VD: Đỏ, Xanh, Đen...">
                            </div>
                        </div>
                        <div class="form-row two-cols">
                            <div class="form-group">
                                <label>Kích cỡ</label>
                                <input type="text" name="sizes" value="<?= htmlspecialchars($product['sizes'] ?? '') ?>" placeholder="VD: S, M, L, XL hoặc 39, 40, 41...">
                            </div>
                            <div class="form-group">
                                <label>Chất liệu</label>
                                <input type="text" name="material" value="<?= htmlspecialchars($product['material'] ?? '') ?>" placeholder="VD: Carbon, Polyester...">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Giá & Kho -->
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-tags"></i>
                        <h3>Giá & Kho hàng</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="form-row three-cols">
                            <div class="form-group">
                                <label>Giá gốc <span class="required">*</span></label>
                                <div class="input-with-suffix">
                                    <input type="number" name="price" id="productPrice" min="0" value="<?= $product['price'] ?>" placeholder="0" required>
                                    <span class="input-suffix">₫</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Giá khuyến mãi</label>
                                <div class="input-with-suffix">
                                    <input type="number" name="sale_price" id="productSalePrice" min="0" value="<?= $product['sale_price'] ?>" placeholder="Không KM">
                                    <span class="input-suffix">₫</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Số lượng kho</label>
                                <div class="input-with-suffix">
                                    <input type="number" name="stock" min="0" value="<?= $product['stock'] ?>" placeholder="0">
                                    <span class="input-suffix">SP</span>
                                </div>
                            </div>
                        </div>
                        <div id="priceCompare" class="price-compare" style="display:none;">
                            <i class="fas fa-fire"></i>
                            <span>Giảm <strong id="discountPercent">0</strong>%</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="form-sidebar">
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-image"></i>
                        <h3>Hình ảnh</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="image-upload-box" id="imageUploadBox">
                            <input type="file" name="image" id="imageInput" accept="image/*">
                            <input type="hidden" name="current_image" value="<?= htmlspecialchars($product['image']) ?>">
                            <div class="upload-placeholder" id="uploadPlaceholder" <?= $product['image'] ? 'style="display:none"' : '' ?>>
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Kéo thả hoặc <span>chọn ảnh</span></p>
                                <small>JPG, PNG, WEBP (Max 5MB)</small>
                            </div>
                            <div class="image-preview" id="imagePreview" <?= !$product['image'] ? 'style="display:none"' : '' ?>>
                                <img src="<?= $product['image'] ? '../images/' . htmlspecialchars($product['image']) : '' ?>" alt="Preview" id="previewImg">
                                <button type="button" class="btn-remove-image" id="removeImage"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-cog"></i>
                        <h3>Cài đặt</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="form-group">
                            <label>Danh mục</label>
                            <select name="category_id" id="categorySelect">
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach ($parentCategories as $parentId => $parent): ?>
                                <optgroup label="<?= htmlspecialchars($parent['name']) ?>">
                                    <?php if (isset($childCategories[$parentId])): ?>
                                        <?php foreach ($childCategories[$parentId] as $child): ?>
                                        <option value="<?= $child['id'] ?>" <?= $product['category_id'] == $child['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($child['name']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select name="status">
                                <option value="active" <?= $product['status'] == 'active' ? 'selected' : '' ?>>✅ Hiển thị</option>
                                <option value="inactive" <?= $product['status'] == 'inactive' ? 'selected' : '' ?>>🚫 Ẩn</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="toggle-label">
                                <span class="toggle-text"><i class="fas fa-star"></i> Sản phẩm nổi bật</span>
                                <div class="toggle-switch">
                                    <input type="checkbox" name="featured" <?= $product['featured'] ? 'checked' : '' ?>>
                                    <span class="toggle-slider"></span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                        <i class="fas fa-save"></i> <?= $isEdit ? 'Cập nhật' : 'Thêm sản phẩm' ?>
                    </button>
                    <a href="products.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
:root { --primary: #f57c00; }
.page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
.page-header-left .page-title { display: flex; align-items: center; gap: 12px; font-size: 22px; font-weight: 700; color: #1a1a2e; margin: 0 0 8px 0; }
.page-header-left .page-title i { color: var(--primary); }
.breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #888; }
.breadcrumb a { color: #666; text-decoration: none; }
.breadcrumb a:hover { color: var(--primary); }
.breadcrumb i { font-size: 10px; color: #ccc; }
.btn-back { background: #f5f5f5; color: #666; padding: 10px 20px; border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 8px; font-weight: 500; }
.btn-back:hover { background: #eee; color: #333; }
.alert { padding: 16px 20px; border-radius: 10px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; }
.alert-error { background: #fff5f5; border: 1px solid #ffcdd2; color: #c62828; }
.form-container { display: grid; grid-template-columns: 1fr 350px; gap: 24px; align-items: start; }
.form-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 20px; overflow: hidden; }
.form-card-header { padding: 16px 20px; background: #f8f9fa; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 10px; }
.form-card-header i { font-size: 16px; color: var(--primary); }
.form-card-header h3 { font-size: 14px; font-weight: 600; color: #333; margin: 0; }
.form-card-body { padding: 20px; }
.form-group { margin-bottom: 16px; }
.form-group:last-child { margin-bottom: 0; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 6px; }
.required { color: #e53935; }
input[type="text"], input[type="number"], select, textarea { width: 100%; padding: 12px 14px; border: 2px solid #e8e8e8; border-radius: 8px; font-size: 14px; color: #333; background: #fafafa; transition: all 0.2s; box-sizing: border-box; }
input:focus, select:focus, textarea:focus { outline: none; border-color: var(--primary); background: #fff; box-shadow: 0 0 0 3px rgba(229,57,53,0.1); }
textarea { resize: vertical; min-height: 100px; }
select { cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23999'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; background-size: 20px; padding-right: 35px; }
.input-with-suffix { position: relative; }
.input-with-suffix input { padding-right: 45px; }
.input-suffix { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); font-size: 13px; font-weight: 600; color: #888; }
.form-row { display: grid; gap: 16px; }
.form-row.two-cols { grid-template-columns: repeat(2, 1fr); }
.form-row.three-cols { grid-template-columns: repeat(3, 1fr); }
.price-compare { margin-top: 12px; padding: 10px 14px; background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); border-radius: 8px; display: flex; align-items: center; gap: 8px; color: #e65100; font-size: 13px; font-weight: 500; }
.image-upload-box { position: relative; border: 2px dashed #ddd; border-radius: 10px; overflow: hidden; cursor: pointer; min-height: 180px; transition: all 0.2s; }
.image-upload-box:hover { border-color: var(--primary); background: #fff5f5; }
.image-upload-box input[type="file"] { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2; }
.upload-placeholder { padding: 35px 20px; text-align: center; }
.upload-placeholder i { font-size: 40px; color: #ddd; margin-bottom: 12px; display: block; }
.upload-placeholder p { color: #666; margin: 0 0 6px 0; font-size: 13px; }
.upload-placeholder span { color: var(--primary); font-weight: 600; }
.upload-placeholder small { color: #999; font-size: 11px; }
.image-preview { position: relative; }
.image-preview img { width: 100%; height: 180px; object-fit: cover; display: block; }
.btn-remove-image { position: absolute; top: 8px; right: 8px; width: 28px; height: 28px; border-radius: 50%; background: rgba(0,0,0,0.6); color: #fff; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 3; }
.btn-remove-image:hover { background: #e53935; }
.toggle-label { display: flex; align-items: center; justify-content: space-between; cursor: pointer; }
.toggle-text { display: flex; align-items: center; gap: 8px; font-weight: 500; font-size: 13px; }
.toggle-text i { color: #ffc107; }
.toggle-switch { position: relative; width: 48px; height: 26px; }
.toggle-switch input { opacity: 0; width: 0; height: 0; position: absolute; }
.toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background: #ddd; border-radius: 26px; transition: 0.3s; }
.toggle-slider:before { position: absolute; content: ""; height: 20px; width: 20px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
.toggle-switch input:checked + .toggle-slider { background: #4caf50; }
.toggle-switch input:checked + .toggle-slider:before { transform: translateX(22px); }
.form-actions { display: flex; flex-direction: column; gap: 10px; }
.btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; text-decoration: none; }
.btn-lg { padding: 14px 20px; }
.btn-primary { background: linear-gradient(135deg, #f57c00 0%, #e65100 100%); color: #fff; box-shadow: 0 4px 12px rgba(245,124,0,0.3); }
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(245,124,0,0.4); }
.btn-secondary { background: #f5f5f5; color: #666; }
.btn-secondary:hover { background: #eee; }
@media (max-width: 1024px) { .form-container { grid-template-columns: 1fr; } .form-row.two-cols, .form-row.three-cols { grid-template-columns: 1fr; } .form-actions { flex-direction: row; } .form-actions .btn { flex: 1; } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('productName');
    const slugInput = document.getElementById('productSlug');
    const priceInput = document.getElementById('productPrice');
    const salePriceInput = document.getElementById('productSalePrice');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const previewImg = document.getElementById('previewImg');
    const removeImageBtn = document.getElementById('removeImage');
    const priceCompare = document.getElementById('priceCompare');
    const discountPercent = document.getElementById('discountPercent');
    const form = document.getElementById('productForm');
    
    function createSlug(str) {
        str = str.toLowerCase();
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, 'a');
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, 'e');
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, 'i');
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, 'o');
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, 'u');
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, 'y');
        str = str.replace(/đ/g, 'd');
        str = str.replace(/[^a-z0-9\s-]/g, '');
        str = str.replace(/[\s-]+/g, '-');
        return str.replace(/^-+|-+$/g, '');
    }
    
    let slugManuallyEdited = slugInput.value.trim() !== '';
    nameInput.addEventListener('input', function() {
        if (!slugManuallyEdited) slugInput.value = createSlug(this.value);
    });
    slugInput.addEventListener('input', function() {
        slugManuallyEdited = this.value.trim() !== '';
    });
    
    function updateDiscount() {
        const price = parseInt(priceInput.value) || 0;
        const salePrice = parseInt(salePriceInput.value) || 0;
        if (price > 0 && salePrice > 0 && salePrice < price) {
            const percent = Math.round((price - salePrice) / price * 100);
            discountPercent.textContent = percent;
            priceCompare.style.display = 'flex';
        } else {
            priceCompare.style.display = 'none';
        }
    }
    priceInput.addEventListener('input', updateDiscount);
    salePriceInput.addEventListener('input', updateDiscount);
    updateDiscount();
    
    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            if (!['image/jpeg', 'image/png', 'image/gif', 'image/webp'].includes(file.type)) {
                alert('Chỉ chấp nhận file ảnh!'); this.value = ''; return;
            }
            if (file.size > 5 * 1024 * 1024) {
                alert('File tối đa 5MB!'); this.value = ''; return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
                uploadPlaceholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
    
    removeImageBtn.addEventListener('click', function(e) {
        e.preventDefault(); e.stopPropagation();
        imageInput.value = '';
        document.querySelector('input[name="current_image"]').value = '';
        previewImg.src = '';
        imagePreview.style.display = 'none';
        uploadPlaceholder.style.display = 'block';
    });
    
    form.addEventListener('submit', function(e) {
        const name = nameInput.value.trim();
        const price = parseInt(priceInput.value) || 0;
        const salePrice = parseInt(salePriceInput.value) || 0;
        if (!name) { e.preventDefault(); alert('Vui lòng nhập tên sản phẩm!'); nameInput.focus(); return; }
        if (price <= 0) { e.preventDefault(); alert('Giá phải lớn hơn 0!'); priceInput.focus(); return; }
        if (salePrice > 0 && salePrice >= price) { e.preventDefault(); alert('Giá KM phải nhỏ hơn giá gốc!'); salePriceInput.focus(); return; }
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
