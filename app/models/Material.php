<?php
require_once 'Database.php';

class Material {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }



    public function addMaterial($data) {
        $stmt = $this->db->prepare("INSERT INTO materials 
            (code, barcode, name, unit, quantity, min_quantity, max_quantity, price, branch_id, supplier_id) 
            VALUES 
            (:code, :barcode, :name, :unit, :quantity, :min_quantity, :max_quantity, :price, :branch_id, :supplier_id)");
        return $stmt->execute($data);
    }


    public function getAllMaterials() {
        $stmt = $this->db->query("SELECT * FROM materials");
        return $stmt->fetchAll();
    }

    public function getMaterialByCode($code) {
        $stmt = $this->db->prepare("SELECT * FROM materials WHERE code = ?");
        $stmt->execute([$code]);
        return $stmt->fetch();
    }

    public function updateMaterialQuantity($id, $quantityChange) {
        $stmt = $this->db->prepare("UPDATE materials SET quantity = quantity + ? WHERE id = ?");
        return $stmt->execute([$quantityChange, $id]);
    }


    public function searchMaterials($query) {
        $stmt = $this->db->prepare("SELECT * FROM materials WHERE name LIKE ? OR code LIKE ? OR barcode LIKE ?");
        $stmt->execute(["%$query%", "%$query%", "%$query%"]);
        return $stmt->fetchAll();
    }


    public function updateMaterial($id, $data) {
        $stmt = $this->db->prepare("UPDATE materials SET name = :name, quantity = :quantity, price = :price, updated_at = NOW() WHERE id = :id");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function softDeleteMaterial($id) {
        $stmt = $this->db->prepare("UPDATE materials SET deleted_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }


}
