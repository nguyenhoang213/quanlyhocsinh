<?php
session_start();

// Xóa tất cả các phiên
session_unset();
session_destroy();

// Xóa cookie "login_token" nếu có
if (isset($_COOKIE['login_token'])) {
    setcookie('login_token', '', time() - 3600, '/'); // Hết hạn cookie
}

// Chuyển hướng người dùng về trang đăng nhập
header('Location: /login.php');
exit();
?>