<?php
$filename = __DIR__ . '/65HTTT_Danh_sach_diem_danh.csv';
if (!file_exists($filename)) {
    die('Không tìm thấy file CSV!');
}
// Mở file CSV
$rows = array();
if (($handle = fopen($filename, 'r')) !== false) {
    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
        $rows[] = $data;
    }
    fclose($handle);
}
?>
<!doctype html>
<html>
        <head><meta charset="utf-8"><title>Danh sách điểm danh</title></head>
             <body>
                 <h1>Danh sách điểm danh</h1>
             <?php if (empty($rows)): ?>
        <p>File CSV rỗng.</p>
            <?php else: ?>
    <table border="1" cellpadding="5" cellspacing="0">
    <thead>
    <tr>
        <?php foreach ($rows[0] as $header): ?>
        <th><?= htmlspecialchars($header) ?></th>
            <?php endforeach; ?>
    </tr>
        </thead>
            <tbody>
            <?php for ($i=1; $i<count($rows); $i++): ?>
        <tr>
            <?php foreach ($rows[$i] as $cell): ?>
            <td><?= htmlspecialchars($cell) ?></td>
            <?php endforeach; ?>
        </tr>
        <?php endfor; ?>
        </tbody>
    </table>
    <?php endif; ?>
</body>
</html>