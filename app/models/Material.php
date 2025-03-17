<?php
require_once 'Database.php';

class Material {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // ✅ إضافة مادة جديدة
    public function addMaterial($data) {
        $stmt = $this->db->prepare("INSERT INTO materials 
            (code, barcode, name, unit, quantity, min_quantity, max_quantity, price, branch_id, supplier_id, created_at) 
            VALUES 
            (:code, :barcode, :name, :unit, :quantity, :min_quantity, :max_quantity, :price, :branch_id, :supplier_id, NOW())");

        return $stmt->execute([
            'code'        => $data['code'],
            'barcode'     => $data['barcode'] ?? null,
            'name'        => $data['name'],
            'unit'        => $data['unit'] ?? 'piece', // ✅ قيمة افتراضية للوحدة
            'quantity'    => $data['quantity'],
            'min_quantity' => $data['min_quantity'] ?? 1,   // ✅ قيمة افتراضية للحد الأدنى
            'max_quantity' => $data['max_quantity'] ?? 100, // ✅ قيمة افتراضية للحد الأقصى
            'price'       => $data['price'],
            'branch_id'   => $data['branch_id'] ?? null,  // ✅ ممكن يكون غير محدد
            'supplier_id' => $data['supplier_id'] ?? null // ✅ ممكن يكون غير محدد
        ]);
    }

    // ✅ جلب جميع المواد
    public function getAllMaterials() {
        $stmt = $this->db->query("SELECT * FROM materials WHERE deleted_at IS NULL ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    // ✅ جلب آخر 20 مادة
    public function getRecentMaterials($limit = 20) {
        $stmt = $this->db->prepare("SELECT * FROM materials WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT ?");
        $stmt->bindValue(1, (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ✅ جلب مادة واحدة عبر الكود
    public function getMaterialByCode($code) {
        $stmt = $this->db->prepare("SELECT * FROM materials WHERE code = ? AND deleted_at IS NULL");
        $stmt->execute([$code]);
        return $stmt->fetch();
    }

    // ✅ جلب مادة واحدة عبر الـ ID
    public function getMaterialById($id) {
        $stmt = $this->db->prepare("SELECT * FROM materials WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }









    // ✅ تعديل كمية المادة
    public function updateMaterialQuantity($id, $quantityChange) {
        $stmt = $this->db->prepare("UPDATE materials SET quantity = quantity + ?, updated_at = NOW() WHERE id = ? AND deleted_at IS NULL");
        return $stmt->execute([$quantityChange, $id]);
    }

    // ✅ البحث عن مواد عبر الاسم أو الكود أو الباركود
    public function searchMaterials($query) {
        $stmt = $this->db->prepare("SELECT * FROM materials WHERE (name LIKE ? OR code LIKE ? OR barcode LIKE ?) AND deleted_at IS NULL");
        $stmt->execute(["%$query%", "%$query%", "%$query%"]);
        return $stmt->fetchAll();
    }

    // ✅ تحديث بيانات المادة
    public function updateMaterial($data) {
        //var_dump($data); exit; // ✅ افحص البيانات قبل تنفيذ الاستعلام

        $stmt = $this->db->prepare("UPDATE materials 
        SET code = :code, barcode = :barcode, name = :name, quantity = :quantity, 
            price = :price, unit = :unit, min_quantity = :min_quantity, max_quantity = :max_quantity, 
            branch_id = :branch_id, supplier_id = :supplier_id, updated_at = NOW() 
        WHERE id = :id AND deleted_at IS NULL");

        return $stmt->execute([
            'id'         => $data['id'],
            'code'       => $data['code'],
            'barcode'    => $data['barcode'] ?? null,
            'name'       => $data['name'],
            'quantity'   => (int) $data['quantity'],
            'price'      => (float) $data['price'],
            'unit'       => $data['unit'],
            'min_quantity' => (int) $data['min_quantity'],
            'max_quantity' => (int) $data['max_quantity'],
            'branch_id'  => !empty($data['branch_id']) ? $data['branch_id'] : null,
            'supplier_id' => !empty($data['supplier_id']) ? $data['supplier_id'] : null
        ]);
    }




    // ✅ الحذف الناعم (Soft Delete)
    public function softDeleteMaterial($id) {
        $stmt = $this->db->prepare("UPDATE materials SET deleted_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }



    public function recordTransaction($material_id, $user_id, $type, $quantity) {
        $stmt = $this->db->prepare("INSERT INTO transactions (material_id, user_id, type, quantity) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$material_id, $user_id, $type, $quantity]);
    }



/*    public function searchMaterials($query) {
        $stmt = $this->db->prepare("SELECT id, code, name, unit, quantity, price FROM materials
                                WHERE name LIKE ? OR code LIKE ? OR unit LIKE ?
                                ORDER BY name ASC LIMIT 20");
        $stmt->execute(["%$query%", "%$query%", "%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }*/





}
?>
