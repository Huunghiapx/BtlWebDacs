<?php 
require_once('/xampp/htdocs/webdacs/BE/ketnoi.php');

// Truy vấn dữ liệu từ các bảng
$sql_baiviet = "SELECT baiviet.*, chuyenmuc.ten as ten_chuyenmuc FROM baiviet JOIN chuyenmuc ON baiviet.chuyenmuc_id = chuyenmuc.id";
$query_baiviet = mysqli_query($connect, $sql_baiviet);

$sql_diendan = "SELECT * FROM DienDan";
$query_diendan = mysqli_query($connect, $sql_diendan);

$sql_chuyenmuc = "SELECT * FROM chuyenmuc";
$query_chuyenmuc = mysqli_query($connect, $sql_chuyenmuc);

// Truy vấn dữ liệu từ bảng chuyên mục
$sql_diendan_chinh = "SELECT * FROM chuyenmuc WHERE danhmuc_id = 1";
$query_diendan_chinh = mysqli_query($connect, $sql_diendan_chinh);

$sql_tin_tuc = "SELECT * FROM chuyenmuc WHERE danhmuc_id = 2"; // ID của danh mục "Tin Tức"
$query_tin_tuc = mysqli_query($connect, $sql_tin_tuc);

$sql_kien_thuc = "SELECT * FROM chuyenmuc WHERE danhmuc_id = 3"; // ID của danh mục "Kiến Thức"
$query_kien_thuc = mysqli_query($connect, $sql_kien_thuc);

$sql_mua_ban = "SELECT * FROM chuyenmuc WHERE danhmuc_id = 4"; // ID của danh mục "Mua Bán"
$query_mua_ban = mysqli_query($connect, $sql_mua_ban);

$sql_giai_tri = "SELECT * FROM chuyenmuc WHERE danhmuc_id = 5"; // ID của danh mục "Giải Trí"
$query_giai_tri = mysqli_query($connect, $sql_giai_tri);

// Truy vấn số bài viết cho mỗi chuyên mục
$sql_count_baiviet = "SELECT chuyenmuc_id, COUNT(*) AS total_baiviet FROM baiviet GROUP BY chuyenmuc_id";
$query_count_baiviet = mysqli_query($connect, $sql_count_baiviet);

// Lưu kết quả vào một mảng để dễ dàng truy cập
$baiviet_counts = array();
while($row = mysqli_fetch_assoc($query_count_baiviet)) {
    $baiviet_counts[$row['chuyenmuc_id']] = $row['total_baiviet'];
}
// Truy vấn số bình luận cho mỗi chuyên mục
$sql_count_binhluan = "SELECT baiviet.chuyenmuc_id, COUNT(*) AS total_binhluan FROM BinhLuan JOIN baiviet ON BinhLuan.baiviet_id = baiviet.id GROUP BY baiviet.chuyenmuc_id";
$query_count_binhluan = mysqli_query($connect, $sql_count_binhluan);

// Lưu kết quả vào một mảng để dễ dàng truy cập
$binhluan_counts = array();
while($row = mysqli_fetch_assoc($query_count_binhluan)) {
    $binhluan_counts[$row['chuyenmuc_id']] = $row['total_binhluan'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diễn đàn</title>
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
    <div class="logo"><h1>Logo</h1></div>
    <header>
        <div class="header-container">
            <div class="nav">
                <ul>
                    <li><a href="DienDan.php">Diễn đàn</a></li>
                    <li><a href="TinTuc.php">Tin tức</a></li>
                    <li><a href="ThanhVien.php">Thành viên</a></li>
                    <li><a href="#">Tìm kiếm</a></li>
                </ul>
            </div>
        </div>
    </header>
   
    <main>
        <div class="container">
            <!-- Phần hiển thị các bài viết -->
            <section>
              
            </section>

            <!-- Phần hiển thị các chuyên mục từ bảng Diễn Đàn -->
           

            <!-- Các phần hiển thị khác tương tự như trên -->
        </div>
    </main>
   
    <footer>
        <div class="container">
            <p>&copy; Web by Huu Nghia and Minh Hien</p>
        </div>
    </footer>
</body>
</html>

