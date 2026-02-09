<?php
require_once __DIR__ . '/../backend/config/config.php';

$error = '';

if (User::isLoggedIn()) {
    header('Location: ' . BASE_URL);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Vui lòng nhập đầy đủ thông tin!';
    } else {
        $user = new User();
        $result = $user->login($email, $password);
        
        if ($result['success']) {
            $redirect = $_GET['redirect'] ?? BASE_URL;
            header('Location: ' . $redirect);
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
        <div class="auth-box">
            <h2>Đăng nhập</h2>
            
            <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['registered'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> Đăng ký thành công! Vui lòng đăng nhập.
            </div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" placeholder="Nhập email của bạn" 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Mật khẩu</label>
                    <div class="password-input">
                        <input type="password" name="password" id="password" placeholder="Nhập mật khẩu" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember"> Ghi nhớ đăng nhập
                    </label>
                    <a href="forgot-password.php" class="forgot-link">Quên mật khẩu?</a>
                </div>
                
                <button type="submit" class="btn-submit">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                </button>
            </form>
            
            <div class="auth-footer">
                <p>Bạn chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
            </div>
            
            <div class="social-login">
                <p>Hoặc đăng nhập bằng</p>
                <div class="social-buttons">
                    <button class="btn-social btn-facebook"><i class="fab fa-facebook-f"></i> Facebook</button>
                    <?php
                    // Tạo URL đăng nhập Google
                    $googleAuthUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
                        'client_id' => GOOGLE_CLIENT_ID,
                        'redirect_uri' => GOOGLE_REDIRECT_URI,
                        'response_type' => 'code',
                        'scope' => 'email profile',
                        'access_type' => 'online'
                    ]);
                    // Lưu redirect URL
                    $_SESSION['login_redirect'] = $_GET['redirect'] ?? BASE_URL;
                    ?>
                    <a href="<?= htmlspecialchars($googleAuthUrl) ?>" class="btn-social btn-google">
                        <i class="fab fa-google"></i> Google
                    </a>
                </div>
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
