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
.container-login input[type="number"]{
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-sizing: border-box;

}
</style>
<body>
    <?php
      
        include("connect.php");

        if(isset($_POST['btn']) ){

            $taikhoan = $_POST['taikhoan'];
                
            $matkhau = $_POST['matkhau'];

            $level = $_POST['level'];

            // chỉ lấy tên hình ảnh
            $anh = $_FILES['HinhAnh']['name'];
            // lấy đường dẫn ảnh
            $anh_tmp_name = $_FILES['HinhAnh']['tmp_name'];

            $hoten = $_POST['hoten'];

            $namsinh = $_POST['namsinh'];

            $sdt = $_POST['sdt'];

            $diachi = $_POST['diachi'];
                         
            if (empty($taikhoan) || empty($matkhau) || empty($level) || empty($anh) || empty($hoten) || empty($namsinh) || empty($sdt) || empty($diachi) ) {
                echo "Vui lòng điền đầy đủ thông tin vào tất cả các trường.";
            } else {
                // Nếu tất cả các trường đã được điền, thực hiện thêm dữ liệu vào cơ sở dữ liệu
                $sql = "INSERT INTO thanhvien (taikhoan, matkhau, level, anh_tv, hoten, namsinh, sdt, diachi)
                    VALUES ('$taikhoan', '$matkhau', '$level', '$anh', '$hoten', '$namsinh', '$sdt', '$diachi')";
        
                mysqli_query($conn, $sql);
                move_uploaded_file($anh_tmp_name, 'images/' . $anh);

                header("location:DangNhap.php");           
                // Sau khi thêm dữ liệu thành công, bạn có thể thực hiện chuyển hướng hoặc hiển thị thông báo thành công tại đây
            }
        }
    ?>


   <div class="container-login">
        
        <h1>Đăng Ký</h1>
        <form action="DangKy.php" method="post"enctype="multipart/form-data" >

                <label>Tên tài khoản</label>
                <input type="text" name="taikhoan">

                <label>Mật khẩu</label>
                <input type="password" name="matkhau">

                <label>Level</label>
                <input type="number" name="level">

                <label>Ảnh</label>
                <input type="file" name="HinhAnh">

                <label>Họ và tên</label>
                <input type="text" name="hoten">

                <label>Năm sinh</label>
                <input type="number" name="namsinh">
                
                <label>Số Điện Thoại</label>
                <input type="number" name="sdt">

                <label>Địa chỉ</label>
                <input type="text" name="diachi">


            <button type="submit" name="btn">Đăng ký</button>
            
        </form>
        <div class="dky-qmk">
            <div><a href="DangNhap.php">Đăng nhập</a></div>
        </div>
   </div>
</body>
</html>