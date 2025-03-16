<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة فرع جديد</title>
    <link href="../../../assets/css/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-100">
<div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">إضافة فرع جديد</h2>
    <form id="addBranchForm">
        <input type="text" name="name" placeholder="اسم الفرع" required class="border p-2 w-full mb-2">
        <input type="text" name="address" placeholder="العنوان" class="border p-2 w-full mb-2">
        <input type="text" name="phone" placeholder="رقم الهاتف" class="border p-2 w-full mb-2">
        <input type="email" name="email" placeholder="البريد الإلكتروني" class="border p-2 w-full mb-2">
        <input type="text" name="manager_name" placeholder="اسم المدير" class="border p-2 w-full mb-2">
        <textarea name="notes" placeholder="ملاحظات" class="border p-2 w-full mb-2"></textarea>
        <select name="status" class="border p-2 w-full mb-2">
            <option value="active">نشط</option>
            <option value="inactive">غير نشط</option>
        </select>
        <button type="submit" class="bg-blue-500 text-white p-2 w-full">إضافة</button>
    </form>
    <p id="responseMessage" class="text-green-500 mt-2"></p>
</div>

<script src="../../../assets/js/branch.js"></script>
</body>
</html>
