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

// ====== THƯ MỤC LƯU ẢNH ======
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// ====== HÀM UPLOAD ẢNH ======
function handleUploadImage($fileInputName, $uploadDir) {
    if (empty($_FILES[$fileInputName]) || $_FILES[$fileInputName]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $f = $_FILES[$fileInputName];
    $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
    $safeName = time() . '_' . bin2hex(random_bytes(6)) . ($ext ? '.' . $ext : '');
    $target = $uploadDir . $safeName;

    if (move_uploaded_file($f['tmp_name'], $target)) {
        return $safeName;
    }
    return null;
}

// ====== THÊM HOA ======
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add') {
    $ten = trim($_POST['name'] ?? '');
    $mota = trim($_POST['description'] ?? '');
    $imageName = handleUploadImage('image', $uploadDir);

    $sql = "INSERT INTO hoa (ten_hoa, mota, hinh_anh)
            VALUES (:ten_hoa, :mota, :hinh_anh)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ten_hoa', $ten);
    $stmt->bindValue(':mota', $mota);
    $stmt->bindValue(':hinh_anh', $imageName);
    $stmt->execute();

    header("Location: admin.php");
    exit;
}

// ====== SỬA HOA ======
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {
    $id = (int)$_POST['id'];
    $ten = trim($_POST['name'] ?? '');
    $mota = trim($_POST['description'] ?? '');
    $newImage = handleUploadImage('image', $uploadDir);

    if ($newImage !== null) {
        $sql = "UPDATE hoa SET ten_hoa = :ten_hoa, mota = :mota, hinh_anh = :hinh_anh WHERE id = :id";
    } else {
        $sql = "UPDATE hoa SET ten_hoa = :ten_hoa, mota = :mota WHERE id = :id";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ten_hoa', $ten);
    $stmt->bindValue(':mota', $mota);

    if ($newImage !== null) {
        $stmt->bindValue(':hinh_anh', $newImage);
    }
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    header("Location: admin.php");
    exit;
}

// ====== XÓA HOA ======
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    // lấy ảnh cũ để xoá file
    $stmtImg = $pdo->prepare("SELECT hinh_anh FROM hoa WHERE id = :id");
    $stmtImg->execute([':id' => $id]);
    $row = $stmtImg->fetch(PDO::FETCH_ASSOC);

    if (!empty($row['hinh_anh'])) {
        $path = $uploadDir . $row['hinh_anh'];
        if (is_file($path)) @unlink($path);
    }

    $stmt = $pdo->prepare("DELETE FROM hoa WHERE id = :id");
    $stmt->execute([':id' => $id]);

    header("Location: admin.php");
    exit;
}

// ====== LẤY DANH SÁCH HOA ======
$stmt = $pdo->query("SELECT * FROM hoa ORDER BY id DESC");
$flowers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quản trị hoa</title>
</head>
<body>

<h2>Thêm hoa mới</h2>
<a href="user.php">Xem trang người dùng</a>

<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add">

    Tên hoa: <input type="text" name="name" required><br><br>
    Mô tả: <textarea name="description"></textarea><br><br>
    Ảnh: <input type="file" name="image" accept="image/*"><br><br>

    <button type="submit">Thêm</button>
</form>

<hr>

<h2>Danh sách hoa</h2>
<table border="1" cellpadding="6">
    <tr>
        <th>ID</th>
        <th>Tên hoa</th>
        <th>Mô tả</th>
        <th>Ảnh</th>
        <th>Hành động</th>
    </tr>

    <?php foreach ($flowers as $f): ?>
    <tr>
        <td><?php echo $f['id']; ?></td>
        <td><?php echo htmlspecialchars($f['ten_hoa']); ?></td>
        <td><?php echo nl2br(htmlspecialchars($f['mota'])); ?></td>
        <td>
            <?php if (!empty($f['hinh_anh']) && file_exists($uploadDir . $f['hinh_anh'])): ?>
                <img src="uploads/<?php echo $f['hinh_anh']; ?>" style="max-width:100px;">
            <?php endif; ?>
        </td>
        <td>

            <!-- FORM SỬA -->
            <form method="post" enctype="multipart/form-data" style="display:inline-block;">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?php echo $f['id']; ?>">

                Tên: <input type="text" name="name" value="<?php echo htmlspecialchars($f['ten_hoa']); ?>" required><br>
                Mô tả: <textarea name="description"><?php echo htmlspecialchars($f['mota']); ?></textarea><br>
                Ảnh mới: <input type="file" name="image"><br>

                <button type="submit">Lưu</button>
            </form>

            <br>

            <!-- LINK XÓA -->
            <a href="admin.php?delete=<?php echo $f['id']; ?>"
               onclick="return confirm('Xóa hoa này?');">
               Xóa
            </a>

        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
