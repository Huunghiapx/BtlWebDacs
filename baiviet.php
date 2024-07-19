<?php
require_once('/xampp/htdocs/webdacs/BE/ketnoi.php');
session_start();

// Thêm debug vào hàm handleLike
function handleLike($connect) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action_like']) && isset($_POST['id'])) {
        $id = intval($_POST['id']);
      
        if ($_POST['action_like'] == 'like') {
            $sql_update_likes = "UPDATE baiviet SET luotthich = luotthich + 1 WHERE id = ?";
        } elseif ($_POST['action_like'] == 'like_comment') {
            $sql_update_likes = "UPDATE binhluan SET thichbinhluan = thichbinhluan + 1 WHERE id = ?";
        } elseif ($_POST['action_like'] == 'like_comment_rep') {
            $sql_update_likes = "UPDATE traloi SET thichtraloi = thichtraloi + 1 WHERE id = ?";
        }

        echo "SQL Update: $sql_update_likes <br>";

        $stmt_update_likes = $connect->prepare($sql_update_likes);
        $stmt_update_likes->bind_param('i', $id);
        if (!$stmt_update_likes->execute()) {
            die('Lỗi khi cập nhật số lượt thích: ' . $stmt_update_likes->error);
        }
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit;
    }
}

function handleEdit($connect) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action_edit'])) {
        if ($_POST['action_edit'] == 'edit_post' && isset($_POST['post_id']) && isset($_POST['edit_noidung'])) {
            $post_id = intval($_POST['post_id']);
            $edit_noidung = $_POST['edit_noidung'];

            $sql_update_post = "UPDATE baiviet SET noidung = ? WHERE id = ?";
            $stmt_update_post = $connect->prepare($sql_update_post);
            $stmt_update_post->bind_param('si', $edit_noidung, $post_id);

            if (!$stmt_update_post->execute()) {
                die('Lỗi khi cập nhật bài viết: ' . $stmt_update_post->error);
            }
        } elseif ($_POST['action_edit'] == 'edit_comment' && isset($_POST['comment_id']) && isset($_POST['edit_comment_content'])) {
            $comment_id = intval($_POST['comment_id']);
            $edit_comment_content = $_POST['edit_comment_content'];

            $sql_update_comment = "UPDATE binhluan SET noidung = ? WHERE id = ?";
            $stmt_update_comment = $connect->prepare($sql_update_comment);
            $stmt_update_comment->bind_param('si', $edit_comment_content, $comment_id);

            if (!$stmt_update_comment->execute()) {
                die('Lỗi khi cập nhật bình luận: ' . $stmt_update_comment->error);
            }
        } elseif ($_POST['action_edit'] == 'edit_reply' && isset($_POST['reply_id']) && isset($_POST['edit_reply_content'])) {
            $reply_id = intval($_POST['reply_id']);
            $edit_reply_content = $_POST['edit_reply_content'];

            $sql_update_reply = "UPDATE traloi SET noidung = ? WHERE id = ?";
            $stmt_update_reply = $connect->prepare($sql_update_reply);
            $stmt_update_reply->bind_param('si', $edit_reply_content, $reply_id);

            if (!$stmt_update_reply->execute()) {
                die('Lỗi khi cập nhật trả lời: ' . $stmt_update_reply->error);
            }
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
function handleDelete($connect) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action_delete'])) {
        if ($_POST['action_delete'] == 'delete_post' && isset($_POST['post_id'])) {
            $post_id = intval($_POST['post_id']);

            $sql_delete_post = "DELETE FROM baiviet WHERE id = ?";
            $stmt_delete_post = $connect->prepare($sql_delete_post);
            $stmt_delete_post->bind_param('i', $post_id);

            if (!$stmt_delete_post->execute()) {
                die('Lỗi khi xóa bài viết: ' . $stmt_delete_post->error);
            }
        } elseif ($_POST['action_delete'] == 'delete_comment' && isset($_POST['comment_id'])) {
            $comment_id = intval($_POST['comment_id']);

            $sql_delete_comment = "DELETE FROM binhluan WHERE id = ?";
            $stmt_delete_comment = $connect->prepare($sql_delete_comment);
            $stmt_delete_comment->bind_param('i', $comment_id);

            if (!$stmt_delete_comment->execute()) {
                die('Lỗi khi xóa bình luận: ' . $stmt_delete_comment->error);
            }
        } elseif ($_POST['action_delete'] == 'delete_reply' && isset($_POST['reply_id'])) {
            $reply_id = intval($_POST['reply_id']);

            $sql_delete_reply = "DELETE FROM traloi WHERE id = ?";
            $stmt_delete_reply = $connect->prepare($sql_delete_reply);
            $stmt_delete_reply->bind_param('i', $reply_id);

            if (!$stmt_delete_reply->execute()) {
                die('Lỗi khi xóa trả lời: ' . $stmt_delete_reply->error);
            }
        }
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit;
    }
}

