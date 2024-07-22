<?php
session_start();
require_once('/xampp/htdocs/webdacs/BE/ketnoi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dangnhap'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM nguoidung WHERE email='$email'";
    $result = $connect->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
                $_SESSION['username'] = $row['username'];
                $_SESSION['nguoidung_id'] = $row['id'];
                echo "Đăng nhập thành công!";
                // Chuyển hướng người dùng đến trang chủ hoặc trang nào đó sau khi đăng nhập thành công
                header("Location: DienDan.php");
                exit();
        } else {
                echo "Sai mật khẩu!";
        }
    } else {
            echo "Không tìm thấy email!";
    }


$connect->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/DangNhap.css">
    <title>Diễn đàn ô tô</title>
    <script src="JS/javascript.js"></script>
</head>
<body>
    <h2>Đăng nhập/ Đăng ký</h2>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="DangKy.php" method="POST">
                <h1>Tạo tài khoản</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>Sử dụng email của bạn để đăng ký</span>
                <input type="text" name="username" placeholder="Họ và tên" required />
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <button name="dangky">Đăng ký</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="DangNhap.php" method="POST">
                <h1>Đăng Nhập</h1>
                <div class="social-container">
                    <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <span>Tài khoản của bạn</span>
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <a href="#">Quên mật khẩu?</a>
                <button name="dangnhap">Đăng nhập</button>
                <?php if (isset($error_msg)) { ?>
                    <p><?php echo $error_msg; ?></p>
                <?php } ?>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>Vui lòng nhập thông tin đăng nhập</p>
                    <button class="ghost" id="signIn">Đăng Nhập</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Chào mừng đến với diễn đàn ô tô!</h1>
                    <p>Hãy nhập thông tin cá nhân của bạn</p>
                    <button class="ghost" id="signUp">Đăng ký</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
