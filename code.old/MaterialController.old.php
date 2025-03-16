<?php
/*require_once 'BaseController.php';
require_once '../models/Material.php';*/

class MaterialController extends BaseController {
    private $materialModel;

    public function __construct() {
        $this->materialModel = new Material();
    }

    public function add() {
        $this->validateRequest('POST');

        $data = $_POST;
        if (empty($data['code']) || empty($data['name']) || empty($data['quantity']) || empty($data['price'])) {
            $this->jsonResponse(["error" => "الحقول الأساسية مطلوبة!"], 400);
        }

        if ($this->materialModel->addMaterial($data)) {
            $this->jsonResponse(["message" => "تمت إضافة المادة بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في الإضافة!"], 500);
        }
    }

    public function getAll() {
        $this->validateRequest('GET');
        $materials = $this->materialModel->getAllMaterials();
        $this->jsonResponse($materials);
    }

    public function edit() {
        $this->validateRequest('POST');

        $data = $_POST;
        if (empty($data['id']) || empty($data['name']) || empty($data['quantity']) || empty($data['price'])) {
            $this->jsonResponse(["error" => "جميع الحقول مطلوبة!"], 400);
        }

        if ($this->materialModel->updateMaterial($data['id'], $data)) {
            $this->jsonResponse(["message" => "تم تحديث المادة بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في التحديث!"], 500);
        }
    }

    public function delete() {
        $this->validateRequest('POST');

        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data['id'])) {
            $this->jsonResponse(["error" => "يجب إرسال معرف المادة"], 400);
        }

        if ($this->materialModel->softDeleteMaterial($data['id'])) {
            $this->jsonResponse(["message" => "تم حذف المادة بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في الحذف!"], 500);
        }
    }
}
?>
