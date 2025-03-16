<?php
/*require_once 'BaseController.php';
require_once '../models/Supplier.php';*/

class SupplierController extends BaseController {
    private $supplierModel;

    public function __construct() {
        $this->supplierModel = new Supplier();
    }

    public function add() {
        $this->validateRequest('POST');

        $data = $_POST;
        if (empty($data['name']) || empty($data['phone']) || empty($data['email']) || empty($data['address']) || empty($data['contact_person'])) {
            $this->jsonResponse(["error" => "جميع الحقول مطلوبة!"], 400);
        }

        if ($this->supplierModel->addSupplier($data)) {
            $this->jsonResponse(["message" => "تمت إضافة المورد بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في الإضافة!"], 500);
        }
    }

    public function getAll() {
        $this->validateRequest('GET');
        $suppliers = $this->supplierModel->getAllSuppliers();
        $this->jsonResponse($suppliers);
    }

    public function edit() {
        $this->validateRequest('POST');

        $data = $_POST;
        if (empty($data['id']) || empty($data['name']) || empty($data['phone']) || empty($data['email'])) {
            $this->jsonResponse(["error" => "جميع الحقول مطلوبة!"], 400);
        }

        if ($this->supplierModel->updateSupplier($data['id'], $data)) {
            $this->jsonResponse(["message" => "تم تحديث المورد بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في التحديث!"], 500);
        }
    }

    public function delete() {
        $this->validateRequest('POST');

        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data['id'])) {
            $this->jsonResponse(["error" => "يجب إرسال معرف المورد"], 400);
        }

        if ($this->supplierModel->softDeleteSupplier($data['id'])) {
            $this->jsonResponse(["message" => "تم حذف المورد بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في الحذف!"], 500);
        }
    }


    public function getRecent() {
        $this->validateRequest('GET');
        $suppliers = $this->supplierModel->getRecentSuppliers(20);
        $this->jsonResponse($suppliers);
    }




}
?>
