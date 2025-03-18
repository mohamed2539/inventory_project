<?php
require_once 'app/models/Database.php';
require_once 'app/models/User.php';

$userModel = new User();
$user = $userModel->getUserByUsername('Ibra');

if (!$user) {
    die("❌ المستخدم غير موجود!");
}

$enteredPassword = '222';
$hashedPassword = password_hash($enteredPassword, PASSWORD_DEFAULT);

echo "🔑 كلمة المرور المشفرة الآن: " . $hashedPassword . "<br>";

if (password_verify($enteredPassword, $hashedPassword)) {
    echo "✅ كلمة المرور صحيحة!";
} else {
    echo "❌ كلمة المرور غير صحيحة!";
}
?>