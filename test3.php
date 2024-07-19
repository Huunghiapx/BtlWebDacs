<?php
require_once('/xampp/htdocs/webdacs/BE/ketnoi.php');
session_start();

// Thêm debug vào hàm handleLike
function handleLike($connect) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['id'])) {
        $id = intval($_POST['id']);
      
        if ($_POST['action'] == 'like') {
            $sql_update_likes = "UPDATE baiviet SET luotthich = luotthich + 1 WHERE id = ?";
        } elseif ($_POST['action'] == 'like_comment') {
            $sql_update_likes = "UPDATE binhluan SET thichbinhluan = thichbinhluan + 1 WHERE id = ?";
        } elseif ($_POST['action'] == 'like_comment_rep') {
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
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
        if ($_POST['action'] == 'edit_post' && isset($_POST['post_id']) && isset($_POST['edit_noidung'])) {
            $post_id = intval($_POST['post_id']);
            $edit_noidung = $_POST['edit_noidung'];

            $sql_update_post = "UPDATE baiviet SET noidung = ? WHERE id = ?";
            $stmt_update_post = $connect->prepare($sql_update_post);
            $stmt_update_post->bind_param('si', $edit_noidung, $post_id);

            if (!$stmt_update_post->execute()) {
                die('Lỗi khi cập nhật bài viết: ' . $stmt_update_post->error);
            }
        } elseif ($_POST['action'] == 'edit_comment' && isset($_POST['comment_id']) && isset($_POST['edit_comment_content'])) {
            $comment_id = intval($_POST['comment_id']);
            $edit_comment_content = $_POST['edit_comment_content'];

            $sql_update_comment = "UPDATE binhluan SET noidung = ? WHERE id = ?";
            $stmt_update_comment = $connect->prepare($sql_update_comment);
            $stmt_update_comment->bind_param('si', $edit_comment_content, $comment_id);

            if (!$stmt_update_comment->execute()) {
                die('Lỗi khi cập nhật bình luận: ' . $stmt_update_comment->error);
            }
        } elseif ($_POST['action'] == 'edit_reply' && isset($_POST['reply_id']) && isset($_POST['edit_reply_content'])) {
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
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
        if ($_POST['action'] == 'delete_post' && isset($_POST['post_id'])) {
            $post_id = intval($_POST['post_id']);

            $sql_delete_post = "DELETE FROM baiviet WHERE id = ?";
            $stmt_delete_post = $connect->prepare($sql_delete_post);
            $stmt_delete_post->bind_param('i', $post_id);

            if (!$stmt_delete_post->execute()) {
                die('Lỗi khi xóa bài viết: ' . $stmt_delete_post->error);
            }
        } elseif ($_POST['action'] == 'delete_comment' && isset($_POST['comment_id'])) {
            $comment_id = intval($_POST['comment_id']);

            $sql_delete_comment = "DELETE FROM binhluan WHERE id = ?";
            $stmt_delete_comment = $connect->prepare($sql_delete_comment);
            $stmt_delete_comment->bind_param('i', $comment_id);

            if (!$stmt_delete_comment->execute()) {
                die('Lỗi khi xóa bình luận: ' . $stmt_delete_comment->error);
            }
        } elseif ($_POST['action'] == 'delete_reply' && isset($_POST['reply_id'])) {
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
            echo "<h2>{$baiviet['tieude']}</h2>";
            echo "<p>{$baiviet['noidung']}</p>";
            echo "<p>Luot thich: {$baiviet['luotthich']}</p>";
            
            if ($_SESSION['nguoidung_id'] == $baiviet['nguoidung_id']) {
                echo "
                <form method='post'>
                    <input type='hidden' name='post_id' value='{$baiviet['id']}'>
                    <textarea name='edit_noidung'>{$baiviet['noidung']}</textarea>
                    <button type='submit' name='action' value='edit_post'>Sửa bài viết</button>
                    <button type='submit' name='action' value='delete_post'>Xóa bài viết</button>
                </form>
                ";
            }
            echo "<form method='post'>
                    <input type='hidden' name='id' value='{$baiviet['id']}'>
                    <button type='submit' name='action' value='like'>Thích</button>
                  </form>";

            echo "<h3>Bình luận</h3>";

            $sql_binhluan = "SELECT * FROM binhluan WHERE baiviet_id = ?";
            $stmt_binhluan = $connect->prepare($sql_binhluan);
            $stmt_binhluan->bind_param('i', $chude_id);
            $stmt_binhluan->execute();
            $result_binhluan = $stmt_binhluan->get_result();

            if ($result_binhluan && $result_binhluan->num_rows > 0) {
                while ($binhluan = $result_binhluan->fetch_assoc()) {
                    echo "<p>{$binhluan['noidung']}</p>";
                    echo "<p>Thích bình luận: {$binhluan['thichbinhluan']}</p>";
                    if ($_SESSION['nguoidung_id'] == $binhluan['nguoidung_id']) {
                        echo "
                        <form method='post'>
                            <input type='hidden' name='comment_id' value='{$binhluan['id']}'>
                            <textarea name='edit_comment_content'>{$binhluan['noidung']}</textarea>
                            <button type='submit' name='action' value='edit_comment'>Sửa bình luận</button>
                            <button type='submit' name='action' value='delete_comment'>Xóa bình luận</button>
                        </form>
                        ";
                    }
                    echo "<form method='post'>
                            <input type='hidden' name='id' value='{$binhluan['id']}'>
                            <button type='submit' name='action' value='like_comment'>Thích bình luận</button>
                          </form>";

                    echo "<h4>Trả lời bình luận</h4>";
                    $sql_traloi = "SELECT * FROM traloi WHERE binhluan_id = ?";
                    $stmt_traloi = $connect->prepare($sql_traloi);
                    $stmt_traloi->bind_param('i', $binhluan['id']);
                    $stmt_traloi->execute();
                    $result_traloi = $stmt_traloi->get_result();

                    if ($result_traloi && $result_traloi->num_rows > 0) {
                        while ($traloi = $result_traloi->fetch_assoc()) {
                            echo "<p>{$traloi['noidung']}</p>";
                            echo "<p>Thích trả lời: {$traloi['thichtraloi']}</p>";
                            if ($_SESSION['nguoidung_id'] == $traloi['nguoidung_id']) {
                                echo "
                                <form method='post'>
                                    <input type='hidden' name='reply_id' value='{$traloi['id']}'>
                                    <textarea name='edit_reply_content'>{$traloi['noidung']}</textarea>
                                    <button type='submit' name='action' value='edit_reply'>Sửa trả lời</button>
                                    <button type='submit' name='action' value='delete_reply'>Xóa trả lời</button>
                                </form>
                                ";
                            }
                            echo "<form method='post'>
                                    <input type='hidden' name='id' value='{$traloi['id']}'>
                                    <button type='submit' name='action' value='like_comment_rep'>Thích trả lời</button>
                                  </form>";
                        }
                    }

                    echo "<form method='post'>
                            <input type='hidden' name='binhluan_id' value='{$binhluan['id']}'>
                            <textarea name='reply_content'></textarea>
                            <button type='submit'>Trả lời</button>
                          </form>";
                }
            } else {
                echo "Chưa có bình luận.";
            }

            echo "<h3>Thêm bình luận mới</h3>";
            echo "<form method='post'>
                    <input type='hidden' name='baiviet_id' value='{$chude_id}'>
                    <textarea name='noidung'></textarea>
                    <button type='submit'>Gửi bình luận</button>
                  </form>";
        } else {
            echo "Không tìm thấy bài viết.";
        }
    } else {
        echo "Không có ID bài viết.";
    }
}

// Gọi các hàm xử lý khi gửi form
handleLike($connect);
handleEdit($connect);
handleNewComment($connect);
handleDelete($connect);
handleReplyComment($connect);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết chủ đề</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="CSS/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php displayPostAndComments($connect); ?>
</body>
</html>
