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
$sql_count_binhluan = "SELECT baiviet.id AS baiviet_id, COUNT(binhluan.id) AS total_binhluan 
                       FROM baiviet 
                       LEFT JOIN binhluan ON baiviet.id = binhluan.baiviet_id 
                       GROUP BY baiviet.id";
$query_count_binhluan = mysqli_query($connect, $sql_count_binhluan);

if ($query_count_binhluan) {
    // Lưu kết quả vào một mảng để dễ dàng truy cập
    $binhluan_counts = array();
    while($row = mysqli_fetch_assoc($query_count_binhluan)) {
        $binhluan_counts[$row['baiviet_id']] = $row['total_binhluan'];
    }
} else {
    // Xử lý lỗi truy vấn
    echo "Lỗi truy vấn: " . mysqli_error($connect);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diễn đàn</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <style>
        .write-post-btn {
                background-color: #007bff; /* Màu nền */
                color: white; /* Màu chữ */
                border: none; /* Loại bỏ đường viền */
                padding:8px 15px; /* Độ lớn của nút */
                border-radius: 3px; /* Bo tròn góc */
                cursor: pointer; /* Con trỏ tay khi rê chuột vào */
                margin: 3px;
                transition: background-color 0.3s; /* Hiệu ứng chuyển đổi màu nền */
            }
    </style>
</head>
<body>
    <div class="logo"><h1>Diễn đàn ô tô</h1></div>
    <header>
        <div class="header-container">
            <div class="nav">
                <ul>
                    <li><a href="DienDan.php">Diễn đàn</a></li>
                    <li><a href="TinTuc.php">Tin tức</a></li>
                    <li><a href="ThanhVien.php">Thành viên</a></li>
                    <li><a href="#">Tìm kiếm</a></li>
                    <li><a href="DangXuat.php">Đăng xuất</a></li>
                </ul>
            </div>
        </div>
    </header>
   
    <main>
        <div class="container">
            <!-- Phần hiển thị các bài viết -->
            <section>
                <div class="card">
                    <div class="card-header">
                        <div>Tin mới diễn đàn </div>
                        <div class="write-post-btn-container">
                        <button class="write-post-btn" onclick="location.href='thembaiviet.php'">Viết bài</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Chủ đề</th>
                                    <th>Chuyên mục</th>
                                    <th>Bình luận</th>
                                    <th>Lượt thích</th>
                                    <th>Tác giả</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    
                                    if($query_baiviet){
                                        while($row = mysqli_fetch_assoc($query_baiviet)){ ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><a href="baiviet.php?chude_id=<?php echo $row['id']; ?>"><?php echo isset($row['chude']) ? $row['chude'] : 'Không xác định'; ?></a></td>
                                                <td><?php echo $row['ten_chuyenmuc']; ?></td>
                                                <td><?php echo isset($binhluan_counts[$row['id']]) ? $binhluan_counts[$row['id']] : 0; ?></td>
                                                <td><?php echo isset($row['luotthich']) ? $row['luotthich'] : 0; ?></td>
                                                <td><?php echo $row['tacgia']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Phần hiển thị các chuyên mục từ bảng Diễn Đàn -->
           
            <section>
                <div class="card">
                    <div class="card-header">
                        <div>Diễn đàn chính</div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Chuyên mục</th>
                                    <th>Bài viết</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if($query_diendan_chinh){
                                        while($row = mysqli_fetch_assoc($query_diendan_chinh)){ ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo isset($row['ten']) ? $row['ten'] : 'Không xác định'; ?></td>
                                                <td><?php echo isset($baiviet_counts[$row['id']]) ? $baiviet_counts[$row['id']] : 0; ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>


            <section>
                <div class="card">
                    <div class="card-header">
                        <div>Tin Tức</div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Chuyên mục</th>
                                    <th>Bài viết</th>
                                 
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if($query_tin_tuc){
                                        while($row = mysqli_fetch_assoc($query_tin_tuc)){ ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo isset($row['ten']) ? $row['ten'] : 'Không xác định'; ?></td>
                                                <td><?php echo isset($baiviet_counts[$row['id']]) ? $baiviet_counts[$row['id']] : 0; ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>


            <section>
                <div class="card">
                    <div class="card-header">
                        <div>Kiến thức</div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Chuyên mục</th>
                                    <th>Bài viết</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if($query_kien_thuc){
                                        while($row = mysqli_fetch_assoc($query_kien_thuc)){ ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo isset($row['ten']) ? $row['ten'] : 'Không xác định'; ?></td>
                                                <td><?php echo isset($baiviet_counts[$row['id']]) ? $baiviet_counts[$row['id']] : 0; ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section>
                <div class="card">
                    <div class="card-header">
                        <div>Mua bán</div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Chuyên mục</th>
                                    <th>Bài viết</th>
                
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if($query_mua_ban){
                                        while($row = mysqli_fetch_assoc($query_mua_ban)){ ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo isset($row['ten']) ? $row['ten'] : 'Không xác định'; ?></td>
                                                <td><?php echo isset($baiviet_counts[$row['id']]) ? $baiviet_counts[$row['id']] : 0; ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section>
                <div class="card">
                    <div class="card-header">
                        <div>Giải trí</div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Chuyên mục</th>
                                    <th>Bài viết</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if($query_giai_tri){
                                        while($row = mysqli_fetch_assoc($query_giai_tri)){ ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo isset($row['ten']) ? $row['ten'] : 'Không xác định'; ?></td>
                                                <td><?php echo isset($baiviet_counts[$row['id']]) ? $baiviet_counts[$row['id']] : 0; ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

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