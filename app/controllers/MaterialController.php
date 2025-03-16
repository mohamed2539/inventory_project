<?php
/*require_once 'BaseController.php';
require_once '../models/Material.php';*/

class MaterialController extends BaseController {
    private $materialModel;

    public function __construct() {
        $this->materialModel = new Material();
    }

    // ✅ إضافة مادة جديدة
    public function add() {
        $this->validateRequest('POST');

        $data = $_POST;

        // ✅ التحقق من الحقول المطلوبة
        if (empty($data['code']) || empty($data['name']) || empty($data['quantity']) || empty($data['price'])) {
            $this->jsonResponse(["error" => "الحقول الأساسية مطلوبة!"], 400);
            return;
        }

        if ($this->materialModel->addMaterial($data)) {
            $this->jsonResponse(["message" => "تمت إضافة المادة بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في الإضافة!"], 500);
        }
    }

    // ✅ جلب جميع المواد
    public function getAll() {
        $this->validateRequest('GET');
        $materials = $this->materialModel->getAllMaterials();
        $this->jsonResponse($materials);
    }

    // ✅ جلب آخر 20 مادة
    public function getRecent() {
        $this->validateRequest('GET');
        $materials = $this->materialModel->getRecentMaterials(20);
        $this->jsonResponse($materials);
    }

    public function get() {
        $this->validateRequest('GET');

        if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->jsonResponse(["error" => "معرف المادة غير صالح!"], 400);
            return;
        }

        $material = $this->materialModel->getMaterialById($_GET['id']);

        if ($material) {
            $this->jsonResponse($material);
        } else {
            $this->jsonResponse(["error" => "المادة غير موجودة!"], 404);
        }
    }







    // ✅ تعديل مادة


    public function edit() {
        $this->validateRequest('POST');

        $data = $_POST;

        // ✅ التحقق من إرسال جميع الحقول المطلوبة
        if (!isset($data['id'], $data['code'], $data['name'], $data['quantity'], $data['price'], $data['unit'], $data['min_quantity'], $data['max_quantity'])) {
            $this->jsonResponse(["error" => "جميع الحقول مطلوبة!"], 400);
            return;
        }

        // ✅ تجهيز البيانات للتحديث
        $updateData = [
            'id'          => (int) $data['id'],
            'code'        => $data['code'],
            'barcode'     => $data['barcode'] ?? null,
            'name'        => $data['name'],
            'quantity'    => (int) $data['quantity'],
            'price'       => (float) $data['price'],
            'unit'        => $data['unit'],
            'min_quantity' => (int) $data['min_quantity'],
            'max_quantity' => (int) $data['max_quantity'],
            'branch_id'   => isset($data['branch_id']) && !empty($data['branch_id']) ? (int) $data['branch_id'] : null,
            'supplier_id' => isset($data['supplier_id']) && !empty($data['supplier_id']) ? (int) $data['supplier_id'] : null
        ];

        if ($this->materialModel->updateMaterial($updateData)) {
            $this->jsonResponse(["message" => "تم تحديث المادة بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في التحديث!"], 500);
        }
    }




    /*    public function edit() {
            $this->validateRequest('POST');

            $data = $_POST;

            // ✅ التحقق من القيم المطلوبة
            if (empty($data['id']) || empty($data['name']) || empty($data['quantity']) || empty($data['price'])) {
                $this->jsonResponse(["error" => "جميع الحقول مطلوبة!"], 400);
                return;
            }

            if ($this->materialModel->updateMaterial($data['id'], $data)) {
                $this->jsonResponse(["message" => "تم تحديث المادة بنجاح!"]);
            } else {
                $this->jsonResponse(["error" => "خطأ في التحديث!"], 500);
            }
        }*/

    // ✅ حذف مادة (Soft Delete)
    public function delete() {
        $this->validateRequest('POST');

        // ✅ استقبال البيانات من JSON
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['id'])) {
            $this->jsonResponse(["error" => "يجب إرسال معرف المادة"], 400);
            return;
        }

        if ($this->materialModel->softDeleteMaterial($data['id'])) {
            $this->jsonResponse(["message" => "تم حذف المادة بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في الحذف!"], 500);
        }
    }
}
?>
