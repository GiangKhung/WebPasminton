<?php
require_once __DIR__ . '/../backend/config/config.php';

$conn = getConnection();
$error = '';
$success = '';

// Xử lý đặt hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $province = trim($_POST['province'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $shipping_fee = intval($_POST['shipping_fee'] ?? 0);
    $note = trim($_POST['note'] ?? '');
    $payment_method = $_POST['payment_method'] ?? 'cod';
    $cart_data = $_POST['cart_data'] ?? '';
    
    if (empty($fullname) || empty($email) || empty($phone) || empty($province) || empty($address)) {
        $error = 'Vui lòng điền đầy đủ thông tin!';
    } else {
        $cart = json_decode($cart_data, true);
        
        if (empty($cart)) {
            $error = 'Giỏ hàng trống!';
        } else {
            // Tính tổng tiền và lấy thông tin sản phẩm
            $subtotal = 0;
            $orderItems = [];
            
            foreach ($cart as $item) {
                $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND status = 'active'");
                $stmt->execute([$item['id']]);
                $product = $stmt->fetch();
                
                if ($product) {
                    $price = $product['sale_price'] ?? $product['price'];
                    $itemTotal = $price * $item['qty'];
                    $subtotal += $itemTotal;
                    
                    $orderItems[] = [
                        'product_id' => $product['id'],
                        'product_name' => $product['name'],
                        'price' => $price,
                        'quantity' => $item['qty']
                    ];
                }
            }
            
            $total = $subtotal + $shipping_fee;
            
            if ($subtotal > 0) {
                try {
                    $conn->beginTransaction();
                    
                    // Tạo đơn hàng - ghép tỉnh thành vào địa chỉ
                    $fullAddress = $address . ' - ' . $province;
                    $userId = User::isLoggedIn() ? $_SESSION['user_id'] : null;
                    $stmt = $conn->prepare("INSERT INTO orders (user_id, fullname, email, phone, address, note, total, shipping_fee, payment_method) 
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$userId, $fullname, $email, $phone, $fullAddress, $note, $total, $shipping_fee, $payment_method]);
                    $orderId = $conn->lastInsertId();
                    
                    // Thêm chi tiết đơn hàng
                    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) 
                                           VALUES (?, ?, ?, ?, ?)");
                    foreach ($orderItems as $item) {
                        $stmt->execute([$orderId, $item['product_id'], $item['product_name'], $item['price'], $item['quantity']]);
                        
                        // Giảm số lượng tồn kho
                        $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?")->execute([$item['quantity'], $item['product_id']]);
                    }
                    
                    $conn->commit();
                    
                    // Redirect đến trang thành công
                    header('Location: ' . BASE_URL . '/order-success.php?id=' . $orderId);
                    exit;
                    
                } catch (Exception $e) {
                    $conn->rollBack();
                    $error = 'Có lỗi xảy ra: ' . $e->getMessage();
                }
            } else {
                $error = 'Không thể tính tổng đơn hàng!';
            }
        }
    }
}

// Lấy thông tin user nếu đã đăng nhập
$user = null;
if (User::isLoggedIn()) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
}

require_once FRONTEND_PATH . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1>Thanh toán</h1>
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>">Trang chủ</a> / 
            <a href="<?= BASE_URL ?>/cart.php">Giỏ hàng</a> / 
            <span>Thanh toán</span>
        </nav>
    </div>
</section>

