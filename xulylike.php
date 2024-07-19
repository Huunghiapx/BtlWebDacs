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