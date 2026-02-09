<?php
// Cấu hình đường dẫn
define('ROOT_PATH', dirname(dirname(__DIR__)));
define('BACKEND_PATH', ROOT_PATH . '/backend');
define('FRONTEND_PATH', ROOT_PATH . '/frontend');

// Cấu hình Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'vnb_sports');
define('DB_USER', 'root');
define('DB_PASS', '');

// Cấu hình URL
define('BASE_URL', '/shopcaulong/frontend');
define('ASSETS_URL', BASE_URL . '/assets');

// Google OAuth Config
define('GOOGLE_CLIENT_ID', 'YOUR_GOOGLE_CLIENT_ID');
define('GOOGLE_CLIENT_SECRET', 'YOUR_GOOGLE_CLIENT_SECRET');
define('GOOGLE_REDIRECT_URI', 'http://localhost/shopcaulong/frontend/google-callback.php');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoload classes
spl_autoload_register(function ($class) {
    $file = BACKEND_PATH . '/classes/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Kết nối database
function getConnection() {
    static $conn = null;
    if ($conn === null) {
        try {
            $conn = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Kết nối thất bại: " . $e->getMessage());
        }
    }
    return $conn;
}
