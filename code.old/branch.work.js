








document.addEventListener("DOMContentLoaded", function () {
    loadRecentSuppliers();

    // إضافة مورد جديد
    document.getElementById("addSupplierForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("/inventory_project/public/index.php?controller=supplier&action=add", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message || data.error);
                if (!data.error) {
                    this.reset();
                    loadRecentSuppliers();
                }
            })
            .catch(error => console.error("Error:", error));
    });

    // تعديل مورد
    document.getElementById("supplierTable").addEventListener("click", function (e) {
        if (e.target.classList.contains("edit-supplier")) {
            let supplierId = e.target.dataset.id;
            let row = e.target.closest("tr");
            let name = prompt("اسم المورد الجديد:", row.cells[0].textContent);
            let phone = prompt("رقم الهاتف الجديد:", row.cells[1].textContent);
            let email = prompt("البريد الإلكتروني الجديد:", row.cells[2].textContent);

            if (name && phone && email) {
                let formData = new FormData();
                formData.append("id", supplierId);
                formData.append("name", name);
                formData.append("phone", phone);
                formData.append("email", email);

                fetch("/inventory_project/public/index.php?controller=supplier&action=edit", {
                    method: "POST",
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message || data.error);
                        if (!data.error) {
                            loadRecentSuppliers();
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }
        }
    });

    // حذف مورد
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
                        alert(data.message || data.error);
                        if (!data.error) {
                            e.target.closest("tr").remove();
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }
        }
    });

    // تحميل آخر 20 مورد عند فتح الصفحة
    function loadRecentSuppliers() {
        fetch("/inventory_project/public/index.php?controller=supplier&action=getRecent")
            .then(response => response.json())
            .then(data => {
                let tableBody = document.getElementById("supplierTable");
                tableBody.innerHTML = "";

                data.forEach(supplier => {
                    let row = `
                    <tr data-id="${supplier.id}">
                        <td class="border p-2">${supplier.name}</td>
                        <td class="border p-2">${supplier.phone}</td>
                        <td class="border p-2">${supplier.email}</td>
                        <td class="border p-2">
                            <button class="edit-supplier bg-yellow-500 text-white p-1 rounded" data-id="${supplier.id}">تعديل</button>
                            <button class="delete-supplier bg-red-500 text-white p-1 rounded" data-id="${supplier.id}">حذف</button>
                        </td>
                    </tr>
                `;
                    tableBody.innerHTML += row;
                });
            })
            .catch(error => console.error("Error:", error));
    }







    /**********************************/
    // فتح المودال وتعبئة البيانات عند الضغط على تعديل
    document.getElementById("supplierTable").addEventListener("click", function (e) {
        if (e.target.classList.contains("edit-supplier")) {
            let row = e.target.closest("tr");
            let supplierId = e.target.dataset.id;
            let name = row.cells[0].textContent;
            let phone = row.cells[1].textContent;
            let email = row.cells[2].textContent;

            document.getElementById("editSupplierId").value = supplierId;
            document.getElementById("editSupplierName").value = name;
            document.getElementById("editSupplierPhone").value = phone;
            document.getElementById("editSupplierEmail").value = email;

            document.getElementById("editSupplierModal").classList.remove("hidden");
        }
    });

    // إغلاق المودال
    document.getElementById("closeEditModal").addEventListener("click", function () {
        document.getElementById("editSupplierModal").classList.add("hidden");
    });

    // حفظ التعديلات عند الضغط على "حفظ"
    document.getElementById("editSupplierForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let supplierId = document.getElementById("editSupplierId").value;
        let name = document.getElementById("editSupplierName").value;
        let phone = document.getElementById("editSupplierPhone").value;
        let email = document.getElementById("editSupplierEmail").value;

        let formData = new FormData();
        formData.append("id", supplierId);
        formData.append("name", name);
        formData.append("phone", phone);
        formData.append("email", email);

        fetch("/inventory_project/public/index.php?controller=supplier&action=edit", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message || data.error);
                if (!data.error) {
                    document.getElementById("editSupplierModal").classList.add("hidden");
                    loadRecentSuppliers();
                }
            })
            .catch(error => console.error("Error:", error));
    });
    /**********************************/







});
