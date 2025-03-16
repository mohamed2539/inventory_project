

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الموردين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <link href="../../../assets/css/tailwind.min.css" rel="stylesheet">
    <link href="../../../assets/css/Animate.css" rel="stylesheet">
    <link href="../../../assets/css/master.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-100">
<div class="max-w-4xl mx-auto bg-white bg-opacity-80 p-8 rounded-2xl shadow-xl backdrop-blur-lg">
    <h2 class="text-3xl font-extrabold text-center text-gray-800 mb-6">إضافة مورد جديد</h2>
    <form id="addSupplierForm" class="space-y-6">
        <div class="relative">
            <label class="block text-gray-700 font-medium mb-1">اسم المورد</label>
            <input type="text" name="name" placeholder="اسم المورد" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white bg-opacity-50 shadow-md">
        </div>
        <div class="relative">
            <label class="block text-gray-700 font-medium mb-1">رقم الهاتف</label>
            <input type="text" name="phone" placeholder="رقم الهاتف" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white bg-opacity-50 shadow-md">
        </div>
        <div class="relative">
            <label class="block text-gray-700 font-medium mb-1">البريد الإلكتروني</label>
            <input type="email" name="email" placeholder="البريد الإلكتروني" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white bg-opacity-50 shadow-md">
        </div>
        <div class="relative">
            <label class="block text-gray-700 font-medium mb-1">العنوان</label>
            <input type="text" name="address" placeholder="العنوان" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white bg-opacity-50 shadow-md">
        </div>
        <div class="relative">
            <label class="block text-gray-700 font-medium mb-1">الشخص المسؤول</label>
            <input type="text" name="contact_person" placeholder="الشخص المسؤول" required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white bg-opacity-50 shadow-md">
        </div>
        <div class="relative">
            <label class="block text-gray-700 font-medium mb-1">الرقم الضريبي (اختياري)</label>
            <input type="text" name="tax_number" placeholder="الرقم الضريبي"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white bg-opacity-50 shadow-md">
        </div>
        <div class="relative">
            <label class="block text-gray-700 font-medium mb-1">السجل التجاري (اختياري)</label>
            <input type="text" name="commercial_record" placeholder="السجل التجاري"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white bg-opacity-50 shadow-md">
        </div>
        <div class="relative">
            <label class="block text-gray-700 font-medium mb-1">شروط الدفع (اختياري)</label>
            <input type="text" name="payment_terms" placeholder="شروط الدفع"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white bg-opacity-50 shadow-md">
        </div>
        <button type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-indigo-600 hover:to-blue-500 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition-all duration-300 transform hover:scale-105">
            إضافة المورد
        </button>
    </form>
    <p id="responseMessage" class="text-center mt-4 text-lg"></p>
</div>






<!-- جدول عرض آخر 20 مورد -->
<div class="max-w-4xl mx-auto bg-white bg-opacity-80 p-8 rounded-2xl shadow-xl backdrop-blur-lg mt-6">
    <h2 class="text-3xl font-extrabold text-center text-gray-800 mb-6">آخر 20 مورد</h2>
    <div class="overflow-x-auto">
        <table class="w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
            <tr class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-lg">
                <th class="py-4 px-6 text-left">اسم المورد</th>
                <th class="py-4 px-6 text-left">رقم الهاتف</th>
                <th class="py-4 px-6 text-left">البريد الإلكتروني</th>
                <th class="py-4 px-6 text-center">الإجراءات</th>
            </tr>
            </thead>
            <tbody id="supplierTable" class="divide-y divide-gray-200 text-gray-800">
            <?php if (isset($suppliers) && !empty($suppliers)): ?>
                <?php foreach ($suppliers as $supplier): ?>
                    <tr data-id="<?= $supplier['id'] ?>" class="hover:bg-gray-100 transition-all duration-300">
                        <td class="py-4 px-6 border"><?= htmlspecialchars($supplier['name']) ?></td>
                        <td class="py-4 px-6 border"><?= htmlspecialchars($supplier['phone']) ?></td>
                        <td class="py-4 px-6 border"><?= htmlspecialchars($supplier['email']) ?></td>
                        <td class="py-4 px-6 border text-center">
                            <button class="edit-supplier bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-lg shadow-md transition-all duration-300 transform hover:scale-105" data-id="<?= $supplier['id'] ?>">تعديل</button>
                            <button class="delete-supplier bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg shadow-md transition-all duration-300 transform hover:scale-105" data-id="<?= $supplier['id'] ?>">حذف</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center py-6 text-gray-600">لا يوجد موردين حتى الآن</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div


<!-- Modal تعديل المورد -->
<div id="editSupplierModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-bold mb-4">تعديل المورد</h2>
        <form id="editSupplierForm">
            <input type="hidden" name="id" id="editSupplierId">
            <div class="mb-4">
                <label class="block text-gray-700">اسم المورد</label>
                <input type="text" name="name" id="editSupplierName" required
                       class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">رقم الهاتف</label>
                <input type="text" name="phone" id="editSupplierPhone" required
                       class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">البريد الإلكتروني</label>
                <input type="email" name="email" id="editSupplierEmail" required
                       class="w-full px-3 py-2 border rounded-lg">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg w-full">حفظ التعديلات</button>
            <button type="button" id="closeEditModal" class="bg-gray-500 text-white px-4 py-2 rounded-lg w-full mt-2">إلغاء</button>
        </form>
    </div>
</div>






<script src="../../../assets/js/ui.js"></script>
<script src="../../../assets/js/supplier.js"></script>
</body>
</html>
