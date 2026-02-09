<?php
require_once __DIR__ . '/../backend/config/config.php';

if (isset($_GET['code'])) {
    // Đổi code lấy access token
    $tokenUrl = 'https://oauth2.googleapis.com/token';
    $tokenData = [
        'code' => $_GET['code'],
        'client_id' => GOOGLE_CLIENT_ID,
        'client_secret' => GOOGLE_CLIENT_SECRET,
        'redirect_uri' => GOOGLE_REDIRECT_URI,
        'grant_type' => 'authorization_code'
    ];
    
    $ch = curl_init($tokenUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($tokenData));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $token = json_decode($response, true);
    
    if (isset($token['access_token'])) {
        // Lấy thông tin user từ Google
        $userInfoUrl = 'https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $token['access_token'];
        
        $ch = curl_init($userInfoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $userInfo = curl_exec($ch);
        curl_close($ch);
        
        $googleUser = json_decode($userInfo, true);
        
        if (isset($googleUser['id'])) {
            $user = new User();
            $result = $user->loginWithGoogle($googleUser);
            
            if ($result['success']) {
                $redirect = $_SESSION['login_redirect'] ?? BASE_URL;
                unset($_SESSION['login_redirect']);
                header('Location: ' . $redirect);
                exit;
            } else {
                header('Location: login.php?error=' . urlencode($result['message']));
                exit;
            }
        }
    }
}

// Lỗi
header('Location: login.php?error=' . urlencode('Đăng nhập Google thất bại!'));
exit;
