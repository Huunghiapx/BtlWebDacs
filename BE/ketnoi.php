<?php
$servername = "localhost";  // Thay đổi nếu không dùng localhost
$username = "root";         // Thay bằng tên người dùng của bạn
$password = "";             // Thay bằng mật khẩu của bạn
$dbname = "webdacs";   // Thay bằng tên cơ sở dữ liệu của bạn

// Tạo kết nối
$connect = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($connect->connect_error) {
    die("Kết nối thất bại: " . $connect->connect_error);
}
echo "";
?>