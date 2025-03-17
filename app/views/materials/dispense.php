<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صرف المواد</title>
    <link href="../../../assets/css/tailwind.min.css" rel="stylesheet">
    <link href="../../../assets/css/Animate.css" rel="stylesheet">
    <link href="../../../assets/css/master.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body class="p-6 bg-gray-100">
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg mt-6">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">صرف المواد</h2>
    <form id="dispenseForm">
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">كود المادة</label>
            <input type="text" id="dispenseCode" name="code" required
                   class="w-full px-4 py-2 border rounded-lg shadow-md" onblur="fetchMaterial()">
        </div>
    </form>

    <div id="materialDetails" class="hidden mt-6 bg-white p-6 rounded-lg shadow-lg transition-all duration-500 transform scale-95">
        <h3 class="text-2xl font-bold text-center text-gray-800 mb-4">تفاصيل المادة</h3>

        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                <p class="text-lg font-semibold text-gray-700">🔹 <strong>الاسم:</strong> <span id="materialName" class="text-blue-600"></span></p>
                <p class="text-lg font-semibold text-gray-700">📏 <strong>الوحدة:</strong> <span id="materialUnit" class="text-blue-600"></span></p>
                <p class="text-lg font-semibold text-gray-700">📦 <strong>الكمية المتاحة:</strong> <span id="materialQuantity" class="text-red-600"></span></p>
            </div>

            <div class="bg-gray-100 p-4 rounded-lg shadow-md flex flex-col justify-between">
                <label class="block text-gray-700 font-medium text-lg">✏️ الكمية المطلوب صرفها</label>
                <input type="number" id="dispenseQuantity" name="quantity" required
                       class="w-full px-4 py-2 border rounded-lg shadow-md focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all duration-300">
            </div>
        </div>

        <button type="button" id="confirmDispense"
                class="w-full bg-red-600 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition-all duration-300 hover:bg-red-700 hover:scale-105 mt-4">
            💨 صرف المادة
        </button>
    </div>

    <p id="responseMessage" class="text-center mt-2 text-lg"></p>
</div>



<div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow-lg mt-6">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">آخر عمليات الصرف</h2>
    <div class="overflow-x-auto">
        <table class="w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
            <tr class="bg-gray-800 text-white text-lg">
                <th class="py-3 px-4 text-left">اسم المادة</th>
                <th class="py-3 px-4 text-left">اسم المستخدم</th>
                <th class="py-3 px-4 text-left">الكمية المصروفة</th>
                <th class="py-3 px-4 text-left">تاريخ الصرف</th>
                <th class="py-3 px-4 text-center">الإجراءات</th>
            </tr>
            </thead>
            <tbody id="transactionTable" class="divide-y divide-gray-200 text-gray-800">
            <?php if (isset($transactions) && !empty($transactions)): ?>
                <?php foreach ($transactions as $transaction): ?>
                    <tr data-id="<?= $transaction['id'] ?>">
                        <td class="border p-2"><?= htmlspecialchars($transaction['material_name']) ?></td>
                        <td class="border p-2"><?= htmlspecialchars($transaction['user_name']) ?></td>
                        <td class="border p-2 transaction-quantity"><?= htmlspecialchars($transaction['quantity']) ?></td>
                        <td class="border p-2"><?= htmlspecialchars($transaction['created_at']) ?></td>
                        <td class="border p-2 text-center">
                            <button class="edit-transaction bg-yellow-500 text-white p-1 rounded" data-id="<?= $transaction['id'] ?>">تعديل</button>
                        </td>
                        <td class="border p-2 text-center">
                            <button class="delete-transaction bg-red-500 text-white p-1 rounded" data-id="3">حذف</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="border p-2 text-center">لا توجد عمليات صرف بعد</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>








<div id="editTransactionModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded shadow-md w-96">
        <h2 class="text-xl font-bold mb-4">تعديل بيانات الصرف</h2>
        <form id="editTransactionForm">
            <input type="hidden" id="editTransactionId" name="id">
            <input type="hidden" id="editTransactionMaterialId" name="material_id">
            <input type="hidden" id="editTransactionUserId" name="user_id">

            <label class="block text-gray-700 font-medium">المادة</label>
            <p id="editTransactionMaterial" class="border p-2 w-full mb-2 bg-gray-100 rounded"></p>

            <label class="block text-gray-700 font-medium">المستخدم</label>
            <p id="editTransactionUser" class="border p-2 w-full mb-2 bg-gray-100 rounded"></p>

            <label class="block text-gray-700 font-medium">الكمية المصروفة</label>
            <input type="number" id="editTransactionQuantity" name="quantity" class="border p-2 w-full mb-2">

            <button type="submit" class="bg-blue-500 text-white p-2 w-full">حفظ التعديلات</button>
            <button type="button" id="closeEditTransactionModal" class="bg-gray-500 text-white p-2 w-full mt-2">إلغاء</button>
        </form>
    </div>
</div>

<script src="../../../assets/js/dispense.js"></script>
</body>
</html>
