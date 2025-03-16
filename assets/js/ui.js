document.addEventListener("DOMContentLoaded", function () {
    initializeUI();
});

function initializeUI() {
    // ✅ إضافة تأثير `fadeIn` عند تحميل الجدول
    document.querySelectorAll("#supplierTable tr").forEach((row, index) => {
        gsap.fromTo(row, { y: -20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.3, delay: index * 0.05 });
    });

    // ✅ فتح المودال عند الضغط على تعديل
    document.getElementById("supplierTable").addEventListener("click", function (e) {
        if (e.target.classList.contains("edit-supplier")) {
            let supplierId = e.target.dataset.id;
            let row = document.querySelector(`tr[data-id="${supplierId}"]`);
            document.getElementById("editSupplierId").value = supplierId;
            document.getElementById("editSupplierName").value = row.cells[0].textContent.trim();
            document.getElementById("editSupplierPhone").value = row.cells[1].textContent.trim();
            document.getElementById("editSupplierEmail").value = row.cells[2].textContent.trim();

            let modal = document.getElementById("editSupplierModal");
            modal.classList.remove("hidden");
            gsap.fromTo(modal, { y: -50, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, ease: "power2.out" });
        }
    });

    // ✅ إغلاق المودال عند الضغط على زر "إلغاء"
    document.getElementById("closeEditModal").addEventListener("click", function () {
        let modal = document.getElementById("editSupplierModal");
        gsap.to(modal, { y: -50, opacity: 0, duration: 0.3, ease: "power2.in", onComplete: () => modal.classList.add("hidden") });
    });
}
