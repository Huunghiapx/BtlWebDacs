<?php 
    require_once('/xampp/htdocs/webdacs/BE/ketnoi.php');

    $sql_binhluan = "SELECT * FROM binhluan";
    $query_binhluan = mysqli_query($connect, $sql_binhluan);

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve data from the form
    
        $noidung = mysqli_real_escape_string($connect, $_POST['noidung']);
        $nguoidung_id = mysqli_real_escape_string($connect, $_POST['nguoidung_id']);
       

        // Prepare SQL statement
        $sql = "INSERT INTO binhluan (nguoidung_id, noidung) VALUES ('$nguoidung_id', '$noidung')";

        // Execute SQL statement
        if (mysqli_query($connect, $sql)) {
            header("Location: baiviet.php"); // Redirect to success page
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
    <title>Tra loi Bài Viết</title>
</head>
<body>
    <h2>Tra loi bai Mới</h2>
 
                                <div id="replyForm" class="reply-form">
                                    <form action="traloi.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo $chude_id; ?>">
                                        <div>
                                            <label for="nguoidung_id">Tác giả:</label><br>
                                            <input type="text" id="nguoidung_id" name="nguoidung_id" required><br>
                                        </div>
                                        <div>
                                            <label for="noidung">Nội dung:</label><br>
                                            <textarea id="noidung" name="noidung" rows="4" cols="50" required></textarea><br>
                                        </div>
                                        <input type="submit" value="Gửi">
                                    </form>
                                </div>
</body>
</html>
