<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="DangNhap.css">
</head>
<style>
.dky-qmk {
    display:flex;
    justify-content: space-around;
    margin-top: 20px;
    text-align: none;
    }
</style>
<body>
    <?php
        include "connect.php";
        session_start();
        if(isset($_POST["dangnhap"]) ){
            $taikhoan = $_POST["taikhoan"];
            $matkhau = $_POST["matkhau"];
            
            
                
            $sql = "SELECT * FROM thanhvien WHERE taikhoan='$taikhoan' and matkhau='$matkhau' ";
            
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0) 
            {
                $_SESSION['taikhoan'] = $taikhoan;
                header("location:TrangChu.php");

            } else {    
                echo"Tài khoản hoặc mật khẩu sai";
            }

        }
    ?>


   <div class="container-login">
        
        <h1>Đăng nhập</h1>
        <form action="DangNhap.php" method="post">
            <label>Tên tài khoản</label>
            <input type="text" name="taikhoan" placeholder="Nhập tên tài khoản">

            <label>Mật khẩu</label>
            <input type="password" name="matkhau" placeholder="Nhập mật khẩu">

            <button type="submit" name="dangnhap">Đăng nhập</button>
            
        </form>
        <div class="dky-qmk">
            <div><a href="DangKy.php">Đăng ký</a></div>
            <div><a href="DoiMatKhau.php">Đổi mật khẩu</a></div>
        </div>
        
   </div>
</body>
</html>