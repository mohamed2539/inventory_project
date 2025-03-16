<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مادة جديدة</title>
    <link href="../../../assets/css/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-100">
<div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">إضافة مادة جديدة</h2>
    <form id="addMaterialForm">
        <input type="text" name="code" placeholder="كود المادة" required class="border p-2 w-full mb-2">
        <input type="text" name="barcode" placeholder="الباركود (افتراضي)" class="border p-2 w-full mb-2">
        <input type="text" name="name" placeholder="اسم المادة" required class="border p-2 w-full mb-2">

        <select name="unit" class="border p-2 w-full mb-2">
            <option value="piece" selected>قطعة (افتراضي)</option>
            <option value="kg">كيلوغرام</option>
            <option value="liter">لتر</option>
        </select>

        <input type="number" name="quantity" placeholder="الكمية" required class="border p-2 w-full mb-2">
        <input type="number" name="min_quantity" placeholder="الحد الأدنى" value="1" class="border p-2 w-full mb-2">
        <input type="number" name="max_quantity" placeholder="الحد الأقصى" value="100" class="border p-2 w-full mb-2">
        <input type="number" name="price" placeholder="السعر" required class="border p-2 w-full mb-2">

        <!-- اختيار الفرع -->
        <select name="branch_id" class="border p-2 w-full mb-2">
            <option value="">اختر الفرع</option>
        </select>

        <!-- اختيار المورد -->
        <select name="supplier_id" class="border p-2 w-full mb-2">
            <option value="">اختر المورد</option>
        </select>

        <button type="submit" class="bg-blue-500 text-white p-2 w-full">إضافة</button>
    </form>
    <p id="responseMessage" class="text-green-500 mt-2"></p>
</div>

<script src="../../../assets/js/material.js"></script>
</body>
</html>
