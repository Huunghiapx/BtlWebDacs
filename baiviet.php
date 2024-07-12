<?php
require_once('/xampp/htdocs/webdacs/BE/ketnoi.php');

// Kiểm tra xử lý khi người dùng gửi form thêm bình luận
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nguoidung_id'], $_POST['noidung'])) {
    // Lấy dữ liệu từ form
    $baiviet_id = intval($_POST['baiviet_id']);
    $nguoidung_id = intval($_POST['nguoidung_id']);
    $noidung = $_POST['noidung'];

    // Kiểm tra xem nguoidung_id có tồn tại trong bảng nguoidung không
    $sql_check_user = "SELECT id FROM nguoidung WHERE id = ?";
    $stmt_check_user = $connect->prepare($sql_check_user);
    $stmt_check_user->bind_param('i', $nguoidung_id);
    $stmt_check_user->execute();
    $result_check_user = $stmt_check_user->get_result();

    if ($result_check_user->num_rows == 0) {
        die('Lỗi: Người dùng không tồn tại.');
    }

    // Chuẩn bị câu lệnh SQL để thêm bình luận vào cơ sở dữ liệu
    $sql_add_comment = "INSERT INTO binhluan (baiviet_id, nguoidung_id, noidung) VALUES (?, ?, ?)";
    $stmt_add_comment = $connect->prepare($sql_add_comment);

    if ($stmt_add_comment === false) {
        die('Lỗi khi chuẩn bị câu lệnh SQL: ' . $connect->error);
    }
    $stmt_add_comment->bind_param('iis', $baiviet_id, $nguoidung_id, $noidung);

    // Thực thi câu lệnh SQL để thêm bình luận
    if (!$stmt_add_comment->execute()) {
        die('Lỗi khi thực thi câu lệnh SQL: ' . $stmt_add_comment->error);
    } else {
        echo 'Thêm bình luận thành công.';
    }
}

// Xử lý khi người dùng truyền chude_id qua URL
if (isset($_GET['chude_id'])) {
    // Lấy chude_id từ URL và đảm bảo rằng nó là số nguyên
    $chude_id = intval($_GET['chude_id']);

    // Viết truy vấn SQL để lấy thông tin chi tiết về bài viết dựa trên chude_id
    $sql_baiviet_detail = "SELECT * FROM baiviet WHERE id = ?";
    $stmt = $connect->prepare($sql_baiviet_detail);
    $stmt->bind_param('i', $chude_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra kết quả truy vấn
    if ($result && $result->num_rows > 0) {
        // Hiển thị thông tin chi tiết về bài viết
        $baiviet = $result->fetch_assoc();

        // Truy vấn để lấy bình luận liên quan đến bài viết
        $sql_comments = "SELECT binhluan.noidung AS comment_content, binhluan.ngaytao AS comment_date, nguoidung.username AS author_name 
                         FROM binhluan 
                         JOIN nguoidung ON binhluan.nguoidung_id = nguoidung.id 
                         WHERE binhluan.baiviet_id = ?";
        $stmt_comments = $connect->prepare($sql_comments);
        $stmt_comments->bind_param('i', $chude_id);
        $stmt_comments->execute();
        $comments_result = $stmt_comments->get_result();
        ?>
        
        <!DOCTYPE html>
        <html lang="vi">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo htmlspecialchars($baiviet['chude']); ?></title>
            <link rel="stylesheet" href="CSS/styles.css">
            <style>
                .card-user {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .card-user .left {
                    margin: 0;
                }

                .card-user .right {
                    margin: 0;
                }
            </style>
            <script>
                function toggleReplyForm() {
                    var replyForm = document.getElementById('replyForm');
                    if (replyForm.style.display === 'none' || replyForm.style.display === '') {
                        replyForm.style.display = 'block';
                    } else {
                        replyForm.style.display = 'none';
                    }
                }
            </script>
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
                            <li><a href="DangNhap.php">Đăng nhập</a></li>
                        </ul>
                    </div>
                </div>
            </header>
            <div class="container">
                <div class="card">
                    <div class="card-user">
                        <p class="left">Tác giả: <?php echo htmlspecialchars($baiviet['tacgia']); ?></p>
                        <p class="right"><small><?php echo htmlspecialchars($baiviet['ngaydang']); ?></small></p>  
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
                        
                    </div>
                </div>
                <div id="replyForm" class="reply-form" style="display: none;">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?chude_id=' . $chude_id; ?>" method="post">
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
                 <!-- Hiển thị bình luận -->
                 <div class="comments-section">
                    <h3>Bình luận</h3>
                    <?php
                    if ($comments_result && $comments_result->num_rows > 0) {
                        while ($comment = $comments_result->fetch_assoc()) {
                            ?>
                            <div class="comment">
                                <p><strong><?php echo htmlspecialchars($comment['author_name']); ?>:</strong> <?php echo nl2br(htmlspecialchars($comment['comment_content'])); ?></p>
                                <p><small><?php echo htmlspecialchars($comment['comment_date']); ?></small></p>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>Chưa có bình luận nào.</p>";
                    }
                    ?>
                </div>
            </div>
            <footer>
                <div class="container">
                    <p>&copy; Web by Huu Nghia and Minh Hien</p>
                </div>
            </footer>
            
        </body>

        </html>
        <?php
    } else {
        echo "Không tìm thấy bài viết.";
    }
} else {
    echo "Chủ đề không được cung cấp.";
}

// Đóng kết nối cơ sở dữ liệu
$connect->close(); 
?>
