<?php
/*require_once 'BaseController.php';
require_once '../models/User.php';*/

class AuthController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // ✅ تسجيل الدخول
    public function login() {
        $this->validateRequest('POST');

        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$username || !$password) {
            $this->jsonResponse(["error" => "اسم المستخدم وكلمة المرور مطلوبة!"], 400);
            return;
        }

        // ✅ جلب بيانات المستخدم من قاعدة البيانات
        $user = $this->userModel->getUserByUsername($username);

        if (!$user) {
            $this->jsonResponse(["error" => "اسم المستخدم غير صحيح!"], 401);
            return;
        }

        // ✅ التحقق من كلمة المرور باستخدام `password_verify()`
        if (!password_verify($password, $user['password'])) {
            $this->jsonResponse(["error" => "كلمة المرور غير صحيحة!"], 401);
            return;
        }

        // ✅ تسجيل بيانات المستخدم في الجلسة
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        $this->jsonResponse(["message" => "تم تسجيل الدخول بنجاح!"]);
    }


    // ✅ تسجيل الخروج
    public function logout() {
        session_start();
        session_destroy();
        $this->jsonResponse(["message" => "تم تسجيل الخروج بنجاح!"]);
    }

    // ✅ التحقق من الجلسة
    public function checkSession() {
        session_start();
        if (isset($_SESSION['user_id'])) {
            $this->jsonResponse(["user" => $_SESSION]);
        } else {
            $this->jsonResponse(["error" => "لم يتم تسجيل الدخول"], 401);
        }
    }
}
?>