<section class="checkout-page">
    <div class="container">
        <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" id="checkout-form">
            <input type="hidden" name="cart_data" id="cart_data">
            <input type="hidden" name="shipping_fee" id="shipping_fee" value="0">
            
            <div class="checkout-grid">
                <div class="checkout-info">
                    <h2>Thông tin giao hàng</h2>
                    
                    <div class="form-group">
                        <label>Họ và tên *</label>
                        <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname'] ?? '') ?>" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại *</label>
                            <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Tỉnh/Thành phố *</label>
                        <select name="province" id="province" required>
                            <option value="">-- Chọn tỉnh/thành phố --</option>
                            <option value="HCM" data-ship="30000">TP. Hồ Chí Minh - 30.000đ</option>
                            <option value="HN" data-ship="35000">Hà Nội - 35.000đ</option>
                            <option value="DN" data-ship="15000">Đà Nẵng - 15.000đ</option>
                            <option value="BD" data-ship="10000">Bình Dương - 10.000đ</option>
                            <option value="DNG" data-ship="10000">Đồng Nai - 10.000đ</option>
                            <option value="HP" data-ship="20000">Hải Phòng - 20.000đ</option>
                            <option value="CT" data-ship="25000">Cần Thơ - 25.000đ</option>
                            <option value="AG" data-ship="30000">An Giang - 30.000đ</option>
                            <option value="BR" data-ship="15000">Bà Rịa - Vũng Tàu - 15.000đ</option>
                            <option value="BG" data-ship="25000">Bắc Giang - 25.000đ</option>
                            <option value="BK" data-ship="35000">Bắc Kạn - 35.000đ</option>
                            <option value="BL" data-ship="30000">Bạc Liêu - 30.000đ</option>
                            <option value="BN" data-ship="20000">Bắc Ninh - 20.000đ</option>
                            <option value="BT" data-ship="30000">Bến Tre - 30.000đ</option>
                            <option value="BP" data-ship="20000">Bình Phước - 20.000đ</option>
                            <option value="BTH" data-ship="25000">Bình Thuận - 25.000đ</option>
                            <option value="BDI" data-ship="25000">Bình Định - 25.000đ</option>
                            <option value="CM" data-ship="35000">Cà Mau - 35.000đ</option>
                            <option value="CB" data-ship="35000">Cao Bằng - 35.000đ</option>
                            <option value="DL" data-ship="25000">Đắk Lắk - 25.000đ</option>
                            <option value="DNO" data-ship="30000">Đắk Nông - 30.000đ</option>
                            <option value="DB" data-ship="35000">Điện Biên - 35.000đ</option>
                            <option value="DT" data-ship="30000">Đồng Tháp - 30.000đ</option>
                            <option value="GL" data-ship="30000">Gia Lai - 30.000đ</option>
                            <option value="HG" data-ship="35000">Hà Giang - 35.000đ</option>
                            <option value="HNA" data-ship="25000">Hà Nam - 25.000đ</option>
                            <option value="HTI" data-ship="30000">Hà Tĩnh - 30.000đ</option>
                            <option value="HD" data-ship="20000">Hải Dương - 20.000đ</option>
                            <option value="HGI" data-ship="30000">Hậu Giang - 30.000đ</option>
                            <option value="HB" data-ship="30000">Hòa Bình - 30.000đ</option>
                            <option value="HY" data-ship="20000">Hưng Yên - 20.000đ</option>
                            <option value="KH" data-ship="25000">Khánh Hòa - 25.000đ</option>
                            <option value="KG" data-ship="30000">Kiên Giang - 30.000đ</option>
                            <option value="KT" data-ship="30000">Kon Tum - 30.000đ</option>
                            <option value="LC" data-ship="35000">Lai Châu - 35.000đ</option>
                            <option value="LD" data-ship="20000">Lâm Đồng - 20.000đ</option>
                            <option value="LS" data-ship="35000">Lạng Sơn - 35.000đ</option>
                            <option value="LCA" data-ship="35000">Lào Cai - 35.000đ</option>
                            <option value="LA" data-ship="25000">Long An - 25.000đ</option>
                            <option value="ND" data-ship="25000">Nam Định - 25.000đ</option>
                            <option value="NA" data-ship="30000">Nghệ An - 30.000đ</option>
                            <option value="NB" data-ship="25000">Ninh Bình - 25.000đ</option>
                            <option value="NT" data-ship="25000">Ninh Thuận - 25.000đ</option>
                            <option value="PT" data-ship="25000">Phú Thọ - 25.000đ</option>
                            <option value="PY" data-ship="25000">Phú Yên - 25.000đ</option>
                            <option value="QB" data-ship="30000">Quảng Bình - 30.000đ</option>
                            <option value="QNA" data-ship="25000">Quảng Nam - 25.000đ</option>
                            <option value="QNG" data-ship="25000">Quảng Ngãi - 25.000đ</option>
                            <option value="QNI" data-ship="30000">Quảng Ninh - 30.000đ</option>
                            <option value="QT" data-ship="30000">Quảng Trị - 30.000đ</option>
                            <option value="ST" data-ship="30000">Sóc Trăng - 30.000đ</option>
                            <option value="SL" data-ship="35000">Sơn La - 35.000đ</option>
                            <option value="TN" data-ship="15000">Tây Ninh - 15.000đ</option>
                            <option value="TB" data-ship="25000">Thái Bình - 25.000đ</option>
                            <option value="TNG" data-ship="30000">Thái Nguyên - 30.000đ</option>
                            <option value="TH" data-ship="30000">Thanh Hóa - 30.000đ</option>
                            <option value="TTH" data-ship="25000">Thừa Thiên Huế - 25.000đ</option>
                            <option value="TG" data-ship="25000">Tiền Giang - 25.000đ</option>
                            <option value="TV" data-ship="0">Trà Vinh - Miễn phí ship</option>
                            <option value="TQ" data-ship="35000">Tuyên Quang - 35.000đ</option>
                            <option value="VL" data-ship="30000">Vĩnh Long - 30.000đ</option>
                            <option value="VP" data-ship="25000">Vĩnh Phúc - 25.000đ</option>
                            <option value="YB" data-ship="35000">Yên Bái - 35.000đ</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Địa chỉ chi tiết *</label>
                        <textarea name="address" rows="3" placeholder="Số nhà, tên đường, phường/xã, quận/huyện..." required><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Ghi chú</label>
                        <textarea name="note" rows="2" placeholder="Ghi chú về đơn hàng..."></textarea>
                    </div>
                    
                    <h2>Phương thức thanh toán</h2>
                    <div class="payment-methods">
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <span class="payment-label">
                                <i class="fas fa-truck"></i>
                                Thanh toán khi nhận hàng (COD)
                            </span>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="bank">
                            <span class="payment-label">
                                <i class="fas fa-university"></i>
                                Chuyển khoản ngân hàng
                            </span>
                        </label>
                    </div>
                    
                    <!-- Thông tin chuyển khoản -->
                    <div id="bank-info" class="bank-transfer-info" style="display: none;">
                        <h3><i class="fas fa-info-circle"></i> Thông tin chuyển khoản</h3>
                        <div class="bank-details">
                            <div class="bank-qr">
                                <img src="https://img.vietqr.io/image/VCB-1234567890-compact.png?amount=0&addInfo=THANHTOAN&accountName=VNB%20SPORTS" 
                                     alt="QR Code" id="bank-qr-img">
                                <p>Quét mã QR để thanh toán</p>
                            </div>
                            <div class="bank-account">
                                <div class="bank-row">
                                    <span class="bank-label">Ngân hàng:</span>
                                    <span class="bank-value"><strong>Vietcombank</strong></span>
                                </div>
                                <div class="bank-row">
                                    <span class="bank-label">Số tài khoản:</span>
                                    <span class="bank-value"><strong>1234567890</strong></span>
                                    <button type="button" class="copy-btn" onclick="copyText('1234567890')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                                <div class="bank-row">
                                    <span class="bank-label">Chủ tài khoản:</span>
                                    <span class="bank-value"><strong>VNB SPORTS</strong></span>
                                </div>
                                <div class="bank-row">
                                    <span class="bank-label">Chi nhánh:</span>
                                    <span class="bank-value">TP. Hồ Chí Minh</span>
                                </div>
                                <div class="bank-note">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Nội dung chuyển khoản: <strong>SDT + Họ tên</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Thông tin chuyển khoản -->
                    <div id="bank-info" class="bank-transfer-info" style="display: none;">
                        <h3><i class="fas fa-info-circle"></i> Thông tin chuyển khoản</h3>
                        <div class="bank-details">
                            <div class="bank-qr">
                                <img src="https://img.vietqr.io/image/VCB-1234567890-compact.png?amount=0&addInfo=THANHTOAN&accountName=VNB%20SPORTS" 
                                     alt="QR Code" id="bank-qr-img">
                                <p>Quét mã QR để thanh toán</p>
                            </div>
                            <div class="bank-account">
                                <div class="bank-row">
                                    <span class="bank-label">Ngân hàng:</span>
                                    <span class="bank-value"><strong>Vietcombank</strong></span>
                                </div>
                                <div class="bank-row">
                                    <span class="bank-label">Số tài khoản:</span>
                                    <span class="bank-value"><strong>1234567890</strong></span>
                                    <button type="button" class="copy-btn" onclick="copyText('1234567890')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                                <div class="bank-row">
                                    <span class="bank-label">Chủ tài khoản:</span>
                                    <span class="bank-value"><strong>VNB SPORTS</strong></span>
                                </div>
                                <div class="bank-row">
                                    <span class="bank-label">Chi nhánh:</span>
                                    <span class="bank-value">TP. Hồ Chí Minh</span>
                                </div>
                                <div class="bank-note">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Nội dung chuyển khoản: <strong>SDT + Họ tên</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="checkout-summary">
                    <h2>Đơn hàng của bạn</h2>
                    <div id="checkout-items">
                        <!-- Items sẽ được load bằng JS -->
                    </div>
                    <div class="checkout-totals">
                        <div class="total-row">
                            <span>Tạm tính:</span>
                            <span id="subtotal">0đ</span>
                        </div>
                        <div class="total-row">
                            <span>Phí vận chuyển:</span>
                            <span id="shipping-fee">Chọn tỉnh/thành</span>
                        </div>
                        <div class="total-row total-final">
                            <span>Tổng cộng:</span>
                            <span id="total">0đ</span>
                        </div>
                    </div>
                    <button type="submit" class="btn-checkout">
                        <i class="fas fa-check"></i> Đặt hàng
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<style>
.checkout-page {
    padding: 40px 0;
}

