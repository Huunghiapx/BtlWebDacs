<?php
require_once('/xampp/htdocs/webdacs/BE/ketnoi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dangky'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password =  $_POST['password'] ;

    $sql = "INSERT INTO nguoidung (username, email, password) VALUES ('$username', '$email', '$password')";
    
    if ($connect->query($sql) === TRUE) {
        
        echo "Tài khoản đã được tạo thành công!";
        header("Location: Dangnhap.php");
    } else {
        echo "Lỗi: " . $sql . "<br>" . $connect->error;
    }
    
    $connect->close();
}
?>
