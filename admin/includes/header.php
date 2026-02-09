<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - VNB Sports</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="../images/logo.jpg" alt="VNB Sports">
                <h2>VNB Admin</h2>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
                        <a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <li class="<?= basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : '' ?>">
                        <a href="products.php"><i class="fas fa-box"></i> Sản phẩm</a>
                    </li>
                    <li class="<?= basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : '' ?>">
                        <a href="categories.php"><i class="fas fa-tags"></i> Danh mục</a>
                    </li>
                    <li class="<?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : '' ?>">
                        <a href="orders.php"><i class="fas fa-shopping-cart"></i> Đơn hàng</a>
                    </li>
                    <li class="<?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : '' ?>">
                        <a href="users.php"><i class="fas fa-users"></i> Người dùng</a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="../frontend/index.php" target="_blank"><i class="fas fa-external-link-alt"></i> Xem trang web</a>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <header class="topbar">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="topbar-right">
                    <div class="admin-info">
                        <span>Xin chào, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                        <a href="logout.php" class="btn-logout" title="Đăng xuất">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </div>
                </div>
            </header>
