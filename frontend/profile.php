<?php
require_once __DIR__ . '/../backend/config/config.php';

if (!User::isLoggedIn()) {
    header('Location: login.php?redirect=profile.php');
    exit;
}

$userObj = new User();
$user = $userObj->getUserById($_SESSION['user_id']);

// Kiểm tra nếu không tìm thấy user
if (!$user) {
    session_destroy();
    header('Location: login.php?error=session_expired');
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_profile') {
        $result = $userObj->updateProfile(
            $_SESSION['user_id'],
            trim($_POST['fullname'] ?? ''),
            trim($_POST['phone'] ?? ''),
            trim($_POST['address'] ?? '')
        );
        if ($result['success']) {
            $success = $result['message'];
            $user = $userObj->getUserById($_SESSION['user_id']);
        } else {
            $error = $result['message'];
        }
    }
    
    if ($action === 'change_password') {
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        if ($new_password !== $confirm_password) {
            $error = 'Mật khẩu xác nhận không khớp!';
        } elseif (strlen($new_password) < 6) {
            $error = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
        } else {
            $result = $userObj->changePassword($_SESSION['user_id'], $_POST['old_password'], $new_password);
            $success = $result['success'] ? $result['message'] : '';
            $error = !$result['success'] ? $result['message'] : '';
        }
    }
}

require_once FRONTEND_PATH . '/includes/header.php';
?>

<div class="profile-page">
    <div class="container">
        <div class="profile-wrapper">
            <div class="profile-sidebar">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                    <h3><?= htmlspecialchars($user['fullname']) ?></h3>
                    <p><?= htmlspecialchars($user['email']) ?></p>
                </div>
                <ul class="profile-menu">
                    <li class="active"><a href="#info" data-tab="info"><i class="fas fa-user"></i> Thông tin tài khoản</a></li>
                    <li><a href="#password" data-tab="password"><i class="fas fa-lock"></i> Đổi mật khẩu</a></li>
                    <li><a href="orders.php"><i class="fas fa-shopping-bag"></i> Đơn hàng của tôi</a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                </ul>
            </div>
            
            <div class="profile-content">
                <?php if ($success): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <div class="profile-tab active" id="info">
                    <h2>Thông tin tài khoản</h2>
                    <form method="POST" class="profile-form">
                        <input type="hidden" name="action" value="update_profile">
                        <div class="form-group">
                            <label>Họ và tên</label>
                            <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label>Địa chỉ</label>
                            <textarea name="address" rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn-submit"><i class="fas fa-save"></i> Cập nhật</button>
                    </form>
                </div>
                
                <div class="profile-tab" id="password">
                    <h2>Đổi mật khẩu</h2>
                    <form method="POST" class="profile-form">
                        <input type="hidden" name="action" value="change_password">
                        <div class="form-group">
                            <label>Mật khẩu hiện tại</label>
                            <input type="password" name="old_password" required>
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu mới</label>
                            <input type="password" name="new_password" required minlength="6">
                        </div>
                        <div class="form-group">
                            <label>Xác nhận mật khẩu mới</label>
                            <input type="password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn-submit"><i class="fas fa-key"></i> Đổi mật khẩu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.profile-menu a[data-tab]').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.profile-menu li').forEach(li => li.classList.remove('active'));
        this.parentElement.classList.add('active');
        document.querySelectorAll('.profile-tab').forEach(tab => tab.classList.remove('active'));
        document.getElementById(this.dataset.tab).classList.add('active');
    });
});
</script>

<?php require_once FRONTEND_PATH . '/includes/footer.php'; ?>