.checkout-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 40px;
}

.checkout-info h2, .checkout-summary h2 {
    font-size: 20px;
    margin: 0 0 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #eee;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    border-color: #ff6600;
    outline: none;
}

.form-group select {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    background: white;
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.payment-option {
    display: flex;
    align-items: center;
    padding: 15px;
    border: 2px solid #eee;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.payment-option:hover {
    border-color: #ff6600;
}

.payment-option input {
    margin-right: 12px;
}

.payment-option input:checked + .payment-label {
    color: #ff6600;
}

.payment-label {
    display: flex;
    align-items: center;
    gap: 10px;
}

.checkout-summary {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 12px;
    height: fit-content;
}

.checkout-item {
    display: flex;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}

.checkout-item img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
}

.checkout-item-info {
    flex: 1;
}

.checkout-item-info h4 {
    font-size: 13px;
    margin: 0 0 5px;
    color: #333;
}

.checkout-item-info p {
    font-size: 12px;
    color: #666;
    margin: 0;
}

.checkout-item-price {
    font-weight: 600;
    color: #e74c3c;
}

.checkout-totals {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 2px solid #ddd;
}

.total-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
}

.total-final {
    font-size: 18px;
    font-weight: 700;
    color: #e74c3c;
    border-top: 1px solid #ddd;
    margin-top: 10px;
    padding-top: 15px;
}

