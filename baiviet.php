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
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title><?php echo htmlspecialchars($baiviet['chude']); ?></title>
                <link rel="stylesheet" href="CSS/styles.css">
            </head>
            <body>
                <div class="container">
                    <h2><?php echo htmlspecialchars($baiviet['chude']); ?></h2>
                    <p><?php echo htmlspecialchars($baiviet['noidung']); ?></p>
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
?>
