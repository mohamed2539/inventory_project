<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الفروع</title>
    <link href="../../../assets/css/tailwind.min.css" rel="stylesheet">
    <link href="../../../assets/css/Animate.css" rel="stylesheet">
    <link href="../../../assets/css/master.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body class="bg-gray-100 p-6">

<!-- ✅ نموذج إضافة فرع جديد -->
<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">إضافة فرع جديد</h2>
    <form id="addBranchForm" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium">اسم الفرع</label>
                <input type="text" name="name" required class="w-full px-4 py-2 border rounded-lg shadow-md">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">العنوان</label>
                <input type="text" name="address" required class="w-full px-4 py-2 border rounded-lg shadow-md">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium">رقم الهاتف</label>
                <input type="text" name="phone" required class="w-full px-4 py-2 border rounded-lg shadow-md">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">البريد الإلكتروني</label>
                <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg shadow-md">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium">مدير الفرع</label>
                <input type="text" name="manager_name" class="w-full px-4 py-2 border rounded-lg shadow-md">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">الحالة</label>
                <select name="status" class="w-full px-4 py-2 border rounded-lg shadow-md">
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                </select>
            </div>
        </div>
        <div>
            <label class="block text-gray-700 font-medium">ملاحظات</label>
            <textarea name="notes" class="w-full px-4 py-2 border rounded-lg shadow-md"></textarea>
        </div>
        <button type="submit"
                class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition-all hover:bg-blue-700">
            إضافة الفرع
        </button>
    </form>
    <p id="responseMessage" class="text-center mt-2 text-lg"></p>
</div>

<!-- ✅ جدول عرض الفروع -->
<div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-lg mt-6">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">آخر 20 فرع</h2>
    <div class="overflow-x-auto">
        <table class="w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
            <tr class="bg-blue-600 text-white text-lg">
                <th class="py-3 px-4 text-left">اسم الفرع</th>
                <th class="py-3 px-4 text-left">العنوان</th>
                <th class="py-3 px-4 text-left">رقم الهاتف</th>
                <th class="py-3 px-4 text-left">البريد الإلكتروني</th>
                <th class="py-3 px-4 text-left">مدير الفرع</th>
                <th class="py-3 px-4 text-left">الحالة</th>
                <th class="py-3 px-4 text-left">ملاحظات</th>
                <th class="py-3 px-4 text-center">الإجراءات</th>
            </tr>
            </thead>
            <tbody id="branchTable" class="divide-y divide-gray-200 text-gray-800">
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ Modal تعديل الفرع -->
<div id="editBranchModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-bold mb-4 text-center">تعديل بيانات الفرع</h2>
        <form id="editBranchForm">
            <input type="hidden" name="id" id="editBranchId">

            <div class="mb-4">
                <label class="block text-gray-700">اسم الفرع</label>
                <input type="text" name="name" id="editBranchName" required
                       class="w-full px-3 py-2 border rounded-lg shadow-md">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">العنوان</label>
                <input type="text" name="address" id="editBranchAddress" required
                       class="w-full px-3 py-2 border rounded-lg shadow-md">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">رقم الهاتف</label>
                <input type="text" name="phone" id="editBranchPhone" required
                       class="w-full px-3 py-2 border rounded-lg shadow-md">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">البريد الإلكتروني</label>
                <input type="email" name="email" id="editBranchEmail"
                       class="w-full px-3 py-2 border rounded-lg shadow-md">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">مدير الفرع</label>
                <input type="text" name="manager_name" id="editBranchManager"
                       class="w-full px-3 py-2 border rounded-lg shadow-md">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">الحالة</label>
                <select name="status" id="editBranchStatus"
                        class="w-full px-3 py-2 border rounded-lg shadow-md">
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">ملاحظات</label>
                <textarea name="notes" id="editBranchNotes"
                          class="w-full px-3 py-2 border rounded-lg shadow-md"></textarea>
            </div>

            <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-green-600 transition-all">
                حفظ التعديلات
            </button>
            <button type="button" id="closeEditBranchModal" class="w-full bg-gray-500 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-gray-600 transition-all mt-2">
                إلغاء
            </button>
        </form>
    </div>
</div>
<script src="../../../assets/js/ui.js"></script>
<script src="../../../assets/js/branch.js"></script>
</body>
</html>
