<?php
//require_once 'Database.php';

class Transaction {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // ✅ تسجيل عملية صرف مادة
    public function recordTransaction($materialId, $userId, $type, $quantity) {
        $stmt = $this->db->prepare("INSERT INTO transactions (material_id, user_id, type, quantity) VALUES (:material_id, :user_id, :type, :quantity)");
        return $stmt->execute([
            'material_id' => $materialId,
            'user_id' => $userId,
            'type' => $type,
            'quantity' => $quantity
        ]);
    }

    // ✅ الحصول على آخر 20 عملية صرف
    public function getRecentTransactions($limit = 20) {
        $stmt = $this->db->prepare("SELECT 
        t.id, 
        m.name AS material_name, 
        u.full_name AS user_name, 
        t.type, 
        t.quantity, 
        t.created_at 
    FROM transactions t
    JOIN materials m ON t.material_id = m.id
    JOIN users u ON t.user_id = u.id
    WHERE t.deleted_at IS NULL  -- ✅ عدم جلب العمليات المحذوفة
    ORDER BY t.created_at DESC 
    LIMIT :limit");

        $stmt->bindValue(":limit", (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function getTransactionById($id) {
        $stmt = $this->db->prepare("
        SELECT t.*, 
               m.name AS material_name, 
               u.full_name AS user_name 
        FROM transactions t
        JOIN materials m ON t.material_id = m.id
        JOIN users u ON t.user_id = u.id
        WHERE t.id = ?
    ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function updateTransaction($data) {
        $this->db->beginTransaction(); // ✅ نبدأ المعاملة

        try {
            // ✅ 1. جلب الكمية القديمة من `transactions`
            $stmt = $this->db->prepare("SELECT quantity, material_id FROM transactions WHERE id = ?");
            $stmt->execute([$data['id']]);
            $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$transaction) {
                throw new Exception("لم يتم العثور على العملية المطلوبة.");
            }

            $oldQuantity = (int) $transaction['quantity']; // الكمية القديمة
            $newQuantity = (int) $data['quantity']; // الكمية الجديدة بعد التعديل
            $materialId = (int) $transaction['material_id']; // معرف المادة

            // ✅ 2. حساب الفرق بين الكمية القديمة والجديدة
            $quantityDifference = $newQuantity - $oldQuantity;

            // ✅ 3. تحديث الكمية في `materials`
            $stmt = $this->db->prepare("UPDATE materials SET quantity = quantity - ? WHERE id = ?");
            $stmt->execute([$quantityDifference, $materialId]);

            if ($stmt->rowCount() === 0) {
                throw new Exception("فشل تحديث الكمية في المخزون.");
            }

            // ✅ 4. تحديث `transactions`
            $stmt = $this->db->prepare("UPDATE transactions SET quantity = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$newQuantity, $data['id']]);

            if ($stmt->rowCount() === 0) {
                throw new Exception("فشل تحديث المعاملة.");
            }

            // ✅ 5. إنهاء المعاملة بنجاح
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // ❌ لو حصل خطأ، يتم التراجع عن جميع العمليات
            $this->db->rollBack();
            return false;
        }
    }



    public function deleteTransaction($id) {
        $stmt = $this->db->prepare("UPDATE transactions SET deleted_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }



}
