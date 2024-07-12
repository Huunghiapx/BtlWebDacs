<?php
require_once('/xampp/htdocs/webdacs/BE/ketnoi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $baiviet_id = intval($_POST['baiviet_id']);
    $nguoidung_id = mysqli_real_escape_string($connect, $_POST['nguoidung_id']);
    $noidung = mysqli_real_escape_string($connect, $_POST['noidung']);

    // Viết truy vấn SQL để chèn bình luận mới
    $sql_insert_comment = "INSERT INTO binhluan (baiviet_id, nguoidung_id, noidung) VALUES (?, ?, ?)";
    $stmt = $connect->prepare($sql_insert_comment);
    $stmt->bind_param('iss', $baiviet_id, $nguoidung_id, $noidung);

    // Kiểm tra kết quả chèn dữ liệu
    if ($stmt->execute()) {
        header("Location: baiviet.php"); // Redirect to success page
        exit();
    } else {
        echo "Lỗi: " . $stmt->error;
    }
} else {
    echo "Phương thức yêu cầu không hợp lệ.";
}

// Đóng kết nối cơ sở dữ liệu
$connect->close();
?>
