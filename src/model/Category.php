<?php
require_once '../src/config/database.php';

class Category
{
    private $conn;
    private $table_name = "categories";

    public $category_id;
    public $category_name;
    public $description;
    public $is_active;
    public $created_at;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET category_name=?, description=?, is_active=?";
        $stmt = $this->conn->prepare($query);

        $this->category_name = htmlspecialchars(strip_tags($this->category_name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->is_active = htmlspecialchars(strip_tags($this->is_active));

        $stmt->bind_param("ssi", $this->category_name, $this->description, $this->is_active);

        if ($stmt->execute()) {
            $this->category_id = $this->conn->insert_id;
            return true;
        }
        return false;
    }

    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function readOne()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE category_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->category_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if ($row) {
            $this->category_name = $row['category_name'];
            $this->description = $row['description'];
            $this->is_active = $row['is_active'];
            $this->created_at = $row['created_at'];
        }
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET category_name=?, description=?, is_active=? WHERE category_id=?";
        $stmt = $this->conn->prepare($query);

        $this->category_name = htmlspecialchars(strip_tags($this->category_name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->is_active = htmlspecialchars(strip_tags($this->is_active));

        $stmt->bind_param("ssii", $this->category_name, $this->description, $this->is_active, $this->category_id);

        return $stmt->execute();
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE category_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->category_id);
        return $stmt->execute();
    }

    public function getActiveCategories()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE is_active = 1 ORDER BY category_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>