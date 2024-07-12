<?php 
require_once('/xampp/htdocs/webdacs/BE/ketnoi.php');

// Lấy danh sách chuyên mục
$sql_chuyenmuc = "SELECT * FROM chuyenmuc";
$query_chuyenmuc = mysqli_query($connect, $sql_chuyenmuc);

// Kiểm tra nếu form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $chude = mysqli_real_escape_string($connect, $_POST['chude']);
    $noidung = mysqli_real_escape_string($connect, $_POST['noidung']);
    $tacgia = mysqli_real_escape_string($connect, $_POST['tacgia']);
    $chuyenmuc_id = mysqli_real_escape_string($connect, $_POST['chuyenmuc_id']);

    // Chuẩn bị câu lệnh SQL
    $sql = "INSERT INTO baiviet (chude, noidung, tacgia, chuyenmuc_id) VALUES ('$chude', '$noidung', '$tacgia', '$chuyenmuc_id')";

    // Thực thi câu lệnh SQL
    if (mysqli_query($connect, $sql)) {
        header("Location: diendan.php"); // Chuyển hướng đến trang thành công
        exit();
    } else {
        echo "Lỗi: " . $sql . "<br>" . mysqli_error($connect); // Hiển thị thông báo lỗi
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Bài Viết</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 600px; /* Tăng chiều rộng của form */
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #666;
        }
        input[type="text"], textarea, select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
        }
        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thêm Bài Viết Mới</h2>
        <form action="thembaiviet.php" method="post">
            <label for="chude">Chủ đề:</label>
            <input type="text" id="chude" name="chude" required>
            <label for="noidung">Nội dung:</label>
            <textarea id="noidung" name="noidung" rows="4" required></textarea>
            <label for="tacgia">Tác giả:</label>
            <input type="text" id="tacgia" name="tacgia" required>
            <label for="chuyenmuc">Chuyên mục:</label>
            <select name="chuyenmuc_id" id="chuyenmuc" required>
                <?php while ($row = mysqli_fetch_assoc($query_chuyenmuc)) { ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['ten']; ?></option>
                <?php } ?>
            </select>
            <input type="submit" value="Thêm Bài Viết">
        </form>
    </div>
</body>
</html>
