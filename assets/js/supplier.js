document.addEventListener("DOMContentLoaded", function () {
    initializeSupplierModule();
});

function initializeSupplierModule() {
    loadRecentSuppliers();
    setupFormSubmission();
    setupTableActions();
}

let responseMessage = document.getElementById("responseMessage");

// ✅ تحميل آخر 20 مورد عند فتح الصفحة
function loadRecentSuppliers() {
    fetch("/inventory_project/public/index.php?controller=supplier&action=getRecent")
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById("supplierTable");
            tableBody.innerHTML = "";

            data.forEach(supplier => {
                let row = document.createElement("tr");
                row.dataset.id = supplier.id;
                row.innerHTML = `
                <td class="border p-2">${supplier.name}</td>
                <td class="border p-2">${supplier.phone}</td>
                <td class="border p-2">${supplier.email}</td>
                <td class="border p-2 text-center">
                    <button class="edit-supplier bg-yellow-500 text-white p-2 rounded shadow-md transition-all duration-300 transform hover:scale-105" data-id="${supplier.id}">تعديل</button>
                    <button class="delete-supplier bg-red-500 text-white p-2 rounded shadow-md transition-all duration-300 transform hover:scale-105" data-id="${supplier.id}">حذف</button>
                </td>
            `;
                tableBody.appendChild(row);
            });

            initializeUI();
        })
        .catch(error => console.error("Error:", error));
}

// ✅ إعداد زرار الإضافة
function setupFormSubmission() {
    document.getElementById("addSupplierForm").addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("/inventory_project/public/index.php?controller=supplier&action=add", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message || data.error, !data.error);
                if (!data.error) {
                    this.reset();
                    loadRecentSuppliers();
                }
            })
            .catch(error => console.error("Error:", error));
    });
}

// ✅ إعداد الأحداث على الجدول (تعديل - حذف)
function setupTableActions() {
    document.getElementById("supplierTable").addEventListener("click", function (e) {
        let target = e.target;

        if (target.classList.contains("edit-supplier")) {
            openEditModal(target.dataset.id);
        } else if (target.classList.contains("delete-supplier")) {
            deleteSupplier(target.dataset.id, target);
        }
    });

    // ✅ إغلاق المودال عند الضغط على إلغاء
    document.getElementById("closeEditModal").addEventListener("click", function () {
        let modal = document.getElementById("editSupplierModal");
        gsap.to(modal, { y: -50, opacity: 0, duration: 0.3, ease: "power2.in", onComplete: () => modal.classList.add("hidden") });
    });

    // ✅ حفظ التعديلات عند الضغط على "حفظ"
    document.getElementById("editSupplierForm").addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("/inventory_project/public/index.php?controller=supplier&action=edit", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message || data.error, !data.error);
                if (!data.error) {
                    document.getElementById("editSupplierModal").classList.add("hidden");
                    loadRecentSuppliers();
                }
            })
            .catch(error => console.error("Error:", error));
    });
}

// ✅ فتح المودال وتعبئة البيانات عند الضغط على تعديل
function openEditModal(supplierId) {
    let row = document.querySelector(`tr[data-id="${supplierId}"]`);
    document.getElementById("editSupplierId").value = supplierId;
    document.getElementById("editSupplierName").value = row.cells[0].textContent;
    document.getElementById("editSupplierPhone").value = row.cells[1].textContent;
    document.getElementById("editSupplierEmail").value = row.cells[2].textContent;

    let modal = document.getElementById("editSupplierModal");
    modal.classList.remove("hidden");
    gsap.fromTo(modal, { y: -50, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, ease: "power2.out" });
}

// ✅ حذف المورد
function deleteSupplier(supplierId, button) {
    if (confirm("هل أنت متأكد من حذف هذا المورد؟")) {
        fetch("/inventory_project/public/index.php?controller=supplier&action=delete", {
            method: "POST",
            body: JSON.stringify({ id: supplierId }),
            headers: { "Content-Type": "application/json" }
        })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message || data.error, !data.error);
                if (!data.error) {
                    button.closest("tr").remove();
                }
            })
            .catch(error => console.error("Error:", error));
    }
}

// ✅ تحسين عرض الرسائل
function showMessage(message, isSuccess) {
    responseMessage.textContent = message;
    responseMessage.classList.remove("text-green-500", "text-red-500");
    responseMessage.classList.add(isSuccess ? "text-green-500" : "text-red-500");
}
