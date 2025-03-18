document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggleSidebarBtn = document.getElementById("toggleSidebar");
    const closeSidebarBtn = document.getElementById("closeSidebar");

    if (!sidebar || !toggleSidebarBtn || !closeSidebarBtn) {
        console.error("❌ خطأ: لم يتم العثور على العناصر المطلوبة.");
        return;
    }

    // ✅ فتح القائمة
    toggleSidebarBtn.addEventListener("click", function () {
        sidebar.classList.remove("closed");
    });

    // ✅ إغلاق القائمة
    closeSidebarBtn.addEventListener("click", function () {
        sidebar.classList.add("closed");
    });
});
