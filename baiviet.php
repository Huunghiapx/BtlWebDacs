<?php
require_once('/xampp/htdocs/webdacs/BE/ketnoi.php');
session_start();

// Xử lý khi người dùng nhấn nút "Thích"
function handleLike($connect) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['id'])) {
        $id = intval($_POST['id']);

        if ($_POST['action'] == 'like') {
            $sql_update_likes = "UPDATE baiviet SET luotthich = luotthich + 1 WHERE id = ?";
        } elseif ($_POST['action'] == 'like_comment') {
            $sql_update_likes = "UPDATE binhluan SET thichbinhluan = thichbinhluan + 1 WHERE id = ?";
        }

        $stmt_update_likes = $connect->prepare($sql_update_likes);
        $stmt_update_likes->bind_param('i', $id);
        if (!$stmt_update_likes->execute()) {
            die('Lỗi khi cập nhật số lượt thích: ' . $stmt_update_likes->error);
        }
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit;
    }
}

// Xử lý khi người dùng gửi form thêm bình luận
function handleNewComment($connect) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['noidung']) && isset($_POST['baiviet_id'])) {
        $baiviet_id = intval($_POST['baiviet_id']);
        $nguoidung_id = $_SESSION['nguoidung_id'];
        $noidung = $_POST['noidung'];

        $sql_check_user = "SELECT id FROM nguoidung WHERE id = ?";
        $stmt_check_user = $connect->prepare($sql_check_user);
        $stmt_check_user->bind_param('i', $nguoidung_id);
        $stmt_check_user->execute();
        $result_check_user = $stmt_check_user->get_result();

        if ($result_check_user->num_rows == 0) {
            die('Lỗi: Người dùng không tồn tại.');
        }

        $sql_add_comment = "INSERT INTO binhluan (baiviet_id, nguoidung_id, noidung) VALUES (?, ?, ?)";
        $stmt_add_comment = $connect->prepare($sql_add_comment);
        if ($stmt_add_comment === false) {
            die('Lỗi khi chuẩn bị câu lệnh SQL: ' . $connect->error);
        }
        $stmt_add_comment->bind_param('iis', $baiviet_id, $nguoidung_id, $noidung);

        if (!$stmt_add_comment->execute()) {
            die('Lỗi khi thực thi câu lệnh SQL: ' . $stmt_add_comment->error);
        } else {
            echo 'Thêm bình luận thành công.';
        }
    }
}

// Xử lý hiển thị bài viết và bình luận
function displayPostAndComments($connect) {
    if (isset($_GET['chude_id'])) {
        $chude_id = intval($_GET['chude_id']);

        $sql_baiviet_detail = "SELECT * FROM baiviet WHERE id = ?";
        $stmt = $connect->prepare($sql_baiviet_detail);
        $stmt->bind_param('i', $chude_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $baiviet = $result->fetch_assoc();

            $sql_comments = "SELECT binhluan.id AS comment_id, binhluan.noidung AS comment_content, binhluan.ngaytao AS comment_date, nguoidung.username AS author_name, binhluan.thichbinhluan AS comment_likes 
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
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                <style>
                    /* CSS styles here */
                </style>
                <script>
                    function toggleReplyForm(commentId) {
                        var replyForm = document.getElementById('reply-form-' + commentId);
                        replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
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
                                <li><a href="DangXuat.php">Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                </header>
                <div class="container">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                                <div>
                                    <h2><?php echo htmlspecialchars($baiviet['chude']); ?></h2>
                                </div>
                                <p class="right-card">
                                    <h4>Tác giả: <?php echo htmlspecialchars($baiviet['tacgia']); ?></h4>
                                </p>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <td>
                                            <p><?php echo nl2br(htmlspecialchars($baiviet['noidung'])); ?></p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="card-user">
                            <div class="write-post-btn-container">
                                <button class="write-post-btn" onclick="toggleReplyForm('post')">
                                    <i class="far fa-comment"></i> Trả lời
                                </button>
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?chude_id=' . $chude_id; ?>">
                                    <input type="hidden" name="id" value="<?php echo $chude_id; ?>">
                                    <input type="hidden" name="action" value="like">
                                    <button class="write-post-btn">
                                        <i class="fas fa-thumbs-up"></i> Like
                                    </button>
                                    <?php if ($baiviet['luotthich'] > 0) { ?>
                                        <?php echo htmlspecialchars($baiviet['luotthich']); ?>
                                    <?php } ?>
                                </form>
                            </div>
                            <p class="right">
                                <small><?php echo htmlspecialchars($baiviet['ngaydang']); ?></small>
                            </p>
                        </div>
                    </div>
                    <div id="reply-form-post" class="reply-form" style="display: none;">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?chude_id=' . $chude_id; ?>" method="post">
                            <input type="hidden" name="baiviet_id" value="<?php echo $chude_id; ?>">
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
                                <div class="comment_baiviet">
                                    <div class="box-comment-user">
                                        <div class="left-content">
                                            <h3>User: <?php echo htmlspecialchars($comment['author_name']); ?></h3>
                                        </div>
                                        <div class="right-content">
                                            <h5><?php echo htmlspecialchars($comment['comment_date']); ?></h5>
                                        </div>
                                    </div>
                                    <div class="comment-noidung">
                                        <p><?php echo htmlspecialchars($comment['comment_content']); ?></p>
                                        <div class="like-comment">
                                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?chude_id=' . $chude_id; ?>">
                                                <input type="hidden" name="id" value="<?php echo $comment['comment_id']; ?>">
                                                <input type="hidden" name="action" value="like_comment">
                                                <button class="write-post-btn">
                                                    <i class="fas fa-thumbs-up"></i> Like
                                                </button>
                                                <?php if ($comment['comment_likes'] > 0) { ?>
                                                    <?php echo htmlspecialchars($comment['comment_likes']); ?>
                                                <?php } ?>
                                            </form>
                                            <button class="write-post-btn" onclick="toggleReplyForm(<?php echo $comment['comment_id']; ?>)">
                                                <i class="far fa-comment"></i> Trả lời
                                            </button>
                                        </div>
                                        <div id="reply-form-<?php echo $comment['comment_id']; ?>" class="reply-form" style="display: none;">
                                            <form action="TraLoiBinhLuan.php" method="post">
                                                <input type="hidden" name="binhluan_id" value="<?php echo $comment['comment_id']; ?>">
                                                <input type="hidden" name="baiviet_id" value="<?php echo $chude_id; ?>">
                                                <input type="hidden" name="nguoidung_id" value="<?php echo $_SESSION['nguoidung_id']; ?>">
                                                <div>
                                                    <label for="reply_content_<?php echo $comment['comment_id']; ?>">Nội dung:</label><br>
                                                    <textarea id="reply_content_<?php echo $comment['comment_id']; ?>" name="reply_content" rows="2" cols="40" required></textarea><br>
                                                </div>
                                                <input type="submit" value="Gửi">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<p>Chưa có bình luận nào.</p>';
                        }
                        ?>
                    </div>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo '<p>Bài viết không tồn tại.</p>';
        }
    } else {
        echo '<p>Không tìm thấy chủ đề.</p>';
    }
}

handleLike($connect);
handleNewComment($connect);
displayPostAndComments($connect);
?>
