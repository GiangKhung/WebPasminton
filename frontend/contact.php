<?php
require_once __DIR__ . '/../backend/config/config.php';
require_once FRONTEND_PATH . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1>Liên Hệ</h1>
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>">Trang chủ</a> / <span>Liên hệ</span>
        </nav>
    </div>
</section>

<section class="contact-page">
    <div class="container">
        <div class="contact-wrapper">
            <div class="contact-info-box">
                <h3>Thông Tin Liên Hệ</h3>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Địa chỉ</h4>
                        <p>123 Đường ABC, Quận XYZ, TP. Hồ Chí Minh</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4>Hotline</h4>
                        <p>0912431719</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email</h4>
                        <p>contact@vnbsports.vn</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h4>Giờ làm việc</h4>
                        <p>8:00 - 21:00 (Thứ 2 - Chủ nhật)</p>
                    </div>
                </div>
            </div>

            <div class="contact-form-box">
                <h3>Gửi Tin Nhắn</h3>
                <form class="contact-form" method="POST">
                    <div class="form-group">
                        <label>Họ và tên *</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại *</label>
                            <input type="tel" name="phone" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tiêu đề</label>
                        <input type="text" name="subject">
                    </div>
                    <div class="form-group">
                        <label>Nội dung *</label>
                        <textarea name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Gửi Tin Nhắn</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once FRONTEND_PATH . '/includes/footer.php'; ?>
