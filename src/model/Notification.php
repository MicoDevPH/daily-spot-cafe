<?php
require_once __DIR__ . '/../config/database.php';

class Notification {
    private $conn;
    private $table_name = "notifications";

    public $id;
    public $title;
    public $message;
    public $type;
    public $is_read;
    public $created_at;

    public function __construct() {
        $this->conn = Database::getConnection();
        $this->createTableIfNotExists();
    }

    private function createTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS " . $this->table_name . " (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            type VARCHAR(50) DEFAULT 'info',
            is_read TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->conn->query($sql);
    }

    public function getAll($filter = 'all') {
        $sql = "SELECT * FROM " . $this->table_name;
        if ($filter === 'unread') {
            $sql .= " WHERE is_read = 0";
        }
        $sql .= " ORDER BY created_at DESC";
        
        $result = $this->conn->query($sql);
        $notifications = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $notifications[] = $row;
            }
        }
        return $notifications;
    }

    public function getUnreadCount() {
        $sql = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE is_read = 0";
        $result = $this->conn->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['count'];
        }
        return 0;
    }

    public function markAsRead($id) {
        $sql = "UPDATE " . $this->table_name . " SET is_read = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function markAllAsRead() {
        $sql = "UPDATE " . $this->table_name . " SET is_read = 1";
        return $this->conn->query($sql);
    }

    public function delete($id) {
        $sql = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function create($title, $message, $type = 'info') {
        $sql = "INSERT INTO " . $this->table_name . " (title, message, type) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $title, $message, $type);
        return $stmt->execute();
    }
}
?>
