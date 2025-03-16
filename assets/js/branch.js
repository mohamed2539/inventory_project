document.addEventListener("DOMContentLoaded", function () {
    initializeBranchModule();
});

function initializeBranchModule() {
    loadRecentBranches();
    setupBranchForm();
    setupBranchTableActions();
}

let responseMessage = document.getElementById("responseMessage");

// ✅ تحميل آخر 20 فرع عند فتح الصفحة
function loadRecentBranches() {
    fetch("/inventory_project/public/index.php?controller=branch&action=getRecent")
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById("branchTable");
            tableBody.innerHTML = "";

            data.forEach(branch => {
                let row = document.createElement("tr");
                row.dataset.id = branch.id;
                row.innerHTML = `
                <td class="border p-2">${branch.name}</td>
                <td class="border p-2">${branch.address}</td>
                <td class="border p-2">${branch.phone}</td>
                <td class="border p-2">${branch.email || '—'}</td>
                <td class="border p-2">${branch.manager_name || '—'}</td>
                <td class="border p-2">${branch.status === 'active' ? '✅ نشط' : '❌ غير نشط'}</td>
                <td class="border p-2">${branch.notes || '—'}</td>
                <td class="border p-2 text-center">
                    <button class="edit-branch bg-yellow-500 text-white p-2 rounded shadow-md transition-all duration-300 transform hover:scale-105" data-id="${branch.id}">تعديل</button>
                    <button class="delete-branch bg-red-500 text-white p-2 rounded shadow-md transition-all duration-300 transform hover:scale-105" data-id="${branch.id}">حذف</button>
                </td>
            `;
                tableBody.appendChild(row);
            });

            initializeUI(); // 🟢 تأكد من إعادة تحميل UI بعد تحديث البيانات
        })
        .catch(error => console.error("Error:", error));
}

// ✅ إعداد زر الإضافة
function setupBranchForm() {
    document.getElementById("addBranchForm").addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("/inventory_project/public/index.php?controller=branch&action=add", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message || data.error, !data.error);
                if (!data.error) {
                    this.reset();
                    loadRecentBranches();
                }
            })
            .catch(error => console.error("Error:", error));
    });
}

// ✅ إعداد الأحداث على الجدول (تعديل - حذف)
function setupBranchTableActions() {
    document.getElementById("branchTable").addEventListener("click", function (e) {
        let target = e.target;

        if (target.classList.contains("edit-branch")) {
            openEditBranchModal(target.dataset.id);
        } else if (target.classList.contains("delete-branch")) {
            deleteBranch(target.dataset.id, target);
        }
    });

    // ✅ إغلاق المودال عند الضغط على إلغاء
    document.getElementById("closeEditBranchModal").addEventListener("click", function () {
        let modal = document.getElementById("editBranchModal");
        gsap.to(modal, { y: -50, opacity: 0, duration: 0.3, ease: "power2.in", onComplete: () => modal.classList.add("hidden") });
    });

    // ✅ حفظ التعديلات عند الضغط على "حفظ"
    document.getElementById("editBranchForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        console.log("📢 البيانات المرسلة إلى السيرفر:", Object.fromEntries(formData.entries()));

        fetch("/inventory_project/public/index.php?controller=branch&action=edit", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message || data.error, !data.error);
                if (!data.error) {
                    document.getElementById("editBranchModal").classList.add("hidden");
                    loadRecentBranches();
                }
            })
            .catch(error => console.error("Error:", error));
    });
}

// ✅ فتح المودال وتعبئة البيانات عند الضغط على تعديل
function openEditBranchModal(branchId) {
    let row = document.querySelector(`tr[data-id="${branchId}"]`);
    document.getElementById("editBranchId").value = branchId;
    document.getElementById("editBranchName").value = row.cells[0].textContent.trim();
    document.getElementById("editBranchAddress").value = row.cells[1].textContent.trim();
    document.getElementById("editBranchPhone").value = row.cells[2].textContent.trim();
    document.getElementById("editBranchEmail").value = row.cells[3]?.textContent.trim() || "";
    document.getElementById("editBranchManager").value = row.cells[4]?.textContent.trim() || "";
    document.getElementById("editBranchStatus").value = row.cells[5]?.textContent.includes("نشط") ? "active" : "inactive";
    document.getElementById("editBranchNotes").value = row.cells[6]?.textContent.trim() || "";

    let modal = document.getElementById("editBranchModal");
    modal.classList.remove("hidden");
    gsap.fromTo(modal, { y: -50, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, ease: "power2.out" });
}

// ✅ حذف الفرع
function deleteBranch(branchId, button) {
    if (confirm("هل أنت متأكد من حذف هذا الفرع؟")) {
        fetch("/inventory_project/public/index.php?controller=branch&action=delete", {
            method: "POST",
            body: JSON.stringify({ id: branchId }),
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
