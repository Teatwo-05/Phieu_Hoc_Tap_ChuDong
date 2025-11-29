<?php
require_once 'BookManager.php';

$db = new Database();
$bookManager = new BookManager($db);
$books = $bookManager->getAllBooks();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Library Management</title>
</head>
<body>
    <h2>Book List</h2>
    <a href="add_book.php">Add New Book</a> <ul>
        <?php foreach ($books as $book): ?>
            <li>
                <?php echo htmlspecialchars($book['Title']) . " - " . htmlspecialchars($book['Genre']) . " (" . htmlspecialchars($book['PublishedYear']) . ")"; ?>
                
                <a href="delete_book.php?id=<?php echo $book['BookID']; ?>">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>