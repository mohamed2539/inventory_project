<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البحث المباشر عن المواد</title>
    <link href="../../../assets/css/tailwind.min.css" rel="stylesheet">
    <link href="../../../assets/css/Animate.css" rel="stylesheet">
    <link href="../../../assets/css/master.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>
<body class="p-6 bg-gray-100">
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">🔍 البحث عن المواد</h2>

    <input type="text" id="searchInput"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition duration-300"
           placeholder="ابحث عن أي مادة (بالاسم، الكود، الوحدة...)">

    <!-- ✅ عرض نتائج البحث -->
    <div id="searchResults" class="mt-4 hidden bg-white p-4 rounded-lg shadow-lg border border-gray-200">
        <h3 class="text-xl font-bold text-blue-600">🔎 النتائج:</h3>
        <table class="w-full bg-white border border-gray-300 rounded-lg shadow-md mt-3">
            <thead>
            <tr class="bg-blue-600 text-white text-lg">
                <th class="py-3 px-4 text-left">📌 الكود</th>
                <th class="py-3 px-4 text-left">📦 الاسم</th>
                <th class="py-3 px-4 text-left">⚖ الوحدة</th>
                <th class="py-3 px-4 text-left">📊 الكمية</th>
                <th class="py-3 px-4 text-left">💰 السعر</th>
            </tr>
            </thead>
            <tbody id="resultsTable" class="divide-y divide-gray-200 text-gray-800">
            <!-- ✅ سيتم تحميل البيانات هنا عبر الـ JavaScript -->
            </tbody>
        </table>
    </div>
</div>



<script src="../../../assets/js/search.js"></script>
</body>
</html>
