<?php 
require_once('/xampp/htdocs/webdacs/BE/ketnoi.php');

$query = isset($_GET['query']) ? mysqli_real_escape_string($connect, $_GET['query']) : '';

$sql_timkiem = "SELECT baiviet.*, chuyenmuc.ten AS ten_chuyenmuc 
                FROM baiviet 
                JOIN chuyenmuc ON baiviet.chuyenmuc_id = chuyenmuc.id 
                WHERE baiviet.chude LIKE '%$query%' OR baiviet.noidung LIKE '%$query%'";

$result_timkiem = mysqli_query($connect, $sql_timkiem);

$sql_count_binhluan = "SELECT baiviet.id AS baiviet_id, COUNT(binhluan.id) AS total_binhluan 
                       FROM baiviet 
                       LEFT JOIN binhluan ON baiviet.id = binhluan.baiviet_id 
                       GROUP BY baiviet.id";
$query_count_binhluan = mysqli_query($connect, $sql_count_binhluan);

$binhluan_counts = array();
if ($query_count_binhluan) {
    while($row = mysqli_fetch_assoc($query_count_binhluan)) {
        $binhluan_counts[$row['baiviet_id']] = $row['total_binhluan'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/styles2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
     .search-container form {
            display: flex;
            justify-content: center;
            margin-top: 10px;
            
        }
        
        .search-container input[type="text"] {
            padding: 10px 30px;
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
<body>
    <div class="logo"><h1>Diễn đàn ô tô</h1></div>
    <header>
        <div class="header-container">
            <div class="nav">
                <ul>
                    <li><a href="DienDan.php">Diễn đàn</a></li>
                    <li><a href="TinTuc.php">Tin tức</a></li>
                    <li><a href="ThanhVien.php">Thành viên</a></li>
                    <li><a href="DangXuat.php">Đăng xuất</a></li>
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
    <div class="container">
        <section>
            <div class="card">
                <div class="card-header">
                    <div>Kết quả tìm kiếm cho "<?php echo htmlspecialchars($query); ?>"</div>
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
                            if ($result_timkiem && mysqli_num_rows($result_timkiem) > 0) {
                                while ($row = mysqli_fetch_assoc($result_timkiem)) { ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><a href="baiviet.php?chude_id=<?php echo $row['id']; ?>" class="post-link">
                                            <?php echo htmlspecialchars($row['chude']); ?>
                                        </a></td>
                                        <td><?php echo htmlspecialchars($row['ten_chuyenmuc']); ?></td>
                                        <td><?php echo $binhluan_counts[$row['id']] ?? 0; ?></td>
                                        <td><?php echo htmlspecialchars($row['luotthich']); ?></td>
                                        <td><?php echo htmlspecialchars($row['tacgia']); ?></td>
                                    </tr>
                                <?php } 
                            } else { ?>
                                <tr><td colspan="6">Không tìm thấy bài viết nào.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
