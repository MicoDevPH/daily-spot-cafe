<?php
require_once '../config/database.php';

class Order
{
    private $conn;
    private $table_name = "orders";

    public $order_id;
    public $total_amount;
    public $order_status;
    public $created_at;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET total_amount=?, order_status=?";
        $stmt = $this->conn->prepare($query);

        $this->total_amount = htmlspecialchars(strip_tags($this->total_amount));
        $this->order_status = htmlspecialchars(strip_tags($this->order_status));

        $stmt->bind_param("ds", $this->total_amount, $this->order_status);

        if ($stmt->execute()) {
            $this->order_id = $this->conn->insert_id;
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
        $query = "SELECT * FROM " . $this->table_name . " WHERE order_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->order_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if ($row) {
            $this->total_amount = $row['total_amount'];
            $this->order_status = $row['order_status'];
            $this->created_at = $row['created_at'];
        }
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET total_amount=?, order_status=? WHERE order_id=?";
        $stmt = $this->conn->prepare($query);

        $this->total_amount = htmlspecialchars(strip_tags($this->total_amount));
        $this->order_status = htmlspecialchars(strip_tags($this->order_status));

        $stmt->bind_param("dsi", $this->total_amount, $this->order_status, $this->order_id);

        return $stmt->execute();
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE order_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->order_id);
        return $stmt->execute();
    }

    public function getOrdersByStatus($status)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE order_status = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $status);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>