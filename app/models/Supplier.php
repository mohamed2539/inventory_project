<?php
require_once 'Database.php';

class Supplier {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAllSuppliers() {
        $stmt = $this->db->query("SELECT * FROM suppliers");
        return $stmt->fetchAll();
    }



    public function addSupplier($data) {
        // تحقق مما إذا كان المورد محذوفًا (soft deleted)
        $stmt = $this->db->prepare("SELECT id, is_deleted FROM suppliers WHERE name = ?");
        $stmt->execute([$data['name']]);
        $existingSupplier = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingSupplier) {
            if ($existingSupplier['is_deleted'] == 1) {
                // إذا كان المورد محذوفًا، نقوم باستعادته بدلاً من إنشاء سجل جديد
                $this->restoreSupplier($data['name']);
                return ["message" => "تمت استعادة المورد بنجاح!"];
            } else {
                return ["error" => "هذا المورد موجود بالفعل!"];
            }
        }

        try {
            $stmt = $this->db->prepare("INSERT INTO suppliers (name, phone, email, address, contact_person, tax_number, commercial_record, payment_terms, created_at, updated_at, is_deleted) 
                                    VALUES (:name, :phone, :email, :address, :contact_person, :tax_number, :commercial_record, :payment_terms, NOW(), NOW(), 0)");
            $stmt->execute($data);
            return ["message" => "تمت إضافة المورد بنجاح!"];
        } catch (PDOException $e) {
            return ["error" => "حدث خطأ أثناء إضافة المورد."];
        }
    }



    /*    public function addSupplier($data) {
            try {
                $stmt = $this->db->prepare("INSERT INTO suppliers (name, phone, email, address, contact_person, tax_number, commercial_record, payment_terms, created_at, updated_at, is_deleted)
                                        VALUES (:name, :phone, :email, :address, :contact_person, :tax_number, :commercial_record, :payment_terms, NOW(), NOW(), 0)");
                return $stmt->execute($data);
            } catch (PDOException $e) {
                return ["error" => "اسم المورد موجود بالفعل. يرجى اختيار اسم آخر."];
            }
        }*/

/*    public function addSupplier($data) {
        $stmt = $this->db->prepare("INSERT INTO suppliers (name, phone, email, address, contact_person, tax_number, commercial_record, payment_terms, created_at, updated_at, is_deleted) 
                                    VALUES (:name, :phone, :email, :address, :contact_person, :tax_number, :commercial_record, :payment_terms, NOW(), NOW(), 0)");
        return $stmt->execute($data);
    }*/

 /*   public function getAllSuppliers() {
        $stmt = $this->db->query("SELECT * FROM suppliers WHERE is_deleted = 0 ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }*/

    public function updateSupplier($id, $data) {
        $stmt = $this->db->prepare("UPDATE suppliers SET name = :name, phone = :phone, email = :email, updated_at = NOW() WHERE id = :id");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function softDeleteSupplier($id) {
        $stmt = $this->db->prepare("UPDATE suppliers SET is_deleted = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getRecentSuppliers($limit = 20) {
        $stmt = $this->db->prepare("SELECT * FROM suppliers WHERE is_deleted = 0 ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function restoreSupplier($name) {
        $stmt = $this->db->prepare("UPDATE suppliers SET is_deleted = 0 WHERE name = ? AND is_deleted = 1");
        return $stmt->execute([$name]);
    }
}

