<?php 
    require_once('/xampp/htdocs/webdacs/BE/ketnoi.php');
    $sql_chuyenmuc = "SELECT * FROM ChuyenMuc";
    $query_chuyenmuc = mysqli_query($connect, $sql_chuyenmuc);

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve data from the form
        $chude = mysqli_real_escape_string($connect, $_POST['chude']);
        $noidung = mysqli_real_escape_string($connect, $_POST['noidung']);
        $tacgia = mysqli_real_escape_string($connect, $_POST['tacgia']);
        $chuyenmuc_id = mysqli_real_escape_string($connect, $_POST['chuyenmuc_id']);

        // Prepare SQL statement
        $sql = "INSERT INTO baiviet (chude, noidung, tacgia, chuyenmuc_id) VALUES ('$chude', '$noidung', '$tacgia', '$chuyenmuc_id')";

        // Execute SQL statement
        if (mysqli_query($connect, $sql)) {
            header("Location: diendan.php"); // Redirect to success page
            exit();
        } else {
            echo "Lỗi: " . $sql . "<br>" . mysqli_error($connect); // Display error message
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Bài Viết</title>
</head>
<body>
    <h2>Thêm Bài Viết Mới</h2>
 
    <form action="thembaiviet.php" method="post">
        <label for="chude">Chủ đề:</label><br>
        <input type="text" id="chude" name="chude" required><br> <!-- Added 'required' attribute -->
        <label for="noidung">Nội dung:</label><br>
        <textarea id="noidung" name="noidung" rows="4" cols="50" required></textarea><br> <!-- Added 'required' attribute -->
        <label for="tacgia">Tác giả:</label><br>
        <input type="text" id="tacgia" name="tacgia" required><br> <!-- Added 'required' attribute -->
        <label for="chuyenmuc">Chuyên mục:</label><br>
        <select name="chuyenmuc_id" id="chuyenmuc" required> <!-- Added 'required' attribute -->
            <?php while ($row = mysqli_fetch_assoc($query_chuyenmuc)) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['ten']; ?></option>
            <?php } ?>
        </select><br>
        <input type="submit" value="Thêm Bài Viết">
    </form>
</body>
</html>
