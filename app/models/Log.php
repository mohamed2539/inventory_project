<?php
require_once 'Database.php';

class Log {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function addLog($user_id, $action, $table_name, $record_id, $details) {
        $stmt = $this->db->prepare("INSERT INTO logs (user_id, action, table_name, record_id, details) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$user_id, $action, $table_name, $record_id, $details]);
    }

    public function getRecentLogs($limit = 10) {
        $stmt = $this->db->prepare("SELECT logs.*, users.username FROM logs JOIN users ON logs.user_id = users.id ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}
