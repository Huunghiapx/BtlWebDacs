<?php 
require_once('/xampp/htdocs/webdacs/BE/ketnoi.php');

// Lấy ID chuyên mục từ URL
$chuyenmuc_id = isset($_GET['chuyenmuc_id']) ? intval($_GET['chuyenmuc_id']) : 0;

// Truy vấn các chuyên mục từ bảng chuyenmuc
$sql_chuyenmuc = "SELECT * FROM chuyenmuc";
$query_chuyenmuc = mysqli_query($connect, $sql_chuyenmuc);

// Truy vấn bài viết thuộc chuyên mục được chọn
$sql_baiviet = $chuyenmuc_id ? 
    "SELECT baiviet.*, chuyenmuc.ten as ten_chuyenmuc 
     FROM baiviet 
     JOIN chuyenmuc ON baiviet.chuyenmuc_id = chuyenmuc.id 
     WHERE baiviet.chuyenmuc_id = $chuyenmuc_id" :
    "SELECT baiviet.*, chuyenmuc.ten as ten_chuyenmuc 
     FROM baiviet 
     JOIN chuyenmuc ON baiviet.chuyenmuc_id = chuyenmuc.id";
$query_baiviet = mysqli_query($connect, $sql_baiviet);

// Truy vấn số bài viết cho mỗi chuyên mục
$sql_count_baiviet = "SELECT chuyenmuc_id, COUNT(*) AS total_baiviet FROM baiviet GROUP BY chuyenmuc_id";
$query_count_baiviet = mysqli_query($connect, $sql_count_baiviet);
$baiviet_counts = array();
while($row = mysqli_fetch_assoc($query_count_baiviet)) {
    $baiviet_counts[$row['chuyenmuc_id']] = $row['total_baiviet'];
}

// Truy vấn số bình luận cho mỗi bài viết
$sql_count_binhluan = "SELECT baiviet.id AS baiviet_id, COUNT(*) AS total_binhluan 
                       FROM binhluan 
                       JOIN baiviet ON binhluan.baiviet_id = baiviet.id 
                       GROUP BY baiviet.id";
$query_count_binhluan = mysqli_query($connect, $sql_count_binhluan);
$binhluan_counts = array();
while($row = mysqli_fetch_assoc($query_count_binhluan)) {
    $binhluan_counts[$row['baiviet_id']] = $row['total_binhluan'];
}

$sql_thanhvien = "SELECT * FROM nguoidung";
$query_thanhvien = mysqli_query($connect, $sql_thanhvien);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diễn đàn</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .search-container form {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        .search-container input[type="text"] {
            padding: 10px 40px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 5px;
        }
        .search-container button {
            padding: 8px 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .search-container button:hover {
            background-color: #0056b3;
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
                    <li><a href="Thanhvien.php">Thành Viên</a></li>
                    <li><a href="Dangnhap.php">Đăng xuất</a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="search-container">
        <form action="timkiem.php" method="GET">
            <input type="text" name="query" placeholder="Tìm kiếm..." required>
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <main>
        <div class="container">
            <!-- Phần hiển thị các chuyên mục -->
            <section>
                <div class="card">
                    <div class="card-header">
                        <div>Chuyên mục</div>

                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tên chuyên mục</th>
                                    <th>Số bài viết</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($query_chuyenmuc)) { ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><a href="?chuyenmuc_id=<?php echo $row['id']; ?>" class="post-link">
                                            <?php echo htmlspecialchars($row['ten']); ?>
                                        </a></td>
                                        <td><?php echo isset($baiviet_counts[$row['id']]) ? $baiviet_counts[$row['id']] : 0; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Phần hiển thị các bài viết thuộc chuyên mục -->
            <?php if ($chuyenmuc_id): ?>
            <section>
                <div class="card">
                    <div class="card-header">
                        <div>Bài viết trong chuyên mục</div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Chủ đề</th>
                                    <th>Lượt thích</th>
                                    <th>Bình luận</th>
                                    <th>Chuyên mục</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if ($query_baiviet && mysqli_num_rows($query_baiviet) > 0) {
                                        while ($row = mysqli_fetch_assoc($query_baiviet)) { ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><a href="baiviet.php?chude_id=<?php echo $row['id']; ?>" class="post-link">
                                                    <?php echo isset($row['chude']) ? htmlspecialchars($row['chude']) : 'Không xác định'; ?>
                                                </a></td>
                                                <td><?php echo htmlspecialchars($row['luotthich']); ?></td>
                                                <td><?php echo isset($binhluan_counts[$row['id']]) ? $binhluan_counts[$row['id']] : 0; ?></td>
                                                <td><?php echo htmlspecialchars($row['ten_chuyenmuc']); ?></td>
                                            </tr>
                                        <?php } 
                                    } else { ?>
                                        <tr><td colspan="5">Không có bài viết nào trong chuyên mục này.</td></tr>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <?php endif; ?>

        </div>
    </main>
    <footer>
        <div class="container">
            <p>&copy; Web by Huu Nghia and Minh Hien</p>
        </div>
    </footer>
</body>
</html>
