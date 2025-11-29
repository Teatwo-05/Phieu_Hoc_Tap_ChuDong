<?php
require_once 'BookManager.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $bookManager = new BookManager($db);
    
    $title = $_POST['title'];
    $year = $_POST['year'];
    $genre = $_POST['genre'];

    $bookManager->addBook($title, $year, $genre);
    
    // Quay về trang chủ sau khi thêm
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Book</title>
</head>
<body>
    <h2>Add Book</h2>
    <form method="post">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br>
        
        <label for="year">Published Year:</label><br>
        <input type="number" id="year" name="year" required><br>
        
        <label for="genre">Genre:</label><br>
        <input type="text" id="genre" name="genre" required><br><br>
        
        <input type="submit" value="Add Book">
    </form>
</body>
</html>