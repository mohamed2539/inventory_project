

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مورد جديد</title>
    <link href="../../../assets/css/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-100">
<div class="max-w-lg mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">إضافة مورد جديد</h2>
    <form id="addSupplierForm">
        <input type="text" name="name" placeholder="اسم المورد" required class="border p-2 w-full mb-2">
        <input type="text" name="phone" placeholder="رقم الهاتف" required class="border p-2 w-full mb-2">
        <input type="email" name="email" placeholder="البريد الإلكتروني" required class="border p-2 w-full mb-2">
        <input type="text" name="address" placeholder="العنوان" required class="border p-2 w-full mb-2">
        <input type="text" name="contact_person" placeholder="الشخص المسؤول" required class="border p-2 w-full mb-2">
        <input type="text" name="tax_number" placeholder="الرقم الضريبي (اختياري)" class="border p-2 w-full mb-2">
        <input type="text" name="commercial_record" placeholder="السجل التجاري (اختياري)" class="border p-2 w-full mb-2">
        <input type="text" name="payment_terms" placeholder="شروط الدفع (اختياري)" class="border p-2 w-full mb-2">
        <button type="submit" class="bg-blue-500 text-white p-2 w-full">إضافة</button>
    </form>
    <p id="responseMessage" class="text-green-500 mt-2"></p>
</div>

<!-- جدول عرض آخر 20 مورد -->
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow mt-6">
    <h2 class="text-xl font-bold mb-4">آخر 20 مورد</h2>
    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
        <tr class="bg-gray-200">
            <th class="border p-2">اسم المورد</th>
            <th class="border p-2">رقم الهاتف</th>
            <th class="border p-2">البريد الإلكتروني</th>
            <th class="border p-2">الإجراءات</th>
        </tr>
        </thead>
        <tbody id="supplierTable">
        <?php if (isset($suppliers) && !empty($suppliers)): ?>
            <?php foreach ($suppliers as $supplier): ?>
                <tr data-id="<?= $supplier['id'] ?>">
                    <td class="border p-2"><?= htmlspecialchars($supplier['name']) ?></td>
                    <td class="border p-2"><?= htmlspecialchars($supplier['phone']) ?></td>
                    <td class="border p-2"><?= htmlspecialchars($supplier['email']) ?></td>
                    <td class="border p-2">
                        <button class="edit-supplier bg-yellow-500 text-white p-1 rounded" data-id="<?= $supplier['id'] ?>">تعديل</button>
                        <button class="delete-supplier bg-red-500 text-white p-1 rounded" data-id="<?= $supplier['id'] ?>">حذف</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" class="text-center border p-2">لا يوجد موردين حتى الآن</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>



<!-- Modal تعديل المورد -->
<div id="editSupplierModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded shadow-md w-96">
        <h2 class="text-xl font-bold mb-4">تعديل بيانات المورد</h2>
        <form id="editSupplierForm">
            <input type="hidden" id="editSupplierId">
            <input type="text" id="editSupplierName" placeholder="اسم المورد" required class="border p-2 w-full mb-2">
            <input type="text" id="editSupplierPhone" placeholder="رقم الهاتف" required class="border p-2 w-full mb-2">
            <input type="email" id="editSupplierEmail" placeholder="البريد الإلكتروني" required class="border p-2 w-full mb-2">
            <button type="submit" class="bg-blue-500 text-white p-2 w-full">حفظ التعديلات</button>
            <button type="button" id="closeEditModal" class="bg-gray-500 text-white p-2 w-full mt-2">إلغاء</button>
        </form>
    </div>
</div>




<script src="../../../assets/js/supplier.js"></script>
</body>
</html>
