<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | إنشاء حساب</title>

    <link href="../../../assets/css/tailwind.min.css" rel="stylesheet">
    <link href="../../../assets/css/Animate.css" rel="stylesheet">
    <link href="../../../assets/css/master.css" rel="stylesheet">
    <link href="../../../assets/css/auth.css" rel="stylesheet">

</head>
<body class="flex justify-center items-center min-h-screen bg-[#f9f9f9]">

<div class="relative w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
    <!-- Tabs -->
    <div class="flex justify-around bg-gray-100 p-2 rounded-full shadow-inner">
        <button id="loginTab" class="tab-btn w-1/2 py-2 rounded-full transition-all">تسجيل الدخول</button>
        <button id="registerTab" class="tab-btn w-1/2 py-2 rounded-full transition-all">إنشاء حساب</button>
    </div>

    <!-- Login Form -->
    <div id="loginContainer" class="tab-content p-6">
        <h2 class="text-2xl font-bold text-center mb-4 text-gray-700">تسجيل الدخول</h2>
        <form id="loginForm" class="space-y-4">
            <h2 class="text-xl font-bold">تسجيل الدخول</h2>
            <input type="text" name="username" placeholder="اسم المستخدم" required class="w-full px-4 py-2 border rounded-lg shadow-md">
            <input type="password" name="password" placeholder="كلمة المرور" required class="w-full px-4 py-2 border rounded-lg shadow-md">
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg hover:bg-blue-700">
                تسجيل الدخول
            </button>
        </form>
        <p id="responseMessage" class="text-center mt-2 text-lg"></p>
    </div>

    <!-- Register Form -->
    <div id="registerContainer" class="tab-content p-6 hidden">
        <h2 class="text-2xl font-bold text-center mb-4 text-gray-700">إنشاء حساب</h2>
        <form id="registerUserForm" class="space-y-4">
            <h2 class="text-xl font-bold">تسجيل مستخدم جديد</h2>
            <input type="text" name="full_name" placeholder="الاسم الكامل" required class="w-full px-4 py-2 border rounded-lg shadow-md">
            <input type="text" name="username" placeholder="اسم المستخدم" required class="w-full px-4 py-2 border rounded-lg shadow-md">
            <input type="password" name="password" placeholder="كلمة المرور" required class="w-full px-4 py-2 border rounded-lg shadow-md">

            <!-- ✅ اختيار الفرع -->
            <select name="branch_id" id="userBranch" required class="w-full px-4 py-2 border rounded-lg shadow-md">
                <option value="">تحميل الفروع...</option>
            </select>

            <!-- ✅ اختيار الدور -->
            <select name="role" required class="w-full px-4 py-2 border rounded-lg shadow-md">
                <option value="admin">مدير عام (Admin)</option>
                <option value="manager">مدير فرع (Manager)</option>
                <option value="staff">موظف (Staff)</option>
            </select>

            <!-- ✅ اختيار الحالة -->
            <select name="status" required class="w-full px-4 py-2 border rounded-lg shadow-md">
                <option value="active">مفعل ✅</option>
                <option value="inactive">غير مفعل ❌</option>
            </select>

            <button type="submit" class="w-full bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg hover:bg-green-700">
                تسجيل المستخدم
            </button>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
<script src="../../../assets/js/auth.js"></script>

</body>
</html>
