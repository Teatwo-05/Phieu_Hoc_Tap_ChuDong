<?php
require_once 'BookManager.php';

if (isset($_GET['id'])) {
    $db = new Database();
    $bookManager = new BookManager($db);
    
    $bookId = $_GET['id'];
    $bookManager->deleteBook($bookId);
}

// Quay về trang chủ sau khi xóa
header("Location: index.php");
exit;
?>