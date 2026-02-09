<?php
require_once __DIR__ . '/../backend/config/config.php';
require_once FRONTEND_PATH . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1>Giỏ hàng</h1>
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>">Trang chủ</a> / <span>Giỏ hàng</span>
        </nav>
    </div>
</section>

<section class="cart-page">
    <div class="container">
        <div id="cart-empty" class="cart-empty" style="display: none;">
            <i class="fas fa-shopping-cart"></i>
            <h3>Giỏ hàng trống</h3>
            <p>Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
            <a href="<?= BASE_URL ?>/products.php" class="btn-primary">Mua sắm ngay</a>
        </div>
        
        <div id="cart-content" class="cart-content">
            <div class="cart-items">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Tổng</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="cart-body">
                        <!-- Cart items will be loaded here -->
                    </tbody>
                </table>
            </div>
            
            <div class="cart-summary">
                <h3>Tổng đơn hàng</h3>
                <div class="summary-row">
                    <span>Tạm tính:</span>
                    <span id="subtotal">0đ</span>
                </div>
                <div class="summary-row">
                    <span>Phí vận chuyển:</span>
                    <span id="shipping">Miễn phí</span>
                </div>
                <div class="summary-row total">
                    <span>Tổng cộng:</span>
                    <span id="total">0đ</span>
                </div>
                <a href="<?= BASE_URL ?>/checkout.php" class="btn-checkout">
                    <i class="fas fa-credit-card"></i> Tiến hành thanh toán
                </a>
                <a href="<?= BASE_URL ?>/products.php" class="btn-continue">
                    <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.cart-page {
    padding: 40px 0;
    min-height: 400px;
}

.cart-empty {
    text-align: center;
    padding: 60px 20px;
    background: #f8f9fa;
    border-radius: 12px;
}

.cart-empty i {
    font-size: 60px;
    color: #ddd;
    margin-bottom: 20px;
}

.cart-empty h3 {
    margin: 0 0 10px;
    color: #333;
}

