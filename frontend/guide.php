<?php
require_once __DIR__ . '/../backend/config/config.php';
require_once FRONTEND_PATH . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1>Hướng Dẫn</h1>
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>">Trang chủ</a> / <span>Hướng dẫn</span>
        </nav>
    </div>
</section>

<section class="guide-page">
    <div class="container">
        <div class="guide-section" id="mua-hang">
            <h2><i class="fas fa-shopping-cart"></i> Hướng Dẫn Mua Hàng</h2>
            <div class="guide-content">
                <h4>Bước 1: Chọn sản phẩm</h4>
                <p>Truy cập website và tìm kiếm sản phẩm bạn muốn mua. Bạn có thể sử dụng thanh tìm kiếm hoặc duyệt theo danh mục.</p>
                
                <h4>Bước 2: Thêm vào giỏ hàng</h4>
                <p>Nhấn nút "Thêm vào giỏ hàng" để thêm sản phẩm. Bạn có thể tiếp tục mua sắm hoặc tiến hành thanh toán.</p>
                
                <h4>Bước 3: Kiểm tra giỏ hàng</h4>
                <p>Xem lại các sản phẩm trong giỏ hàng, điều chỉnh số lượng nếu cần.</p>
                
                <h4>Bước 4: Đặt hàng</h4>
                <p>Điền thông tin giao hàng và chọn phương thức thanh toán. Xác nhận đơn hàng.</p>
            </div>
        </div>

        <div class="guide-section" id="thanh-toan">
            <h2><i class="fas fa-credit-card"></i> Hướng Dẫn Thanh Toán</h2>
            <div class="guide-content">
                <h4>Thanh toán khi nhận hàng (COD)</h4>
                <p>Bạn thanh toán trực tiếp cho nhân viên giao hàng khi nhận được sản phẩm.</p>
                
                <h4>Chuyển khoản ngân hàng</h4>
                <p>Chuyển khoản vào tài khoản ngân hàng của VNB Sports. Đơn hàng sẽ được xử lý sau khi xác nhận thanh toán.</p>
                
                <h4>Ví điện tử</h4>
                <p>Hỗ trợ thanh toán qua MoMo, ZaloPay, VNPay...</p>
            </div>
        </div>

        <div class="guide-section" id="doi-tra">
            <h2><i class="fas fa-sync-alt"></i> Chính Sách Đổi Trả</h2>
            <div class="guide-content">
                <h4>Điều kiện đổi trả</h4>
                <ul>
                    <li>Sản phẩm còn nguyên tem, nhãn mác</li>
                    <li>Chưa qua sử dụng</li>
                    <li>Trong vòng 7 ngày kể từ ngày nhận hàng</li>
                    <li>Có hóa đơn mua hàng</li>
                </ul>
                
                <h4>Quy trình đổi trả</h4>
                <p>Liên hệ hotline 0912431719 để được hướng dẫn đổi trả sản phẩm.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once FRONTEND_PATH . '/includes/footer.php'; ?>
