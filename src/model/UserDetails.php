<?php
require_once __DIR__ . '/../config/database.php';

class UserDetails
{
    private $conn;
    private $table_name = "user_details";

    public $user_id;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;
    public $phone_number;
    public $email;
    public $updated_at;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " SET first_name=?, middle_name=?, last_name=?, suffix=?, phone_number=?, email=?";
        $stmt = $this->conn->prepare($query);

        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->middle_name = htmlspecialchars(strip_tags($this->middle_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->suffix = htmlspecialchars(strip_tags($this->suffix));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bind_param("ssssss", $this->first_name, $this->middle_name, $this->last_name, $this->suffix, $this->phone_number, $this->email);

        if ($stmt->execute()) {
            $this->user_id = $this->conn->insert_id;
            return true;
        }
        return false;
    }

    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY updated_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function readOne()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if ($row) {
            $this->first_name = $row['first_name'];
            $this->middle_name = $row['middle_name'];
            $this->last_name = $row['last_name'];
            $this->suffix = $row['suffix'];
            $this->phone_number = $row['phone_number'];
            $this->email = $row['email'];
            $this->updated_at = $row['updated_at'];
        }
    }

    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET first_name=?, middle_name=?, last_name=?, suffix=?, phone_number=?, email=? WHERE user_id=?";
        $stmt = $this->conn->prepare($query);

        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->middle_name = htmlspecialchars(strip_tags($this->middle_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->suffix = htmlspecialchars(strip_tags($this->suffix));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bind_param("ssssssi", $this->first_name, $this->middle_name, $this->last_name, $this->suffix, $this->phone_number, $this->email, $this->user_id);

        return $stmt->execute();
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->user_id);
        return $stmt->execute();
    }

    public function getFullName()
    {
        $fullName = $this->first_name;
        if (!empty($this->middle_name)) {
            $fullName .= ' ' . $this->middle_name;
        }
        $fullName .= ' ' . $this->last_name;
        if (!empty($this->suffix)) {
            $fullName .= ' ' . $this->suffix;
        }
        return $fullName;
    }
}
?>