.cart-empty p {
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

.cart-content {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 30px;
}

.cart-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.cart-table th {
    background: #f8f9fa;
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: #333;
}

.cart-table td {
    padding: 15px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.cart-product {
    display: flex;
    align-items: center;
    gap: 15px;
}

.cart-product img {
    width: 80px;
    height: 80px;
    object-fit: contain;
    background: #f8f9fa;
    border-radius: 8px;
}

.cart-product-info h4 {
    margin: 0 0 5px;
    font-size: 14px;
    color: #333;
}

.cart-product-info a {
    color: #333;
    text-decoration: none;
}

.cart-product-info a:hover {
    color: #ff6600;
}

.cart-price {
    font-weight: 600;
    color: #e74c3c;
}

.cart-qty {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 6px;
    overflow: hidden;
    width: fit-content;
}

.cart-qty button {
    width: 32px;
    height: 32px;
    border: none;
    background: #f5f5f5;
    cursor: pointer;
    font-size: 16px;
}

.cart-qty button:hover {
    background: #e0e0e0;
}

.cart-qty input {
    width: 50px;
    height: 32px;
    border: none;
    text-align: center;
    font-weight: 600;
}

.cart-total {
    font-weight: 700;
    color: #e74c3c;
    font-size: 16px;
}

.cart-remove {
    background: none;
    border: none;
    color: #999;
    cursor: pointer;
    font-size: 18px;
    padding: 5px;
}

.cart-remove:hover {
    color: #e74c3c;
}

.cart-summary {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    height: fit-content;
    position: sticky;
    top: 20px;
}

.cart-summary h3 {
    margin: 0 0 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #eee;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    color: #666;
}

.summary-row.total {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 2px solid #eee;
    font-size: 18px;
    font-weight: 700;
    color: #333;
}

.summary-row.total span:last-child {
    color: #e74c3c;
}

.btn-checkout {
    display: block;
    width: 100%;
    background: linear-gradient(135deg, #ff8c00, #ff6600);
    color: white;
    padding: 14px;
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    font-weight: 600;
    margin-top: 20px;
    transition: all 0.3s;
}

.btn-checkout:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 102, 0, 0.4);
}

.btn-continue {
    display: block;
    width: 100%;
    background: #f8f9fa;
    color: #333;
    padding: 12px;
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    margin-top: 10px;
}

.btn-continue:hover {
    background: #e9ecef;
}

@media (max-width: 992px) {
    .cart-content {
        grid-template-columns: 1fr;
    }
    .cart-summary {
        position: static;
    }
}

@media (max-width: 600px) {
    .cart-table th:nth-child(2),
    .cart-table td:nth-child(2) {
        display: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadCart();
});

async function loadCart() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const cartEmpty = document.getElementById('cart-empty');
    const cartContent = document.getElementById('cart-content');
    const cartBody = document.getElementById('cart-body');
    
    if (cart.length === 0) {
        cartEmpty.style.display = 'block';
        cartContent.style.display = 'none';
        return;
    }
    
    cartEmpty.style.display = 'none';
    cartContent.style.display = 'grid';
    
    // Fetch product details
    const productIds = cart.map(item => item.id).join(',');
    
    try {
        const response = await fetch('<?= BASE_URL ?>/api/get-products.php?ids=' + productIds);
        const products = await response.json();
        
        let html = '';
        let subtotal = 0;
        
        cart.forEach(item => {
            const product = products.find(p => p.id == item.id);
            if (product) {
                const price = product.sale_price || product.price;
                const itemTotal = price * item.qty;
                subtotal += itemTotal;
                
                html += `
                    <tr data-id="${product.id}">
                        <td>
                            <div class="cart-product">
                                <img src="/shopcaulong/images/${product.image || 'product-placeholder.jpg'}" 
                                     alt="${product.name}"
                                     onerror="this.src='/shopcaulong/images/product-placeholder.jpg'">
                                <div class="cart-product-info">
                                    <h4><a href="<?= BASE_URL ?>/product-detail.php?id=${product.id}">${product.name}</a></h4>
                                </div>
                            </div>
                        </td>
                        <td class="cart-price">${formatPrice(price)}đ</td>
                        <td>
                            <div class="cart-qty">
                                <button onclick="updateQty(${product.id}, -1)">-</button>
                                <input type="number" value="${item.qty}" min="1" onchange="setQty(${product.id}, this.value)">
                                <button onclick="updateQty(${product.id}, 1)">+</button>
                            </div>
                        </td>
                        <td class="cart-total">${formatPrice(itemTotal)}đ</td>
                        <td>
                            <button class="cart-remove" onclick="removeItem(${product.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }
        });
        
        cartBody.innerHTML = html;
        document.getElementById('subtotal').textContent = formatPrice(subtotal) + 'đ';
        document.getElementById('total').textContent = formatPrice(subtotal) + 'đ';
        
    } catch (error) {
        console.error('Error loading cart:', error);
        cartBody.innerHTML = '<tr><td colspan="5">Có lỗi xảy ra khi tải giỏ hàng</td></tr>';
    }
}

function updateQty(productId, change) {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const item = cart.find(i => i.id == productId);
    
    if (item) {
        item.qty = Math.max(1, item.qty + change);
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCart();
        updateCartCount();
    }
}

function setQty(productId, qty) {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const item = cart.find(i => i.id == productId);
    
    if (item) {
        item.qty = Math.max(1, parseInt(qty) || 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCart();
        updateCartCount();
    }
}

function removeItem(productId) {
    if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        cart = cart.filter(i => i.id != productId);
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCart();
        updateCartCount();
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}

function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    let total = cart.reduce((sum, item) => sum + item.qty, 0);
    document.querySelectorAll('.cart-count, .fixed-cart-count').forEach(el => {
        el.textContent = total;
    });
}
</script>

<?php require_once FRONTEND_PATH . '/includes/footer.php'; ?>
