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
                <div class="card">
                    <div class="card-header">
                        <div>Tin mới diễn đàn </div>
                        <div class="write-post-btn-container">
                           <a class="write-post-btn" href="Thembaiviet.php">Viết bài</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            
                        </table>
                    </div>
                </div>
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

///
<?php
require_once('/xampp/htdocs/webdacs/BE/ketnoi.php');

// Truy vấn dữ liệu từ các bảng
$sql_baiviet = "SELECT baiviet.*, chuyenmuc.ten as ten_chuyenmuc FROM baiviet JOIN chuyenmuc ON baiviet.chuyenmuc_id = chuyenmuc.id";
$query_baiviet = mysqli_query($connect, $sql_baiviet);

// Kiểm tra xem tham số chude_id có được truyền qua URL không
if (isset($_GET['chude_id'])) {
    // Lấy chude_id từ URL và đảm bảo rằng nó là số nguyên
    $chude_id = intval($_GET['chude_id']);

    // Viết truy vấn SQL để lấy thông tin chi tiết về bài viết dựa trên chude_id
    $sql_baiviet_detail = "SELECT * FROM baiviet WHERE id = $chude_id";
    $query_baiviet_detail = mysqli_query($connect, $sql_baiviet_detail);

    // Kiểm tra kết quả truy vấn
    if ($query_baiviet_detail) {
        if (mysqli_num_rows($query_baiviet_detail) > 0) {
            // Hiển thị thông tin chi tiết về bài viết
            $baiviet = mysqli_fetch_assoc($query_baiviet_detail);
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title><?php echo htmlspecialchars($baiviet['chude']); ?></title>
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
                        <section>
                            <div class="card">
                                <div class="card-user"><?php echo htmlspecialchars($baiviet['tacgia']); ?></div>
                                <div class="card-content">
                                    <div class="card-header">
                                        <div><h2><?php echo htmlspecialchars($baiviet['chude']); ?></h2> </div>
                                        <div class="write-post-btn-container">
                                        <a class="write-post-btn" href="traloi.php">Trả lời</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table class="table">
                                        
                                        <p><?php echo htmlspecialchars($baiviet['noidung']); ?></p>
                                            
                                        </table>
                                    </div>                    
                                </div>
                            </div>
                        </section>

                      
                    </div>
               </main>
            </body>
            </html>
            <?php
        } else {
            echo "Không tìm thấy bài viết.";
        }
    } else {
        // In lỗi truy vấn SQL
        echo "Lỗi truy vấn: " . mysqli_error($connect);
    }
} else {
    echo "Chủ đề không được cung cấp.";
}
?>
////
<?php
require_once('/xampp/htdocs/webdacs/BE/ketnoi.php');

// Kiểm tra xem tham số chude_id có được truyền qua URL không
if (isset($_GET['chude_id'])) {
    // Lấy chude_id từ URL và đảm bảo rằng nó là số nguyên
    $chude_id = intval($_GET['chude_id']);

    // Viết truy vấn SQL để lấy thông tin chi tiết về bài viết dựa trên chude_id
    $sql_baiviet_detail = "SELECT * FROM baiviet WHERE id = $chude_id";
    $query_baiviet_detail = mysqli_query($connect, $sql_baiviet_detail);

    // Kiểm tra kết quả truy vấn
    if ($query_baiviet_detail) {
        if (mysqli_num_rows($query_baiviet_detail) > 0) {
            // Hiển thị thông tin chi tiết về bài viết
            $baiviet = mysqli_fetch_assoc($query_baiviet_detail);
            ?>
            <!DOCTYPE html>
            <html lang="vi">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title><?php echo htmlspecialchars($baiviet['chude']); ?></title>
                <link rel="stylesheet" href="CSS/styles.css">
                
                <script>
                    <!-- your JavaScript functions here -->
                </script>
            </head>
            <body>
                <div class="container">
                    <div class="card">
                        <div class="card-user">
                            <p>Tác giả: <?php echo htmlspecialchars($baiviet['tacgia']); ?></p>
                        </div>
                        <div class="card-content">
                            <div class="card-header">
                                <div><h2><?php echo htmlspecialchars($baiviet['chude']); ?></h2></div>
                                <div class="write-post-btn-container">
                                    <button class="write-post-btn" onclick="toggleReplyForm()">Trả lời</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <td><p><?php echo nl2br(htmlspecialchars($baiviet['noidung'])); ?></p></td>
                                    </tr>
                                </table>
                            </div>
                            <div id="replyForm" class="reply-form">
                                <form action="traloi.php" method="post">
                                    <input type="hidden" name="baiviet_id" value="<?php echo $chude_id; ?>">
                                    <div>
                                        <label for="nguoidung_id">Tác giả:</label><br>
                                        <input type="text" id="nguoidung_id" name="nguoidung_id" required><br>
                                    </div>
                                    <div>
                                        <label for="noidung">Nội dung:</label><br>
                                        <textarea id="noidung" name="noidung" rows="4" cols="50" required></textarea><br>
                                    </div>
                                    <input type="submit" value="Gửi">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "Không tìm thấy bài viết.";
        }
    } else {
        // In lỗi truy vấn SQL
        echo "Lỗi truy vấn: " . mysqli_error($connect);
    }
} else {
    echo "Chủ đề không được cung cấp.";
}

// Đóng kết nối cơ sở dữ liệu
$connect->close();
?>

 <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        display: flex;
                        justify-content: center;
                        align-items: flex-start;
                        height: 100vh;
                        padding: 20px;
                    }
                    .card {
                        background: #fff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        display: flex;
                        flex-direction: row;
                        gap: 20px;
                        width: 80%;
                        max-width: 1000px;
                    }
                    .card-user {
                        background: #f9f9f9;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
                        flex: 1;
                        max-width: 250px;
                    }
                    .card-content {
                        background: #fff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        flex: 3;
                    }
                    .card-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        border-bottom: 1px solid #ddd;
                        padding-bottom: 10px;
                        margin-bottom: 10px;
                    }
                    .card-header h2 {
                        margin: 0;
                        font-size: 24px;
                    }
                    .write-post-btn-container {
                        text-align: right;
                    }
                    .write-post-btn {
                        background-color: #4CAF50;
                        color: white;
                        padding: 10px 20px;
                        text-decoration: none;
                        border-radius: 5px;
                        cursor: pointer;
                    }
                    .write-post-btn:hover {
                        background-color: #45a049;
                    }
                    .card-body {
                        margin-top: 10px;
                    }
                    .table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    .table p {
                        margin: 0;
                    }
                    .reply-form {
                        display: none;
                        margin-top: 20px;
                        background: #f9f9f9;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
                    }
                </style>