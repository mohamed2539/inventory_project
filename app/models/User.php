<?php
require_once 'Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // ✅ جلب بيانات مستخدم عبر اسم المستخدم
    public function getUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ إنشاء مستخدم جديد
    public function createUser($data) {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO users (username, password, full_name, branch_id, role, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$data['username'], $hashedPassword, $data['full_name'], $data['branch_id'], $data['role'], $data['status']]);
    }

    // ✅ جلب جميع المستخدمين
    public function getAllUsers() {
        $stmt = $this->db->query("SELECT u.*, b.name AS branch_name FROM users u LEFT JOIN branches b ON u.branch_id = b.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ تحديث بيانات المستخدم
    public function updateUser($data) {
        $stmt = $this->db->prepare("UPDATE users SET full_name = ?, role = ?, status = ?, branch_id = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$data['full_name'], $data['role'], $data['status'], $data['branch_id'], $data['id']]);
    }

    // ✅ حذف المستخدم
    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
