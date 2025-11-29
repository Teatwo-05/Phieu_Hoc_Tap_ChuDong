<?php
// BookManager.php
require_once 'db.php';

class BookManager {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db->pdo;
    }

    // Thêm sách mới
    public function addBook($title, $publishedYear, $genre) {
        $stmt = $this->db->prepare("INSERT INTO Books (Title, PublishedYear, Genre) VALUES (:title, :year, :genre)");
        $stmt->execute(['title' => $title, 'year' => $publishedYear, 'genre' => $genre]);
    }

    // Lấy tất cả sách
    public function getAllBooks() {
        $stmt = $this->db->query("SELECT * FROM Books");
        return $stmt->fetchAll();
    }

    // Xóa sách
    public function deleteBook($bookId) {
        $stmt = $this->db->prepare("DELETE FROM Books WHERE BookID = :bookId");
        $stmt->execute(['bookId' => $bookId]);
    }

    // Cập nhật sách (Tùy chọn thêm)
    public function updateBook($bookId, $title, $publishedYear, $genre) {
        $stmt = $this->db->prepare("UPDATE Books SET Title = :title, PublishedYear = :year, Genre = :genre WHERE BookID = :bookId");
        $stmt->execute(['title' => $title, 'year' => $publishedYear, 'genre' => $genre, 'bookId' => $bookId]);
    }
}
?>