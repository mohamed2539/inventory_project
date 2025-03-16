<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المواد</title>
    <link href="../../../assets/css/tailwind.min.css" rel="stylesheet">
    <link href="../../../assets/css/Animate.css" rel="stylesheet">
    <link href="../../../assets/css/master.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body class="bg-gray-100 p-6">

<!-- ✅ نموذج إضافة مادة جديدة -->
<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">إضافة مادة جديدة</h2>
    <form id="addMaterialForm" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium">كود المادة</label>
                <input type="text" name="code" required class="w-full px-4 py-2 border rounded-lg shadow-md">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">الباركود</label>
                <input type="text" name="barcode" class="w-full px-4 py-2 border rounded-lg shadow-md">
            </div>
        </div>
        <div>
            <label class="block text-gray-700 font-medium">اسم المادة</label>
            <input type="text" name="name" required class="w-full px-4 py-2 border rounded-lg shadow-md">
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-gray-700 font-medium">الوحدة</label>
                <select name="unit" class="w-full px-4 py-2 border rounded-lg shadow-md">
                    <option value="piece">قطعة</option>
                    <option value="kg">كيلوغرام</option>
                    <option value="liter">لتر</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium">اختر الفرع</label>
                    <select name="branch_id" id="branchSelect" class="w-full px-4 py-2 border rounded-lg shadow-md">
                        <option value="">اختر الفرع</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">اختر المورد</label>
                    <select name="supplier_id" id="supplierSelect" class="w-full px-4 py-2 border rounded-lg shadow-md">
                        <option value="">اختر المورد</option>
                    </select>
                </div>
            </div>




            <div>
                <label class="block text-gray-700 font-medium">الكمية</label>
                <input type="number" name="quantity" required class="w-full px-4 py-2 border rounded-lg shadow-md">
            </div>

            <div class="mb-4">
                <label>أقل كمية</label>
                <input type="number" name="min_quantity" id="MaterialMinQuantity" required class="border p-2 w-full mb-2">


            </div>
            <div class="mb-4">
                <label>أقصى كمية</label>
                <input type="number" name="max_quantity" id="MaterialMaxQuantity" required class="border p-2 w-full mb-2">
            </div>

            <div>
                <label class="block text-gray-700 font-medium">السعر</label>
                <input type="number" name="price" required class="w-full px-4 py-2 border rounded-lg shadow-md">
            </div>
        </div>
        <button type="submit"
                class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition-all hover:bg-blue-700">
            إضافة المادة
        </button>
    </form>
    <p id="responseMessage" class="text-center mt-2 text-lg"></p>
</div>

<!-- ✅ جدول عرض المواد -->
<div class="max-w-screen-xl mx-auto bg-white p-6 rounded-lg shadow-lg mt-6">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">آخر 20 مادة</h2>
    <div class="overflow-x-auto">
        <table class="w-full table-auto bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
            <tr class="bg-blue-600 text-white text-lg">
                <th class="py-3 px-4 text-left">كود المادة</th>
                <th class="py-3 px-4 text-left">الباركود</th>
                <th class="py-3 px-4 text-left">اسم المادة</th>
                <th class="py-3 px-4 text-left">الوحدة</th>
                <th class="py-3 px-4 text-left">الكمية</th>
                <th class="py-3 px-4 text-left">السعر</th>
                <th class="py-3 px-4 text-center">الإجراءات</th>
            </tr>
            </thead>
            <tbody id="materialTable" class="divide-y divide-gray-200 text-gray-800">
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ Modal تعديل المادة -->
<!-- ✅ نموذج تعديل المادة -->
<!-- ✅ مودال تعديل المادة -->
<div id="editMaterialModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-2xl transform transition-all scale-95">
        <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">تعديل المادة</h2>

        <form id="editMaterialForm" class="space-y-4">
            <input type="hidden" name="id" id="editMaterialId">

            <div class="grid grid-cols-2 gap-4">
                <div class="relative">
                    <label class="block text-gray-700 font-medium">كود المادة</label>
                    <input type="text" name="code" id="editMaterialCode" required
                           class="w-full px-4 py-2 border rounded-lg shadow-md focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="relative">
                    <label class="block text-gray-700 font-medium">الباركود</label>
                    <input type="text" name="barcode" id="editMaterialBarcode"
                           class="w-full px-4 py-2 border rounded-lg shadow-md focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-medium">اسم المادة</label>
                <input type="text" name="name" id="editMaterialName" required
                       class="w-full px-4 py-2 border rounded-lg shadow-md focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium">الوحدة</label>
                    <select name="unit" id="editMaterialUnit"
                            class="w-full px-4 py-2 border rounded-lg shadow-md focus:ring-2 focus:ring-blue-500">
                        <option value="piece">قطعة</option>
                        <option value="kg">كيلوغرام</option>
                        <option value="liter">لتر</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium">الكمية</label>
                    <input type="number" name="quantity" id="editMaterialQuantity" required
                           class="w-full px-4 py-2 border rounded-lg shadow-md focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium">السعر</label>
                    <input type="number" name="price" id="editMaterialPrice" required
                           class="w-full px-4 py-2 border rounded-lg shadow-md focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium">أقل كمية</label>
                    <input type="number" name="min_quantity" id="editMaterialMinQuantity" required
                           class="w-full px-4 py-2 border rounded-lg shadow-md focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">أقصى كمية</label>
                    <input type="number" name="max_quantity" id="editMaterialMaxQuantity" required
                           class="w-full px-4 py-2 border rounded-lg shadow-md focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium">اختر الفرع</label>
                    <select name="branch_id" id="editMaterialBranch"
                            class="w-full px-4 py-2 border rounded-lg shadow-md focus:ring-2 focus:ring-blue-500">
                        <option value="">اختر الفرع</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">اختر المورد</label>
                    <select name="supplier_id" id="editMaterialSupplier"
                            class="w-full px-4 py-2 border rounded-lg shadow-md focus:ring-2 focus:ring-blue-500">
                        <option value="">اختر المورد</option>
                    </select>
                </div>
            </div>

            <!-- ✅ أزرار التحكم -->
            <div class="flex justify-between mt-4">
                <button type="submit"
                        class="w-1/2 bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition-all hover:bg-blue-700">
                    حفظ التعديلات
                </button>
                <button type="button" id="closeEditMaterialModal"
                        class="w-1/2 bg-gray-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition-all hover:bg-gray-600">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>


<script src="../../../assets/js/ui.js"></script>
<script src="../../../assets/js/material.js"></script>
</body>
</html>
