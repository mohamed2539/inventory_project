<?php

class TransactionController extends BaseController {
    private $transactionModel;
    private $materialModel;

    public function __construct() {
        $this->transactionModel = new Transaction();
        $this->materialModel = new Material();
    }

    // ✅ تنفيذ عملية الصرف

    public function dispense() {
        $this->validateRequest('POST');
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse(["error" => "يجب تسجيل الدخول قبل الصرف!"], 403);
            return;
        }

        if (empty($data['material_id']) || empty($data['quantity'])) {
            $this->jsonResponse(["error" => "جميع الحقول مطلوبة!"], 400);
            return;
        }

        $user_id = $_SESSION['user_id'];
        $this->db->beginTransaction(); // ✅ بدء المعاملة (TRANSACTION)

        try {
            $material = $this->materialModel->getMaterialById($data['material_id']);

            if (!$material || $material['quantity'] < $data['quantity']) {
                throw new Exception("الكمية غير كافية للصرف!");
            }

            // ✅ تحديث الكمية
            $this->materialModel->updateMaterialQuantity($data['material_id'], -$data['quantity']);

            // ✅ تسجيل المعاملة
            $this->transactionModel->recordTransaction($data['material_id'], $user_id, 'dispense', $data['quantity']);

            $this->db->commit(); // ✅ تأكيد العملية
            $this->jsonResponse(["message" => "تم صرف الكمية بنجاح!"]);

        } catch (Exception $e) {
            $this->db->rollBack(); // ❌ إلغاء التحديثات في حالة حدوث خطأ
            $this->jsonResponse(["error" => $e->getMessage()], 400);
        }
    }






    /*    public function dispense() {
            $this->validateRequest('POST');
            $data = json_decode(file_get_contents("php://input"), true);

            if (empty($data['material_id']) || empty($data['quantity']) || empty($data['user_id'])) {
                $this->jsonResponse(["error" => "جميع الحقول مطلوبة!"], 400);
                return;
            }

            // ✅ التحقق من توفر الكمية
            $material = $this->materialModel->getMaterialById($data['material_id']);
            if (!$material || $material['quantity'] < $data['quantity']) {
                $this->jsonResponse(["error" => "الكمية غير كافية للصرف!"], 400);
                return;
            }

            // ✅ تحديث الكمية في المخزون
            $this->materialModel->updateMaterialQuantity($data['material_id'], -$data['quantity']);

            // ✅ تسجيل عملية الصرف
            $this->transactionModel->recordTransaction($data['material_id'], $data['user_id'], 'dispense', $data['quantity']);

            $this->jsonResponse(["message" => "تم صرف الكمية بنجاح!"]);
        }*/

    // ✅ الحصول على آخر العمليات
    public function getRecentTransactions() {
        $this->validateRequest('GET');
        $transactions = $this->transactionModel->getRecentTransactions();

        if ($transactions) {
            $this->jsonResponse($transactions);
        } else {
            $this->jsonResponse(["error" => "لا توجد عمليات حتى الآن!"], 404);
        }
    }




    public function get() {
        $this->validateRequest('GET');

        if (!isset($_GET['id'])) {
            $this->jsonResponse(["error" => "يجب إرسال معرف العملية"], 400);
        }

        $transaction = $this->transactionModel->getTransactionById($_GET['id']);

        if ($transaction) {
            $this->jsonResponse($transaction);
        } else {
            $this->jsonResponse(["error" => "لم يتم العثور على المعاملة"], 404);
        }
    }

    public function edit() {
        $this->validateRequest('POST');

        $data = $_POST;
        if (empty($data['id']) || empty($data['quantity'])) {
            $this->jsonResponse(["error" => "جميع الحقول مطلوبة!"], 400);
            return;
        }

        if ($this->transactionModel->updateTransaction($data['id'], $data['quantity'])) {
            $this->jsonResponse(["message" => "تم تعديل بيانات الصرف بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في تعديل البيانات!"], 500);
        }
    }



    public function update() {
        $this->validateRequest('POST');

        $data = $_POST;

        // ✅ التحقق من إرسال جميع الحقول المطلوبة
        if (!isset($data['id'], $data['quantity'])) {
            $this->jsonResponse(["error" => "جميع الحقول مطلوبة!"], 400);
            return;
        }

        // ✅ جلب بيانات العملية القديمة
        $transaction = $this->transactionModel->getTransactionById($data['id']);
        if (!$transaction) {
            $this->jsonResponse(["error" => "المعاملة غير موجودة!"], 404);
            return;
        }

        // ✅ حساب الفرق بين الكمية القديمة والجديدة
        $oldQuantity = (int) $transaction['quantity'];
        $newQuantity = (int) $data['quantity'];
        $quantityDifference = $newQuantity - $oldQuantity;

        // ✅ تجهيز بيانات التحديث
        $updateData = [
            "id" => $data["id"],
            "quantity" => $newQuantity
        ];

        // ✅ تحديث سجل المعاملة
        if ($this->transactionModel->updateTransaction($updateData)) {
            // ✅ تحديث كمية المادة في المخزون بناءً على الفرق
            $this->materialModel->updateMaterialQuantity($transaction['material_id'], -$quantityDifference);

            $this->jsonResponse(["message" => "تم تعديل بيانات الصرف بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في تعديل بيانات الصرف!"], 500);
        }
    }


    public function delete() {
        $this->validateRequest('POST');
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['id'])) {
            $this->jsonResponse(["error" => "يجب إرسال معرف العملية"], 400);
        }

        if ($this->transactionModel->deleteTransaction($data['id'])) {
            $this->jsonResponse(["message" => "تم حذف العملية بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في الحذف!"], 500);
        }
    }



}
