<?php
/*require_once 'BaseController.php';
require_once '../models/User.php';*/

class UserController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // ✅ جلب جميع المستخدمين
    public function getAll() {
        $this->validateRequest('GET');
        $users = $this->userModel->getAllUsers();
        $this->jsonResponse($users);
    }


/*    public function login() {
        $this->validateRequest('POST');

        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$username || !$password) {
            $this->jsonResponse(["error" => "اسم المستخدم وكلمة المرور مطلوبة!"], 400);
            return;
        }

        $user = $this->userModel->getUserByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->jsonResponse(["error" => "اسم المستخدم أو كلمة المرور غير صحيحة!"], 401);
            return;
        }

        // ✅ تسجيل المستخدم في الجلسة
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        $this->jsonResponse(["message" => "تم تسجيل الدخول بنجاح!"]);
    }*/



    // ✅ تسجيل مستخدم جديد
    public function register() {
        $this->validateRequest('POST');

        $data = $_POST;
        if (empty($data['username']) || empty($data['password']) || empty($data['full_name']) || empty($data['branch_id']) || empty($data['role'])) {
            $this->jsonResponse(["error" => "جميع الحقول مطلوبة!"], 400);
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        if ($this->userModel->createUser($data)) {
            $this->jsonResponse(["message" => "تم تسجيل المستخدم بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في إنشاء المستخدم!"], 500);
        }
    }

    // ✅ تعديل المستخدم
    public function update() {
        $this->validateRequest('POST');

        $data = $_POST;
        if (empty($data['id']) || empty($data['full_name']) || empty($data['role']) || empty($data['status']) || empty($data['branch_id'])) {
            $this->jsonResponse(["error" => "جميع الحقول مطلوبة!"], 400);
        }

        if ($this->userModel->updateUser($data)) {
            $this->jsonResponse(["message" => "تم تحديث المستخدم بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في تحديث المستخدم!"], 500);
        }
    }

    // ✅ حذف المستخدم
    public function delete() {
        $this->validateRequest('POST');

        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data['id'])) {
            $this->jsonResponse(["error" => "يجب إرسال معرف المستخدم"], 400);
        }

        if ($this->userModel->deleteUser($data['id'])) {
            $this->jsonResponse(["message" => "تم حذف المستخدم بنجاح!"]);
        } else {
            $this->jsonResponse(["error" => "خطأ في حذف المستخدم!"], 500);
        }
    }
}
?>
