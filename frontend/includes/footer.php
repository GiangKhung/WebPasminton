    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>THÔNG TIN CHUNG</h4>
                    <p><strong>VNB Sports</strong> là hệ thống cửa hàng cầu lông với hơn 50 chi nhánh trên toàn quốc, cung cấp sỉ và lẻ các mặt hàng dụng cụ cầu lông từ phong trào tới chuyên nghiệp.</p>
                    <p><strong>Với sứ mệnh:</strong> "VNB cam kết mang đến những sản phẩm, dịch vụ chất lượng tốt nhất phục vụ cho người chơi thể thao để nâng cao sức khỏe của chính mình."</p>
                </div>
                <div class="footer-col">
                    <h4>THÔNG TIN LIÊN HỆ</h4>
                    <p>Hệ thống cửa hàng: <a href="#">5 Super Center, 5 shop Premium và 78 cửa hàng</a> trên toàn quốc</p>
                    <p><strong>Hotline:</strong> 0977508430 | 0338000308</p>
                    <p><strong>Email:</strong> info@shopvnb.com</p>
                    <p><strong>Hợp tác kinh doanh:</strong> 0947342259 (Ms. Thảo)</p>
                </div>
                <div class="footer-col">
                    <h4>CHÍNH SÁCH</h4>
                    <ul>
                        <li><a href="#">Thông tin về vận chuyển và giao nhận</a></li>
                        <li><a href="#">Chính sách đổi trả/hoàn tiền</a></li>
                        <li><a href="#">Chính sách bảo hành</a></li>
                        <li><a href="#">Chính sách xử lý khiếu nại</a></li>
                        <li><a href="#">Điều khoản sử dụng</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>HƯỚNG DẪN</h4>
                    <ul>
                        <li><a href="#">Hướng dẫn cách chọn vợt cầu lông</a></li>
                        <li><a href="#">Hướng dẫn thanh toán</a></li>
                        <li><a href="#">Kiểm tra bảo hành</a></li>
                        <li><a href="#">Kiểm tra đơn hàng</a></li>
                        <li><a href="#">HƯỚNG DẪN MUA HÀNG</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Fixed Elements -->
    <a href="<?= BASE_URL ?>/cart.php" class="fixed-cart">
        <i class="fas fa-shopping-cart"></i>
        <span>Xem giỏ hàng (<span class="fixed-cart-count">0</span>)</span>
    </a>

    <div class="fixed-store">
        <a href="<?= BASE_URL ?>/stores.php">
            <i class="fas fa-store"></i>
            <span>Hệ thống cửa hàng</span>
        </a>
    </div>

    <a href="#" class="back-to-top">
        <i class="fas fa-chevron-up"></i>
    </a>

    <!-- Floating Action Button với Zalo + Chatbot -->
    <?php include_once FRONTEND_PATH . '/includes/chatbot.php'; ?>

    <script src="<?= ASSETS_URL ?>/js/main.js"></script>
    
    <script>
    // Hàm global cập nhật số lượng giỏ hàng
    window.updateAllCartCounts = function() {
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        let total = cart.reduce((sum, item) => sum + parseInt(item.qty || 0), 0);
        
        // Cập nhật tất cả các vị trí hiển thị số lượng giỏ hàng
        document.querySelectorAll('.cart-count, .fixed-cart-count').forEach(el => {
            el.textContent = total;
        });
    }
    
    // Chạy khi trang load
    document.addEventListener('DOMContentLoaded', window.updateAllCartCounts);
    </script>
</body>
</html>
