document.addEventListener("DOMContentLoaded", function () {
    loadRecentSuppliers();

    let responseMessage = document.getElementById("responseMessage");

    // ✅ إضافة مورد جديد
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

    // ✅ فتح المودال وتعبئة البيانات عند الضغط على تعديل
    document.getElementById("supplierTable").addEventListener("click", function (e) {
        if (e.target.classList.contains("edit-supplier")) {
            let row = e.target.closest("tr");
            document.getElementById("editSupplierId").value = e.target.dataset.id;
            document.getElementById("editSupplierName").value = row.cells[0].textContent;
            document.getElementById("editSupplierPhone").value = row.cells[1].textContent;
            document.getElementById("editSupplierEmail").value = row.cells[2].textContent;

            document.getElementById("editSupplierModal").classList.remove("hidden");
        }
    });

    // ✅ إغلاق المودال عند الضغط على إلغاء
    document.getElementById("closeEditModal").addEventListener("click", function () {
        document.getElementById("editSupplierModal").classList.add("hidden");
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

    // ✅ حذف مورد
    document.getElementById("supplierTable").addEventListener("click", function (e) {
        if (e.target.classList.contains("delete-supplier")) {
            let supplierId = e.target.dataset.id;

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
                            e.target.closest("tr").remove(); // حذف الصف فورًا من الجدول
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }
        }
    });

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
                    <td class="border p-2">
                        <button class="edit-supplier bg-yellow-500 text-white p-1 rounded" data-id="${supplier.id}">تعديل</button>
                        <button class="delete-supplier bg-red-500 text-white p-1 rounded" data-id="${supplier.id}">حذف</button>
                    </td>
                `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => console.error("Error:", error));
    }

    // ✅ تحسين عرض الرسائل بدلاً من `alert()`
    function showMessage(message, isSuccess) {
        responseMessage.textContent = message;
        responseMessage.classList.remove("text-green-500", "text-red-500");
        responseMessage.classList.add(isSuccess ? "text-green-500" : "text-red-500");
    }
});