// Xử lý khi người dùng gửi form thêm trả lời
function handleReplyComment($connect) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply_content']) && isset($_POST['binhluan_id'])) {
        $binhluan_id = intval($_POST['binhluan_id']);
        $nguoidung_id = $_SESSION['nguoidung_id'];
        $reply_content = $_POST['reply_content'];

        $sql_check_user = "SELECT id FROM nguoidung WHERE id = ?";
        $stmt_check_user = $connect->prepare($sql_check_user);
        $stmt_check_user->bind_param('i', $nguoidung_id);
        $stmt_check_user->execute();
        $result_check_user = $stmt_check_user->get_result();

        if ($result_check_user->num_rows == 0) {
            die('Lỗi: Người dùng không tồn tại.');
        }

        $sql_add_reply = "INSERT INTO traloi (binhluan_id, nguoidung_id, noidung) VALUES (?, ?, ?)";
        $stmt_add_reply = $connect->prepare($sql_add_reply);
        if ($stmt_add_reply === false) {
            die('Lỗi khi chuẩn bị câu lệnh SQL: ' . $connect->error);
        }
        $stmt_add_reply->bind_param('iis', $binhluan_id, $nguoidung_id, $reply_content);

        if (!$stmt_add_reply->execute()) {
            die('Lỗi khi thực thi câu lệnh SQL: ' . $stmt_add_reply->error);
        } else {
            echo 'Thêm trả lời thành công.';
        }
    }
}

