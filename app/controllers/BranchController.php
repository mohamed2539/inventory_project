<?php
//require_once 'BaseController.php';
//require_once '../models/Branch.php';

class BranchController extends BaseController {
    private $branchModel;

    public function __construct() {
        $this->branchModel = new Branch();
    }

    public function add() {
        $this->validateRequest('POST');

        $data = [
            'name' => $_POST['name'],
            'address' => $_POST['address'],
            'phone' => $_POST['phone'],
            'email' => $_POST['email'],
            'manager_name' => $_POST['manager_name'],
            'status' => $_POST['status'],
            'notes' => $_POST['notes'] ?? null
        ];

        if ($this->branchModel->addBranch($data)) {
            $this->jsonResponse(["message" => "تمت إضافة الفرع بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في الإضافة!"], 500);
        }
    }

    public function getAll() {
        $this->validateRequest('GET');
        $branches = $this->branchModel->getAllBranches();
        $this->jsonResponse($branches);
    }

    public function edit() {
        $this->validateRequest('POST');

        $id = $_POST['id'];
        $updatedData = [
            'name' => $_POST['name'],
            'address' => $_POST['address'],
            'phone' => $_POST['phone'],
            'email' => $_POST['email'],
            'manager_name' => $_POST['manager_name'],
            'status' => $_POST['status'],
            'notes' => $_POST['notes'] ?? null
        ];

        if ($this->branchModel->updateBranch($id, $updatedData)) {
            $this->jsonResponse(["message" => "تم تحديث الفرع بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في التحديث!"], 500);
        }
    }

    public function delete() {
        $this->validateRequest('POST');

        // ✅ استقبال `id` من `JSON` بدلاً من `$_POST`
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? null;

        if (!$id) {
            $this->jsonResponse(["error" => "معرّف الفرع غير موجود!"], 400);
            return;
        }

        if ($this->branchModel->softDeleteBranch($id)) {
            $this->jsonResponse(["message" => "تم حذف الفرع بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في الحذف!"], 500);
        }
    }




    /*public function delete() {
        $this->validateRequest('POST');

        $id = $_POST['id'];

        if ($this->branchModel->softDeleteBranch($id)) {
            $this->jsonResponse(["message" => "تم حذف الفرع بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في الحذف!"], 500);
        }
    }*/


    public function getRecent() {
        $this->validateRequest('GET');
        $branches = $this->branchModel->getRecentBranches(20);
        $this->jsonResponse($branches);
    }



}



?>
