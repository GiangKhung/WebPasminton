<?php
require_once __DIR__ . '/../backend/config/config.php';

$user = new User();
$user->logout();

header('Location: ' . BASE_URL);
exit;
