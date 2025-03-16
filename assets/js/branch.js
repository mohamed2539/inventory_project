document.addEventListener("DOMContentLoaded", function () {
    loadRecentBranches(); // تحميل البيانات عند فتح الصفحة

    // إضافة فرع جديد بدون إعادة تحميل الصفحة
    document.getElementById("addBranchForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("/inventory_project/public/index.php?controller=branch&action=add", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message || data.error);
                if (!data.error) {
                    this.reset();
                    loadRecentBranches(); // تحديث القائمة فورًا بعد الإضافة
                }
            })
            .catch(error => console.error("Error:", error));
    });

    // تعديل فرع مباشرة من الجدول
    document.getElementById("branchTable").addEventListener("click", function (e) {
        if (e.target.classList.contains("edit-branch")) {
            let branchId = e.target.dataset.id;
            let row = e.target.closest("tr");
            let name = prompt("اسم الفرع الجديد:", row.cells[0].textContent);
            let address = prompt("العنوان الجديد:", row.cells[1].textContent);
            let phone = prompt("رقم الهاتف الجديد:", row.cells[2].textContent);
            let email = prompt("البريد الإلكتروني الجديد:", row.cells[3].textContent);

            if (name && address && phone && email) {
                let formData = new FormData();
                formData.append("id", branchId);
                formData.append("name", name);
                formData.append("address", address);
                formData.append("phone", phone);
                formData.append("email", email);
                formData.append("manager_name", row.cells[4]?.textContent || ""); // إذا كان هناك حقل مدير

                fetch("/inventory_project/public/index.php?controller=branch&action=edit", {
                    method: "POST",
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message || data.error);
                        if (!data.error) {
                            loadRecentBranches(); // تحديث الجدول بعد التعديل
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }
        }
    });

    // حذف فرع مباشرة من الجدول بدون إعادة تحميل الصفحة
    document.getElementById("branchTable").addEventListener("click", function (e) {
        if (e.target.classList.contains("delete-branch")) {
            let branchId = e.target.dataset.id;

            if (confirm("هل أنت متأكد من حذف هذا الفرع؟")) {
                fetch("/inventory_project/public/index.php?controller=branch&action=delete", {
                    method: "POST",
                    body: JSON.stringify({ id: branchId }),
                    headers: { "Content-Type": "application/json" }
                })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message || data.error);
                        if (!data.error) {
                            e.target.closest("tr").remove(); // حذف الصف فورًا من الجدول
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }
        }
    });

    // تحميل أحدث 20 فرع عند فتح الصفحة
    function loadRecentBranches() {
        fetch("/inventory_project/public/index.php?controller=branch&action=getRecent")
            .then(response => response.json())
            .then(data => {
                let tableBody = document.getElementById("branchTable");
                tableBody.innerHTML = "";

                data.forEach(branch => {
                    let row = `
                        <tr data-id="${branch.id}">
                            <td class="border p-2">${branch.name}</td>
                            <td class="border p-2">${branch.address}</td>
                            <td class="border p-2">${branch.phone}</td>
                            <td class="border p-2">${branch.email}</td>
                            <td class="border p-2">
                                <button class="edit-branch bg-yellow-500 text-white p-1 rounded" data-id="${branch.id}">تعديل</button>
                                <button class="delete-branch bg-red-500 text-white p-1 rounded" data-id="${branch.id}">حذف</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            })
            .catch(error => console.error("Error:", error));
    }
});
