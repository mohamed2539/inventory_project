document.addEventListener("DOMContentLoaded", function () {
    initializeMaterialsModule();
});

function initializeMaterialsModule() {
    loadMaterials();
    loadBranches();
    loadSuppliers();
    setupMaterialForm();
    setupMaterialTableActions();
}

let responseMessage = document.getElementById("responseMessage");

// ✅ تحميل الفروع والموردين
function loadSelectData(url, selectIds, selectedId = null) {
    fetch(url)
        .then(response => response.json())
        .then(data => {
            selectIds.forEach(selectId => {
                let selectElement = document.getElementById(selectId);
                if (!selectElement) return;

                selectElement.innerHTML = '<option value="">اختر</option>';
                data.forEach(item => {
                    let option = document.createElement("option");
                    option.value = item.id;
                    option.textContent = item.name;
                    if (selectedId && selectedId == item.id) {
                        option.selected = true;
                    }
                    selectElement.appendChild(option);
                });
            });
        })
        .catch(error => console.error(`❌ خطأ في تحميل البيانات من ${url}:`, error));
}

function loadBranches(selectedBranchId = null) {
    loadSelectData("/inventory_project/public/index.php?controller=branch&action=getAll", ["editMaterialBranch", "branchSelect"], selectedBranchId);
}
function loadSuppliers(selectedSupplierId = null) {
    loadSelectData("/inventory_project/public/index.php?controller=supplier&action=getAll", ["editMaterialSupplier", "supplierSelect"], selectedSupplierId);
}

// ✅ تحميل المواد عند فتح الصفحة
function loadMaterials() {
    fetch("/inventory_project/public/index.php?controller=material&action=getRecent")
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById("materialTable");
            if (!tableBody) {
                console.error("❌ `materialTable` غير موجود في الـ DOM");
                return;
            }

            tableBody.innerHTML = "";

            data.forEach(material => {
                let row = document.createElement("tr");
                row.dataset.id = material.id;
                row.innerHTML = `
                    <td class="border p-2">${material.code}</td>
                    <td class="border p-2">${material.barcode || '—'}</td>
                    <td class="border p-2">${material.name}</td>
                    <td class="border p-2">${material.unit}</td>
                    <td class="border p-2">${material.quantity}</td>
                    <td class="border p-2">${material.price}</td>
                    <td class="border p-2 text-center">
                        <button class="edit-material bg-yellow-500 text-white p-2 rounded shadow-md transition-all duration-300 transform hover:scale-105" data-id="${material.id}">تعديل</button>
                        <button class="delete-material bg-red-500 text-white p-2 rounded shadow-md transition-all duration-300 transform hover:scale-105" data-id="${material.id}">حذف</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            if (typeof initializeUI === "function") {
                initializeUI();
            } else {
                console.warn("⚠️ `initializeUI()` غير معرف. تأكد من تحميل `ui.js` قبل `materials.js`.");
            }
        })
        .catch(error => console.error("❌ خطأ في تحميل المواد:", error));
}

// ✅ إضافة مادة جديدة (إصلاح استخدام `POST`)
function setupMaterialForm() {
    document.getElementById("addMaterialForm").addEventListener("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("/inventory_project/public/index.php?controller=material&action=add", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message || data.error, !data.error);
                if (!data.error) {
                    this.reset();
                    loadMaterials();
                }
            })
            .catch(error => console.error("❌ خطأ في إضافة المادة:", error));
    });
}

// ✅ إعداد الأحداث على الجدول (تعديل - حذف)
function setupMaterialTableActions() {
    document.getElementById("materialTable").addEventListener("click", function (e) {
        let target = e.target;

        if (target.classList.contains("edit-material")) {
            openEditMaterialModal(target.dataset.id);
        } else if (target.classList.contains("delete-material")) {
            deleteMaterial(target.dataset.id, target);
        }
    });

    document.getElementById("closeEditMaterialModal").addEventListener("click", function () {
        let modal = document.getElementById("editMaterialModal");
        gsap.to(modal, {
            y: -50,
            opacity: 0,
            duration: 0.3,
            ease: "power2.in",
            onComplete: () => modal.classList.add("hidden")
        });
    });
}

// ✅ حذف المادة (إصلاح المشكلة)
function deleteMaterial(materialId, button) {
    if (!confirm("هل أنت متأكد من حذف هذه المادة؟")) return;

    fetch("/inventory_project/public/index.php?controller=material&action=delete", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: materialId })
    })
        .then(response => response.json())
        .then(data => {
            showMessage(data.message || data.error, !data.error);
            if (!data.error) {
                gsap.to(button.closest("tr"), {
                    opacity: 0,
                    y: -10,
                    duration: 0.5,
                    ease: "power2.out",
                    onComplete: function () {
                        button.closest("tr").remove();
                    }
                });
            }
        })
        .catch(error => console.error("❌ خطأ في حذف المادة:", error));
}

function openEditMaterialModal(materialId) {
    fetch(`/inventory_project/public/index.php?controller=material&action=get&id=${materialId}`)
        .then(response => response.json())
        .then(material => {
            if (!material || !material.id) {
                console.error("❌ لم يتم العثور على بيانات المادة!");
                return;
            }

            console.log("✅ بيانات المادة المسترجعة:", material);

            document.getElementById("editMaterialId").value = material.id;
            document.getElementById("editMaterialCode").value = material.code;
            document.getElementById("editMaterialBarcode").value = material.barcode || '';
            document.getElementById("editMaterialName").value = material.name;
            document.getElementById("editMaterialUnit").value = material.unit;
            document.getElementById("editMaterialQuantity").value = material.quantity;
            document.getElementById("editMaterialPrice").value = material.price;

            // ✅ تعبئة قيم min_quantity و max_quantity
            document.getElementById("editMaterialMinQuantity").value = material.min_quantity;
            document.getElementById("editMaterialMaxQuantity").value = material.max_quantity;

            loadBranches(material.branch_id);
            loadSuppliers(material.supplier_id);

            let modal = document.getElementById("editMaterialModal");
            modal.classList.remove("hidden");
            gsap.fromTo(modal, { y: -50, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, ease: "power2.out" });
        })
        .catch(error => console.error("❌ خطأ في تحميل بيانات المادة:", error));
}

document.getElementById("editMaterialForm").addEventListener("submit", function (e) {
    e.preventDefault();

    let formData = new FormData(this); // ✅ تأكيد إرسال البيانات كـ `FormData`

    fetch("/inventory_project/public/index.php?controller=material&action=edit", {
        method: "POST", // ✅ التأكد من استخدام `POST`
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            showMessage(data.message || data.error, !data.error);
            if (!data.error) {
                document.getElementById("editMaterialModal").classList.add("hidden");
                loadMaterials(); // ✅ تحديث الجدول بعد التعديل
            }
        })
        .catch(error => console.error("❌ خطأ في تعديل المادة:", error));
});



// ✅ تحسين عرض الرسائل
function showMessage(message, isSuccess) {
    responseMessage.textContent = message;
    responseMessage.classList.remove("text-green-500", "text-red-500");
    responseMessage.classList.add(isSuccess ? "text-green-500" : "text-red-500");

    setTimeout(() => {
        responseMessage.textContent = "";
    }, 3000);
}
