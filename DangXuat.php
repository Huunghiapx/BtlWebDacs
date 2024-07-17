<?php
// Bắt đầu phiên làm việc
session_start();

// Hủy phiên làm việc bằng cách xóa tất cả các biến session
$_SESSION = array();

// Nếu có cookie được lưu, hủy nó bằng cách đặt thời gian sống là quá khứ
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Hủy phiên làm việc
session_destroy();

// Chuyển hướng người dùng đến trang đăng nhập hoặc trang chủ sau khi đăng xuất thành công
header("Location: DangNhap.php");
exit();
?>
