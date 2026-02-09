<?php
require_once __DIR__ . '/../backend/config/config.php';

$error = '';
$errors = [];

if (User::isLoggedIn()) {
    header('Location: ' . BASE_URL);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $agree = isset($_POST['agree']);
    
    if (empty($fullname)) $errors['fullname'] = 'Vui lòng nhập họ tên!';
    if (empty($email)) $errors['email'] = 'Vui lòng nhập email!';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Email không hợp lệ!';
    if (empty($phone)) $errors['phone'] = 'Vui lòng nhập số điện thoại!';
    elseif (!preg_match('/^[0-9]{10,11}$/', $phone)) $errors['phone'] = 'Số điện thoại không hợp lệ!';
    if (empty($password)) $errors['password'] = 'Vui lòng nhập mật khẩu!';
    elseif (strlen($password) < 6) $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự!';
    if ($password !== $confirm_password) $errors['confirm_password'] = 'Mật khẩu xác nhận không khớp!';
    if (!$agree) $errors['agree'] = 'Bạn phải đồng ý với điều khoản sử dụng!';
    
    if (empty($errors)) {
        $user = new User();
        $result = $user->register($fullname, $email, $phone, $password);
        
        if ($result['success']) {
            header('Location: login.php?registered=1');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

require_once FRONTEND_PATH . '/includes/header.php';
?>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-box register-box">
            <h2>Đăng ký tài khoản</h2>
            
            <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="form-group <?= isset($errors['fullname']) ? 'has-error' : '' ?>">
                    <label><i class="fas fa-user"></i> Họ và tên <span class="required">*</span></label>
                    <input type="text" name="fullname" placeholder="Nhập họ và tên" value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
                    <?php if (isset($errors['fullname'])): ?><span class="error-text"><?= $errors['fullname'] ?></span><?php endif; ?>
                </div>
                
                <div class="form-group <?= isset($errors['email']) ? 'has-error' : '' ?>">
                    <label><i class="fas fa-envelope"></i> Email <span class="required">*</span></label>
                    <input type="email" name="email" placeholder="Nhập email của bạn" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <?php if (isset($errors['email'])): ?><span class="error-text"><?= $errors['email'] ?></span><?php endif; ?>
                </div>
                
                <div class="form-group <?= isset($errors['phone']) ? 'has-error' : '' ?>">
                    <label><i class="fas fa-phone"></i> Số điện thoại <span class="required">*</span></label>
                    <input type="tel" name="phone" placeholder="Nhập số điện thoại" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                    <?php if (isset($errors['phone'])): ?><span class="error-text"><?= $errors['phone'] ?></span><?php endif; ?>
                </div>
                
                <div class="form-group <?= isset($errors['password']) ? 'has-error' : '' ?>">
                    <label><i class="fas fa-lock"></i> Mật khẩu <span class="required">*</span></label>
                    <div class="password-input">
                        <input type="password" name="password" id="password" placeholder="Nhập mật khẩu (ít nhất 6 ký tự)">
                        <button type="button" class="toggle-password" onclick="togglePassword('password')"><i class="fas fa-eye"></i></button>
                    </div>
                    <?php if (isset($errors['password'])): ?><span class="error-text"><?= $errors['password'] ?></span><?php endif; ?>
                </div>
                
                <div class="form-group <?= isset($errors['confirm_password']) ? 'has-error' : '' ?>">
                    <label><i class="fas fa-lock"></i> Xác nhận mật khẩu <span class="required">*</span></label>
                    <div class="password-input">
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Nhập lại mật khẩu">
                        <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')"><i class="fas fa-eye"></i></button>
                    </div>
                    <?php if (isset($errors['confirm_password'])): ?><span class="error-text"><?= $errors['confirm_password'] ?></span><?php endif; ?>
                </div>
                
                <div class="form-group checkbox-group <?= isset($errors['agree']) ? 'has-error' : '' ?>">
                    <label class="checkbox-label">
                        <input type="checkbox" name="agree" <?= isset($_POST['agree']) ? 'checked' : '' ?>>
                        <span>Tôi đồng ý với <a href="#">Điều khoản sử dụng</a> và <a href="#">Chính sách bảo mật</a></span>
                    </label>
                    <?php if (isset($errors['agree'])): ?><span class="error-text"><?= $errors['agree'] ?></span><?php endif; ?>
                </div>
                
                <button type="submit" class="btn-submit"><i class="fas fa-user-plus"></i> Đăng ký</button>
            </form>
            
            <div class="auth-footer">
                <p>Bạn đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>

<?php require_once FRONTEND_PATH . '/includes/footer.php'; ?>