// Hàm xử lý cập nhật bài viết, bình luận và trả lời bình luận




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
                <link rel="stylesheet" href="CSS/style2.css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                <style>
                
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
                                <div id="editPostForm" style="display: none;">
                                    <form method="POST" action="">
                                        <textarea rows="4" cols="80" name="edit_noidung" placeholder="Nhập nội dung mới..."><?php echo htmlspecialchars($baiviet['noidung']); ?></textarea>
                                        <input type="hidden" name="action_edit" value="edit_post">
                                        <input type="hidden" name="post_id" value="<?php echo $baiviet['id']; ?>">
                                        <button type="submit">Cập nhật</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-user">
                            <div class="write-post-btn-container">
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?chude_id=' . $chude_id; ?>">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($chude_id); ?>">
                                <input type="hidden" name="action_like" value="like">
                                <button type="submit" class="write-post-btn">
                                    <i class="fas fa-thumbs-up"></i> Like
                                    <?php if ($baiviet['luotthich'] > 0) { ?>       
                                        <?php echo htmlspecialchars($baiviet['luotthich']); ?>
                                    <?php } ?>
                                </button>
                            </form>

                                <button class="write-post-btn" onclick="toggleReplyForm('post')">
                                    <i class="far fa-comment"></i> Trả lời
                                </button>
                                

                                <button class="write-post-btn" onclick="toggleEditPostForm()">Chỉnh sửa</button>
                                
                                <div id="editPostForm" style="display: none;">
                                    <form method="POST" action="">
                                        <textarea name="edit_noidung" placeholder="Nhập nội dung mới..."><?php echo htmlspecialchars($baiviet['noidung']); ?></textarea>
                                        <input type="hidden" name="edit" value="edit_post">
                                        <input type="hidden" name="post_id" value="<?php echo $baiviet['id']; ?>">
                                        <button type="submit">Cập nhật</button>
                                    </form>
                                </div>
                                <form method="POST" action="">
                                    <input type="hidden" name="action_delete" value="delete_post">
                                    <input type="hidden" name="post_id" value="<?php echo $baiviet['id']; ?>">
                                    <button  class="write-post-btn" type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">Xóa</button>
                                </form>
                                <script>
                                function toggleEditPostForm() {
                                    var editForm = document.getElementById('editPostForm');
                                    if (editForm.style.display === 'none') {
                                        editForm.style.display = 'block';
                                    } else {
                                        editForm.style.display = 'none';
                                    }
                                }
                                </script>
                                

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
                                <label for="noidung" ></label><br>
                                <textarea id="noidung" placeholder="Nhập bình luận" name="noidung" rows="4" cols="80" required></textarea><br>
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
                                        <div id="editCommentForm-<?php echo $comment['comment_id']; ?>" style="display: none;">
                                            <form method="POST" action="">
                                                <textarea name="edit_comment_content" placeholder="Nhập nội dung mới..."><?php echo htmlspecialchars($comment['comment_content']); ?></textarea>
                                                <input type="hidden" name="action_edit" value="edit_comment">
                                                <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                                                <button type="submit">Cập nhật</button>
                                            </form>
                                        </div>
                                        <div class="like-comment">
                                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?chude_id=' . $chude_id; ?>">
                                                <input type="hidden" name="id" value="<?php echo $comment['comment_id']; ?>">
                                                <input type="hidden" name="action_like" value="like_comment">
                                                <button class="write-post-btn">
                                                    <i class="fas fa-thumbs-up"></i> Like
                                                    <?php if ($comment['comment_likes'] > 0) { ?>
                                                        <?php echo htmlspecialchars($comment['comment_likes']); ?>
                                                    <?php } ?>
                                                </button>
                                              
                                            </form>
                                            <button class="write-post-btn" onclick="toggleReplyForm(<?php echo $comment['comment_id']; ?>)">
                                                <i class="far fa-comment"></i> Trả lời
                                            </button>
                                            <button  class="write-post-btn" onclick="toggleEditCommentForm(<?php echo $comment['comment_id']; ?>)">Chỉnh sửa</button>

                                            <script>
                                            function toggleEditCommentForm(commentId) {
                                                var editForm = document.getElementById('editCommentForm-' + commentId);
                                                if (editForm.style.display === 'none') {
                                                    editForm.style.display = 'block';
                                                } else {
                                                    editForm.style.display = 'none';
                                                }
                                            }
                                            </script>   
                                           
                                            <form method="POST" action="">
                                                <input type="hidden" name="action_delete" value="delete_comment">
                                                <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                                                <button class="write-post-btn" type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa bình luận này?')">Xóa</button>
                                            </form>
                                        </div>

                                        

                                        <div id="reply-form-<?php echo $comment['comment_id']; ?>" class="reply-form" style="display: none;">
                                            <form action="TraLoiBinhLuan.php" method="post">
                                                <input type="hidden" name="binhluan_id" value="<?php echo $comment['comment_id']; ?>">
                                                <input type="hidden" name="baiviet_id" value="<?php echo $chude_id; ?>">
                                                <input type="hidden" name="nguoidung_id" value="<?php echo $_SESSION['nguoidung_id']; ?>">
                                                <div>
                                                    <label  for="reply_content_<?php echo $comment['comment_id']; ?>"></label><br>
                                                    <textarea placeholder="Trả lời bình luận" id="reply_content_<?php echo $comment['comment_id']; ?>" name="reply_content" rows="3" cols="60" required></textarea><br>
                                                </div>
                                                <input type="submit" value="Gửi">
                                            </form>
                                        </div>

                                        <!-- Hiển thị các câu trả lời -->
                                        <?php
                                        $sql_replies = "SELECT traloi.id AS reply_id, traloi.noidung AS reply_content, traloi.ngaytao AS reply_date, nguoidung.username AS reply_author, traloi.thichtraloi AS reply_likes 
                                                    FROM traloi 
                                                    JOIN nguoidung ON traloi.nguoidung_id = nguoidung.id 
                                                    WHERE traloi.binhluan_id = ?";
                                        $stmt_replies = $connect->prepare($sql_replies);
                                        $stmt_replies->bind_param('i', $comment['comment_id']);
                                        $stmt_replies->execute();
                                        $replies_result = $stmt_replies->get_result();

                                        if ($replies_result && $replies_result->num_rows > 0) {
                                            while ($reply = $replies_result->fetch_assoc()) {
                                                ?>
                                                <div class="rep_comment">
                                                    <div class="reply">
                                                        <p><strong><?php echo htmlspecialchars($reply['reply_author']); ?>:</strong> <?php echo htmlspecialchars($reply['reply_content']); ?></p>
                                                        
                                                    </div>
                                                        <div class="like-comment">
                                                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?chude_id=' . $chude_id; ?>">
                                                            <input type="hidden" name="id" value="<?php echo $reply['reply_id']; ?>">
                                                            <input type="hidden" name="action_like" value="like_comment_rep">
                                                            <button class="write-post-btn">
                                                                <i class="fas fa-thumbs-up"></i> Like
                                                                <?php if ($reply['reply_likes'] > 0) { ?>
                                                                    <?php echo htmlspecialchars($reply['reply_likes']); ?>
                                                                <?php } ?>
                                                            </button>
                                                            
                                                        </form>
                                                        <button class="write-post-btn" onclick="toggleReplyFormReply(<?php echo $comment['comment_id']; ?>)">
                                                            <i class="far fa-comment"></i> Trả lời
                                                        </button>

                                                        <button  class="write-post-btn" onclick="toggleEditReplyForm(<?php echo $reply['reply_id']; ?>)">Chỉnh sửa</button>
                    
                                                        <script>
                                                        function toggleEditReplyForm(replyId) {
                                                            var editForm = document.getElementById('editReplyForm-' + replyId);
                                                            if (editForm.style.display === 'none') {
                                                                editForm.style.display = 'block';
                                                            } else {
                                                                editForm.style.display = 'none';
                                                            }
                                                        }
                                                        </script>
                                                        <script>
                                                            function toggleReplyFormReply(commentId) {
                                                                var replyForm = document.getElementById('reply-form-reply-' + commentId);
                                                                if (replyForm) {
                                                                    replyForm.style.display = (replyForm.style.display === 'none' || replyForm.style.display === '') ? 'block' : 'none';
                                                                }
                                                            }                                                  
                                                         </script>

                                                        <form method="POST" action="">
                                                            <input type="hidden" name="action_delete" value="delete_reply">
                                                            <input type="hidden" name="reply_id" value="<?php echo $reply['reply_id']; ?>">
                                                            <button class="write-post-btn" type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa trả lời này?')">Xóa</button>
                                                        </form>
                                                        <small><?php echo htmlspecialchars($reply['reply_date']); ?></small>
                                                    </div>
                                                    <div id="reply-form-reply-<?php echo $comment['comment_id']; ?>" class="reply-form-reply" style="display: none;">
                                                        <form action="TraLoiBinhLuan.php" method="post">
                                                            <input type="hidden" name="binhluan_id" value="<?php echo $comment['comment_id']; ?>">
                                                            <input type="hidden" name="baiviet_id" value="<?php echo $chude_id; ?>">
                                                            <input type="hidden" name="nguoidung_id" value="<?php echo $_SESSION['nguoidung_id']; ?>">
                                                            <div>
                                                                <label for="reply_content_<?php echo $comment['comment_id']; ?>"></label><br>
                                                                <textarea placeholder="Trả lời bình luận" id="reply_content_<?php echo $comment['comment_id']; ?>" name="reply_content" rows="3" cols="60" required></textarea><br>
                                                            </div>
                                                            <input type="submit" value="Gửi">
                                                        </form>
                                                    </div>
                                                

                                                    <div id="editReplyForm-<?php echo $reply['reply_id']; ?>" style="display: none;">
                                                        <form method="POST" action="">
                                                            <textarea name="edit_reply_content" placeholder="Nhập nội dung mới..."><?php echo htmlspecialchars($reply['reply_content']); ?></textarea>
                                                            <input type="hidden" name="action_edit" value="edit_reply">
                                                            <input type="hidden" name="reply_id" value="<?php echo $reply['reply_id']; ?>">
                                                            <button type="submit">Cập nhật</button>
                                                        </form>
                                                    </div>
                                                   
                                                </div>

                                                        
                                                

                                                <?php
                                            }
                                        }
                                        ?>
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
// Xử lý cập nhật bài viết, bình luận và trả lời bình luận
handleEdit($connect);
handleLike($connect);
handleDelete($connect);
handleReplyComment($connect);
handleNewComment($connect);

displayPostAndComments($connect);
?>
<?php 

// Hiển thị bài viết và bình luận

?>