<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مستخدم جديد</title>
    <link href="../../../assets/css/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-100">
<div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">إضافة مستخدم جديد</h2>
    <form id="addUserForm">
        <input type="text" name="username" placeholder="اسم المستخدم" required class="border p-2 w-full mb-2">
        <input type="password" name="password" placeholder="كلمة المرور" required class="border p-2 w-full mb-2">
        <input type="text" name="full_name" placeholder="الاسم الكامل" required class="border p-2 w-full mb-2">

        <!-- اختيار الفرع -->
        <select name="branch_id" class="border p-2 w-full mb-2">
            <option value="">اختر الفرع</option>
        </select>

        <select name="role" class="border p-2 w-full mb-2">
            <option value="user">مستخدم عادي</option>
            <option value="admin">مشرف</option>
        </select>

        <select name="status" class="border p-2 w-full mb-2">
            <option value="active">نشط</option>
            <option value="inactive">غير نشط</option>
        </select>

        <button type="submit" class="bg-blue-500 text-white p-2 w-full">إضافة</button>
    </form>
    <p id="responseMessage" class="text-green-500 mt-2"></p>
</div>

<script src="../../../assets/js/add_user.js"></script>
</body>
</html>