.btn-checkout {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #ff8c00, #ff6600);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-checkout:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 102, 0, 0.4);
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-error {
    background: #fee;
    color: #c00;
    border: 1px solid #fcc;
}

/* Bank Transfer Info */
.bank-transfer-info {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    border: 2px solid #4caf50;
    border-radius: 12px;
    padding: 20px;
    margin-top: 20px;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.bank-transfer-info h3 {
    color: #2e7d32;
    margin: 0 0 15px;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.bank-details {
    display: flex;
    gap: 25px;
    align-items: flex-start;
}

.bank-qr {
    text-align: center;
    background: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.bank-qr img {
    width: 180px;
    height: 180px;
    border-radius: 8px;
}

.bank-qr p {
    margin: 10px 0 0;
    font-size: 12px;
    color: #666;
}

.bank-account {
    flex: 1;
}

.bank-row {
    display: flex;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px dashed #a5d6a7;
}

.bank-row:last-of-type {
    border-bottom: none;
}

.bank-label {
    width: 120px;
    color: #555;
    font-size: 14px;
}

.bank-value {
    flex: 1;
    font-size: 14px;
}

.copy-btn {
    background: #4caf50;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 12px;
    margin-left: 10px;
}

.copy-btn:hover {
    background: #388e3c;
}

.bank-note {
    background: #fff3cd;
    color: #856404;
    padding: 12px 15px;
    border-radius: 8px;
    margin-top: 15px;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 10px;
}

@media (max-width: 600px) {
    .bank-details {
        flex-direction: column;
    }
    .bank-qr {
        width: 100%;
    }
}

@media (max-width: 900px) {
    .checkout-grid {
        grid-template-columns: 1fr;
    }
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
let cartSubtotal = 0;
let currentShippingFee = 0;

document.addEventListener('DOMContentLoaded', function() {
    loadCheckoutItems();
    
    // Xử lý chọn tỉnh/thành phố
    const provinceSelect = document.getElementById('province');
    provinceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        currentShippingFee = parseInt(selectedOption.dataset.ship) || 0;
        
        // Cập nhật hiển thị phí ship
        const shippingFeeEl = document.getElementById('shipping-fee');
        if (currentShippingFee === 0) {
            shippingFeeEl.textContent = 'Miễn phí';
            shippingFeeEl.style.color = '#27ae60';
        } else {
            shippingFeeEl.textContent = formatPrice(currentShippingFee);
            shippingFeeEl.style.color = '#333';
        }
        
        // Cập nhật hidden input
        document.getElementById('shipping_fee').value = currentShippingFee;
        
        // Cập nhật tổng cộng
        updateTotal();
    });
    
    // Toggle bank info khi chọn phương thức thanh toán
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const bankInfo = document.getElementById('bank-info');
    
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'bank') {
                bankInfo.style.display = 'block';
            } else {
                bankInfo.style.display = 'none';
            }
        });
    });
    
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        if (cart.length === 0) {
            e.preventDefault();
            alert('Giỏ hàng trống!');
            return;
        }
        
        const province = document.getElementById('province').value;
        if (!province) {
            e.preventDefault();
            alert('Vui lòng chọn tỉnh/thành phố!');
            document.getElementById('province').focus();
            return;
        }
        
        document.getElementById('cart_data').value = JSON.stringify(cart);
    });
});

