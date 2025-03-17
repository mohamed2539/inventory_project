<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة كمية جديدة</title>
    <link href="../../../assets/css/tailwind.min.css" rel="stylesheet">
    <link href="../../../assets/css/Animate.css" rel="stylesheet">
    <link href="../../../assets/css/master.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body class="p-6 bg-gray-100">
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg mt-6 transition-all duration-300 transform hover:scale-[1.02]">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">إضافة كمية جديدة</h2>

    <label class="block text-gray-700 font-semibold mb-2">🔍 أدخل كود المادة</label>
    <input type="text" id="materialCode" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition duration-300"
           onblur="fetchMaterial()" placeholder="مثال: A001">

    <!-- ✅ تفاصيل المادة -->
    <div id="materialDetails" class="hidden mt-6 bg-white p-5 rounded-lg shadow-lg border border-gray-200 transition-all duration-300">
        <h3 class="text-xl font-bold text-blue-600 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            تفاصيل المادة:
        </h3>

        <div class="grid grid-cols-2 gap-4 mt-4">
            <p class="text-gray-700"><strong class="text-gray-900">📌 الاسم:</strong> <span id="materialName"></span></p>
            <p class="text-gray-700"><strong class="text-gray-900">📦 الوحدة:</strong> <span id="materialUnit"></span></p>
            <p class="text-gray-700 col-span-2"><strong class="text-gray-900">📊 الكمية الحالية:</strong> <span id="currentQuantity" class="text-green-600 font-bold"></span></p>
        </div>

        <!-- ✅ إدخال الكمية المراد إضافتها -->
        <label class="block text-gray-700 font-semibold mt-4">📥 الكمية المراد إضافتها</label>
        <input type="number" id="addQuantity" class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-400 focus:border-green-400 transition duration-300"
               placeholder="أدخل الكمية">

        <button type="button" id="confirmAdd"
                class="w-full bg-green-600 text-white font-bold py-3 px-4 rounded-lg shadow-lg mt-4 transition-all hover:bg-green-700 hover:shadow-xl active:scale-95">
            ➕ إضافة الكمية
        </button>
    </div>

    <!-- ✅ رسالة الاستجابة -->
    <p id="responseMessage" class="text-center mt-4 text-lg font-semibold text-gray-700"></p>
</div>
<script src="../../../assets/js/addQuantity.js"></script>
</body>
</html>
