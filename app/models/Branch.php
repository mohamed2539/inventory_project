<?php
require_once 'Database.php';

class Branch {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function addBranch($data) {
        $stmt = $this->db->prepare("INSERT INTO branches (name, address, phone, email, manager_name, status, notes, created_at, updated_at) 
                                    VALUES (:name, :address, :phone, :email, :manager_name, :status, :notes, NOW(), NOW())");
        return $stmt->execute($data);
    }

    public function getAllBranches() {
        $stmt = $this->db->query("SELECT * FROM branches WHERE deleted_at IS NULL ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getBranchById($id) {
        $stmt = $this->db->prepare("SELECT * FROM branches WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateBranch($id, $data) {
        $stmt = $this->db->prepare("UPDATE branches SET name = :name, address = :address, phone = :phone, email = :email, manager_name = :manager_name, 
                                    status = :status, notes = :notes, updated_at = NOW() WHERE id = :id");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function softDeleteBranch($id) {
        $stmt = $this->db->prepare("UPDATE branches SET deleted_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }


/*    public function ($limit = 20) {
        $stmt = $this->db->prepare("SELECT * FROM branches WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();

    }*/

    public function getRecentBranches($limit = 20) {
        $stmt = $this->db->prepare("SELECT * FROM branches WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }





}
?>