function updateTotal() {
    const total = cartSubtotal + currentShippingFee;
    document.getElementById('total').textContent = formatPrice(total);
}

// Copy text function
function copyText(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Đã sao chép: ' + text);
    }).catch(() => {
        // Fallback
        const input = document.createElement('input');
        input.value = text;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
        alert('Đã sao chép: ' + text);
    });
}

async function loadCheckoutItems() {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    
    if (cart.length === 0) {
        window.location.href = '<?= BASE_URL ?>/cart.php';
        return;
    }
    
    const container = document.getElementById('checkout-items');
    let html = '';
    cartSubtotal = 0;
    
    for (let item of cart) {
        try {
            const res = await fetch('<?= BASE_URL ?>/api/get-product.php?id=' + item.id);
            const product = await res.json();
            
            if (product && !product.error) {
                const price = product.sale_price || product.price;
                const subtotal = price * item.qty;
                cartSubtotal += subtotal;
                
                html += `
                    <div class="checkout-item">
                        <img src="/shopcaulong/images/${product.image || 'product-placeholder.jpg'}" 
                             onerror="this.src='/shopcaulong/images/product-placeholder.jpg'">
                        <div class="checkout-item-info">
                            <h4>${product.name}</h4>
                            <p>Số lượng: ${item.qty}</p>
                        </div>
                        <div class="checkout-item-price">${formatPrice(subtotal)}</div>
                    </div>
                `;
            }
        } catch (err) {
            console.error(err);
        }
    }
    
    container.innerHTML = html;
    document.getElementById('subtotal').textContent = formatPrice(cartSubtotal);
    updateTotal();
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price) + 'đ';
}
</script>

<?php require_once FRONTEND_PATH . '/includes/footer.php'; ?>
