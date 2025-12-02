<?php
// ====== KẾT NỐI PDO ======
$host = '127.0.0.1';
$dbname = 'dtbhoa';
$username = 'root';
$password = '';
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

// ====== LẤY DANH SÁCH HOA ======
$sql_select = "SELECT * FROM hoa ORDER BY id DESC";
$stmt_select = $pdo->query($sql_select);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách hoa</title>    
</head>
<body>
    <h2>Danh Sách Hoa</h2>
        <p><a href="login.php">Đăng nhập quản trị</a></p>


    <?php
    // ====== HIỂN THỊ DANH SÁCH HOA ======
    while ($row = $stmt_select->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="flower">';
        echo '<h3>' . htmlspecialchars($row['ten_hoa']) . '</h3>';
        echo '<p>' . nl2br(htmlspecialchars($row['mota'])) . '</p>';
        if (!empty($row['hinh_anh']) && file_exists('uploads/' . $row['hinh_anh'])) {
            echo '<img src="uploads/' . htmlspecialchars($row['hinh_anh']) . '" alt="' . htmlspecialchars($row['ten_hoa']) . '">';
        }
        echo '</div>';
    }
    ?>
</body>
</html>
