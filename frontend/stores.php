<?php
require_once __DIR__ . '/../backend/config/config.php';
require_once FRONTEND_PATH . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <h1>Hệ Thống Cửa Hàng</h1>
        <nav class="breadcrumb">
            <a href="<?= BASE_URL ?>">Trang chủ</a> / <span>Hệ thống cửa hàng</span>
        </nav>
    </div>
</section>

<section class="stores-page">
    <div class="container">
        <div class="stores-intro">
            <h2>ADGSHOP - Cửa Hàng Cầu Lông Chính Hãng</h2>
            <p>Chuyên cung cấp các sản phẩm cầu lông chính hãng với giá tốt nhất.</p>
        </div>

        <div class="store-detail">
            <div class="store-info-box">
                <h3><i class="fas fa-store"></i> ADGSHOP</h3>
                <div class="store-info-item">
                    <i class="fas fa-location-dot"></i>
                    <p>Khóm 6, TT. Càng Long, Càng Long, Trà Vinh, Việt Nam</p>
                </div>
                <div class="store-info-item">
                    <i class="fas fa-phone"></i>
                    <p>0912431719</p>
                </div>
                <div class="store-info-item">
                    <i class="fas fa-clock"></i>
                    <p>8:00 - 21:00 (Thứ 2 - Chủ nhật)</p>
                </div>
                <div class="store-info-item">
                    <i class="fas fa-envelope"></i>
                    <p>contact@adgshop.vn</p>
                </div>
            </div>

            <div class="store-map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14387.227558742994!2d106.19047964087251!3d9.986195424630404!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a013013f79f097%3A0x6559c111a287b23b!2zS2jDs20gNiwgdHQuIEPDoG5nIExvbmcsIEPDoG5nIExvbmcsIFRyw6AgVmluaCwgVmnhu4d0IE5hbQ!5e1!3m2!1svi!2s!4v1767286504106!5m2!1svi!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</section>

<?php require_once FRONTEND_PATH . '/includes/footer.php'; ?>
