<?php
session_start();
require_once('/xampp/htdocs/webdacs/BE/ketnoi.php'); // Đảm bảo rằng bạn sử dụng đúng đường dẫn tới file kết nối

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $binhluan_id = intval($_POST['binhluan_id']);
    $baiviet_id = intval($_POST['baiviet_id']);
    $nguoidung_id = $_SESSION['nguoidung_id'];
    $reply_content = $_POST['reply_content'];

    // Kiểm tra người dùng có tồn tại không
    $sql_check_user = "SELECT id FROM nguoidung WHERE id = ?";
    $stmt_check_user = $connect->prepare($sql_check_user);
    $stmt_check_user->bind_param('i', $nguoidung_id);
    $stmt_check_user->execute();
    $result_check_user = $stmt_check_user->get_result();

    if ($result_check_user->num_rows == 0) {
        die('Lỗi: Người dùng không tồn tại.');
    }

    // Thực hiện thêm trả lời vào cơ sở dữ liệu
    $sql_add_reply = "INSERT INTO traloi (binhluan_id, nguoidung_id, noidung) VALUES (?, ?, ?)";
    $stmt_add_reply = $connect->prepare($sql_add_reply);
    if ($stmt_add_reply === false) {
        die('Lỗi khi chuẩn bị câu lệnh SQL: ' . $connect->error);
    }
    $stmt_add_reply->bind_param('iis', $binhluan_id, $nguoidung_id, $reply_content);

    if (!$stmt_add_reply->execute()) {
        die('Lỗi khi thực thi câu lệnh SQL: ' . $stmt_add_reply->error);
    } else {
        // Sau khi thêm trả lời thành công, chuyển hướng người dùng về trang bài viết gốc
        header("Location: baiviet.php?chude_id=$baiviet_id");
        exit();
    }
} else {
    // Nếu không phải là phương thức POST, không làm gì cả hoặc có thể xử lý lỗi tại đây
    die('Phương thức yêu cầu không hợp lệ.');
}
?>
