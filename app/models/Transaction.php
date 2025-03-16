<?php
require_once 'Database.php';

class Transaction {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function recordTransaction($material_id, $user_id, $type, $quantity) {
        $stmt = $this->db->prepare("INSERT INTO transactions (material_id, user_id, type, quantity) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$material_id, $user_id, $type, $quantity]);
    }
}
