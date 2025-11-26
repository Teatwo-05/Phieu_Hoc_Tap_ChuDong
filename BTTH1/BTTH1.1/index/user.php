<?php
session_start();

if (isset($_GET['set'])) {
    if ($_GET['set'] === 'admin') $_SESSION['user_role'] = 'admin';
    else $_SESSION['user_role'] = 'guest';
    header('Location: user.php');
    exit;
}

$is_admin = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');

// Dữ liệu hoa (giả lập, chưa dùng CSDL)
require 'hoa.php';
$success = $_GET['success'] ?? "";

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Danh sách hoa</title>
</head>
<body>
    <p>Chế độ: <?php echo $is_admin ? 'Quản trị' : 'Khách'; ?> — 
       <a href="?set=guest">Chuyển thành Khách</a> |
       <a href="?set=admin">Chuyển thành Quản trị</a>
    </p>

<?php if ($is_admin): ?>
    <!-- Admin: hiển thị bảng và CRUD -->
    <h1>Danh sách hoa (Quản trị)</h1>
    <p><a href="add.php">Thêm hoa mới</a></p>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Tên</th><th>Mô tả</th><th>Ảnh</th><th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($hoa_list as $key => $item): 

            $ten  = htmlspecialchars($item['ten_hoa'] ?? $item['ten'] ?? '');
            $anh = htmlspecialchars($item['hinh_anh'] ?? '');
            $mota = htmlspecialchars($item['mota'] ?? $item['mo_ta'] ?? '');
        ?>
            <tr>
                <td><?php echo $ten; ?></td>
                <td><?php echo $mota; ?></td>
                <td>
                    <?php if ($anh): ?>
                        <img src="<?php echo $anh; ?>" alt="<?php echo $ten; ?>" style="max-width:100px;">
                    <?php else: ?>
                        (Chưa có ảnh)
                    <?php endif; ?>

                <td>
                    <a href="edit.php?ten=<?php echo urlencode($key); ?>">Sửa</a> |
                    <a href="delete.php?ten=<?php echo urlencode($key); ?>" onclick="return confirm('Xác nhận xóa?')">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>

    <!-- Guest: hiển thị list bài viết -->

    <h1>Danh sách hoa</h1>
    <?php foreach ($hoa_list as $key => $item): 
        $ten  = htmlspecialchars($item['ten_hoa'] ?? $item['ten'] ?? '');
        $mota = htmlspecialchars($item['mota'] ?? $item['mo_ta'] ?? '');
        $anh = htmlspecialchars($item['hinh_anh'] ?? '');
    ?>
        <div style="border:1px solid #ccc; padding:12px; margin-bottom:12px;">
            <h2><?php echo $ten; ?></h2>
            <?php if ($anh): ?>
                <img src="<?php echo $anh; ?>" alt="<?php echo $ten; ?>" style="max-width:200px; float:left; margin-right:12px;">
            <?php endif; ?>
            <p><?php echo nl2br($mota); ?></p>
            <div style="clear:both;"></div>
        </div>
    <?php endforeach; ?>

<?php endif; ?>

</body>
</html>