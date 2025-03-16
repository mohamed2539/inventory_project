
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

<!-- ✅ نموذج إضافة فرع جديد (تصغيره وتحسين التصميم) -->
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">إضافة فرع جديد</h2>
    <form id="addBranchForm" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium">اسم الفرع</label>
                <input type="text" name="name" placeholder="اسم الفرع" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white shadow-md">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">العنوان</label>
                <input type="text" name="address" placeholder="العنوان" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white shadow-md">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 font-medium">رقم الهاتف</label>
                <input type="text" name="phone" placeholder="رقم الهاتف" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white shadow-md">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">البريد الإلكتروني</label>
                <input type="email" name="email" placeholder="البريد الإلكتروني"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white shadow-md">
            </div>
        </div>
        <button type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-indigo-600 hover:to-blue-500 text-white font-bold py-2 px-4 rounded-lg shadow-lg transition-all duration-300 transform hover:scale-105">
            إضافة الفرع
        </button>
    </form>
    <p id="responseMessage" class="text-center mt-2 text-lg"></p>
</div>

<!-- ✅ جدول عرض الفروع (تكبيره ليكون أوضح) -->
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-lg mt-6">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">آخر 20 فرع</h2>
    <div class="overflow-x-auto">
        <table class="w-full bg-white border border-gray-300 rounded-lg shadow-md">
            <thead>
            <tr class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-lg">
                <th class="py-3 px-6 text-left">اسم الفرع</th>
                <th class="py-3 px-6 text-left">العنوان</th>
                <th class="py-3 px-6 text-left">رقم الهاتف</th>
                <th class="py-3 px-6 text-left">البريد الإلكتروني</th>
                <th class="py-3 px-6 text-center">الإجراءات</th>
            </tr>
            </thead>
            <tbody id="branchTable" class="divide-y divide-gray-200 text-gray-800">
            <!-- ✅ يتم ملء البيانات بواسطة JavaScript -->
            </tbody>
        </table>
    </div>
</div>


<script src="../../../assets/js/ui.js"></script>
<script src="../../../assets/js/branch.js"></script>
</body>
</html>




