<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VNB Sports - Cửa hàng cầu lông chính hãng</title>
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/styles.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Top Header -->
    <div class="top-header">
        <div class="container">
            <div class="top-header-content">
                <div class="logo">
                    <a href="<?= BASE_URL ?>">
                        <img src="/shopcaulong/images/logo.jpg" alt="VNB Sports">
                    </a>
                </div>
                <div class="contact-info">
                    <i class="fas fa-phone"></i>
                    <span>HOTLINE: <strong>0912431719</strong></span>
                </div>
                <div class="store-system">
                    <a href="<?= BASE_URL ?>/stores.php">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>HỆ THỐNG CỬA HÀNG</span>
                    </a>
                </div>
                <div class="search-box">
                    <input type="text" placeholder="Tìm sản phẩm...">
                    <button><i class="fas fa-search"></i></button>
                </div>
                <div class="user-actions">
                    <a href="#">
                        <i class="fas fa-search"></i>
                        <span>TRA CỨU</span>
                    </a>
                    <div class="account-dropdown">
                        <?php if (User::isLoggedIn()): ?>
                        <a href="<?= BASE_URL ?>/profile.php" class="account-btn">
                            <i class="fas fa-user"></i>
                            <span><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                        </a>
                        <div class="account-menu">
                            <a href="<?= BASE_URL ?>/profile.php"><i class="fas fa-user-circle"></i> Tài khoản</a>
                            <a href="<?= BASE_URL ?>/orders.php"><i class="fas fa-shopping-bag"></i> Đơn hàng</a>
                            <a href="<?= BASE_URL ?>/logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                        </div>
                        <?php else: ?>
                        <a href="#" class="account-btn">
                            <i class="fas fa-user"></i>
                            <span>TÀI KHOẢN</span>
                        </a>
                        <div class="account-menu">
                            <a href="<?= BASE_URL ?>/login.php"><i class="fas fa-sign-in-alt"></i> Đăng nhập</a>
                            <a href="<?= BASE_URL ?>/register.php"><i class="fas fa-user-plus"></i> Đăng ký</a>
                        </div>
                        <?php endif; ?>
                    </div>
                    <a href="<?= BASE_URL ?>/cart.php" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <span>GIỎ HÀNG</span>
                        <span class="cart-count">0</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="main-nav">
        <div class="container">
            <ul class="nav-menu">
                <li><a href="<?= BASE_URL ?>">Trang Chủ</a></li>
                <li class="has-mega-menu">
                    <a href="<?= BASE_URL ?>/products.php">Sản Phẩm <i class="fas fa-chevron-down"></i></a>
                    <div class="mega-menu">
                        <div class="mega-menu-inner">
                            <!-- Cầu Lông -->
                            <div class="mega-menu-column">
                                <h4><i class="fas fa-shuttlecock"></i> Cầu Lông</h4>
                                <ul>
                                    <li><a href="<?= BASE_URL ?>/products.php?cat=vot-cau-long">Vợt Cầu Lông</a></li>
                                    <li><a href="<?= BASE_URL ?>/products.php?cat=giay-cau-long">Giày Cầu Lông</a></li>
                                    <li><a href="<?= BASE_URL ?>/products.php?cat=ao-cau-long">Quần Áo Cầu Lông</a></li>
                                    <li><a href="<?= BASE_URL ?>/products.php?cat=phu-kien-cau-long">Phụ Kiện Cầu Lông</a></li>
                                </ul>
                            </div>
                            <!-- Bóng Đá -->
                            <div class="mega-menu-column">
                                <h4><i class="fas fa-futbol"></i> Bóng Đá</h4>
                                <ul>
                                    <li><a href="<?= BASE_URL ?>/products.php?cat=giay-bong-da">Giày Bóng Đá</a></li>
                                    <li><a href="<?= BASE_URL ?>/products.php?cat=ao-bong-da">Quần Áo Bóng Đá</a></li>
                                    <li><a href="<?= BASE_URL ?>/products.php?cat=bong-da-qua">Bóng Đá</a></li>
                                    <li><a href="<?= BASE_URL ?>/products.php?cat=phu-kien-bong-da">Phụ Kiện Bóng Đá</a></li>
                                </ul>
                            </div>
                            <!-- Tennis -->
                            <div class="mega-menu-column">
                                <h4><i class="fas fa-baseball-ball"></i> Tennis</h4>
                                <ul>
                                    <li><a href="<?= BASE_URL ?>/products.php?cat=vot-tennis">Vợt Tennis</a></li>
                                    <li><a href="<?= BASE_URL ?>/products.php?cat=giay-tennis">Giày Tennis</a></li>
                                    <li><a href="<?= BASE_URL ?>/products.php?cat=ao-tennis">Quần Áo Tennis</a></li>
                                    <li><a href="<?= BASE_URL ?>/products.php?cat=phu-kien-tennis">Phụ Kiện Tennis</a></li>
                                </ul>
                            </div>
                            <!-- Phụ Kiện -->
                            <div class="mega-menu-column">
                                <h4><i class="fas fa-tshirt"></i> Phụ Kiện</h4>
                                <ul>
                                    <li><a href="<?= BASE_URL ?>/products.php?cat=tui-balo">Túi & Balo</a></li>
                                    <li><a href="<?= BASE_URL ?>/products.php?cat=vo-can-cuoc">Vớ & Căng Cước</a></li>
                                    <li><a href="<?= BASE_URL ?>/products.php?cat=bang-bao-ve">Băng Bảo Vệ</a></li>
                                    <li><a href="<?= BASE_URL ?>/products.php?cat=phu-kien-khac">Phụ Kiện Khác</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <li><a href="<?= BASE_URL ?>/sale.php">Sale Off</a></li>
                <li><a href="<?= BASE_URL ?>/news.php">Tin Tức</a></li>
                <li><a href="<?= BASE_URL ?>/franchise.php">Nhượng Quyền</a></li>
                <li class="has-dropdown">
                    <a href="<?= BASE_URL ?>/guide.php">Hướng Dẫn <i class="fas fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= BASE_URL ?>/guide.php#mua-hang">Hướng dẫn mua hàng</a></li>
                        <li><a href="<?= BASE_URL ?>/guide.php#thanh-toan">Hướng dẫn thanh toán</a></li>
                        <li><a href="<?= BASE_URL ?>/guide.php#doi-tra">Chính sách đổi trả</a></li>
                    </ul>
                </li>
                <li><a href="<?= BASE_URL ?>/about.php">Giới Thiệu</a></li>
                <li><a href="<?= BASE_URL ?>/contact.php">Liên Hệ</a></li>
            </ul>
        </div>
    </nav>
