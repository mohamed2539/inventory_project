<!-- ✅ Navbar -->
<nav class="bg-white shadow-md p-4 flex justify-between items-center">
    <div class="flex items-center space-x-4">
        <button id="toggleSidebar" class="text-gray-700 text-2xl focus:outline-none">
            <i class="fas fa-bars"></i>
        </button>
        <img src="https://via.placeholder.com/40" alt="Logo" class="w-10 h-10 rounded-full">
        <span class="text-xl font-bold text-gray-700">إسم الشركة</span>
    </div>

    <div class="flex items-center space-x-4">
        <span class="text-gray-700 font-medium">مرحباً، <?php echo $_SESSION['username'] ?? 'مستخدم'; ?></span>
        <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-600 transition">تسجيل الخروج</a>
    </div>
</nav>
