<?php
require_once 'Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function addUser($data) {
        $stmt = $this->db->prepare("INSERT INTO users (username, password, full_name, email, role) VALUES (:username, :password, :full_name, :email, :role)");
        return $stmt->execute($data);
    }

    public function getAllUsers() {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 10");
        return $stmt->fetchAll();
    }
}
