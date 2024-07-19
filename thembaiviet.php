<?php 
require_once('/xampp/htdocs/webdacs/BE/ketnoi.php');

// Lấy danh sách chuyên mục
$sql_chuyenmuc = "SELECT * FROM chuyenmuc";
$query_chuyenmuc = mysqli_query($connect, $sql_chuyenmuc);

// Bắt đầu session
session_start();
if (!isset($_SESSION['nguoidung_id'])) {
    header("Location: dangnhap.php");
    exit();
}
$nguoidung_id = $_SESSION['nguoidung_id']; // Lấy ID người dùng từ session

// Lấy username từ cơ sở dữ liệu
$sql_get_username = "SELECT username FROM nguoidung WHERE id = ?";
$stmt_get_username = $connect->prepare($sql_get_username);
$stmt_get_username->bind_param('i', $nguoidung_id);
$stmt_get_username->execute();
$result_get_username = $stmt_get_username->get_result();
if ($result_get_username->num_rows == 0) {
    die('Lỗi: Người dùng không tồn tại.');
}
$row = $result_get_username->fetch_assoc();
$username = $row['username'];

// Kiểm tra nếu form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $chude = mysqli_real_escape_string($connect, $_POST['chude']);
    $noidung = mysqli_real_escape_string($connect, $_POST['noidung']);
    $chuyenmuc_id = mysqli_real_escape_string($connect, $_POST['chuyenmuc_id']);

    // Chuẩn bị câu lệnh SQL để thêm bài viết vào cơ sở dữ liệu
    $sql_add_post = "INSERT INTO baiviet (chude, noidung, tacgia, chuyenmuc_id) VALUES (?, ?, ?, ?)";
    $stmt_add_post = $connect->prepare($sql_add_post);

    if ($stmt_add_post === false) {
        die('Lỗi khi chuẩn bị câu lệnh SQL: ' . $connect->error);
    }
    $stmt_add_post->bind_param('sssi', $chude, $noidung, $username, $chuyenmuc_id);

    // Thực thi câu lệnh SQL để thêm bài viết
    if (!$stmt_add_post->execute()) {
        die('Lỗi khi thực thi câu lệnh SQL: ' . $stmt_add_post->error);
    } else {
        header("Location: diendan.php"); // Chuyển hướng đến trang thành công
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Bài Viết</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <style>
       * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    }

    /* Global Styles */
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        background-color: #f8f8f8;
        color: #333;
    }

    .container {
        max-width: 1000px;
        margin: auto;
        padding: 0 20px;
    }

    /* Header Styles */
    .header-container {
        background-color: #333;
        padding: 20px 0;
    }

    .nav ul {
        list-style-type: none;
        text-align: center;
    }

    .nav ul li {
        display: inline-block;
        margin-right: 20px;
    }

    .nav ul li a {
        color: #fff;
        text-decoration: none;
        font-size: 18px;
    }

    /* Logo Styles */
    .logo {
        text-align: center;
        margin-top: 20px;
    }

    .logo h1 {
        color: #333;
        font-size: 36px;
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
        <h2>Thêm Bài Viết Mới</h2>
        <form action="thembaiviet.php" method="post">
            <label for="chude">Chủ đề:</label>
            <input type="text" id="chude" name="chude" required>
            <label for="noidung">Nội dung:</label>
            <textarea id="noidung" name="noidung" rows="4" required></textarea>
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
