<?php
// db.php
class Database {
    private $host = '127.0.0.1';
    private $db = 'BooksManagementSystem'; // Tên CSDL bạn đã tạo
    private $user = 'root'; // Username mặc định của XAMPP
    private $pass = '';     // Password mặc định thường để trống
    private $charset = 'utf8mb4';
    public $pdo = null;

    public function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
}
?>