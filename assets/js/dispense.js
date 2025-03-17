document.addEventListener("DOMContentLoaded", function () {
    loadTransactions(); // ✅ تحميل العمليات السابقة عند تحميل الصفحة

    document.getElementById("dispenseCode").addEventListener("blur", fetchMaterial);

    document.getElementById("confirmDispense").addEventListener("click", function () {
        let materialId = this.dataset.materialId;
        let dispenseQuantity = document.getElementById("dispenseQuantity").value;

        // ✅ التحقق من أن المادة تم تحميلها
        if (!materialId) {
            showMessage("الرجاء إدخال كود صحيح للمادة!", false);
            return;
        }

        // ✅ التحقق من أن الكمية صالحة
        if (!dispenseQuantity || dispenseQuantity <= 0) {
            showMessage("يرجى إدخال كمية صالحة!", false);
            return;
        }

        console.log(`🚀 إرسال طلب الصرف: المادة ID: ${materialId}, الكمية: ${dispenseQuantity}`);

        // ✅ تنفيذ طلب الصرف
        fetch("/inventory_project/public/index.php?controller=transaction&action=dispense", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ material_id: materialId, quantity: dispenseQuantity, user_id: 1 })
        })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message || data.error, !data.error);
                if (!data.error) {
                    console.log("✅ تم الصرف بنجاح!");
                    document.getElementById("materialQuantity").textContent -= dispenseQuantity;
                    document.getElementById("dispenseQuantity").value = "";
                    loadTransactions();
                }
            })
            .catch(error => console.error("❌ خطأ في صرف المادة:", error));
    });
});


/** ✅ جلب بيانات المادة بناءً على الكود **/
function fetchMaterial() {
    let materialCode = document.getElementById("dispenseCode").value;
    if (!materialCode) return;

    fetch(`/inventory_project/public/index.php?controller=material&action=getByCode&code=${materialCode}`)
        .then(response => response.json())
        .then(material => {
            if (!material || material.error) {
                showMessage(material.error || "لم يتم العثور على المادة!", false);
                return;
            }

            console.log("📢 تم تحميل بيانات المادة:", material);

            document.getElementById("materialName").textContent = material.name;
            document.getElementById("materialUnit").textContent = material.unit;
            document.getElementById("materialQuantity").textContent = material.quantity;
            document.getElementById("materialDetails").classList.remove("hidden");

            // ✅ تعيين المادة المختارة في الزر
            document.getElementById("confirmDispense").dataset.materialId = material.id;
        })
        .catch(error => console.error("❌ خطأ في تحميل بيانات المادة:", error));
}

/** ✅ تحسين عرض الرسائل **/


function loadTransactions() {
    fetch("/inventory_project/public/index.php?controller=transaction&action=getRecentTransactions")
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById("transactionTable");
            tableBody.innerHTML = "";

            if (!data.length) {
                tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-gray-500">لا توجد عمليات صرف بعد</td></tr>`;
                return;
            }

            data.forEach(transaction => {
                let row = document.createElement("tr");
                row.innerHTML = `
                    <td class="border p-2">${transaction.material_name}</td>
                    <td class="border p-2">${transaction.user_name}</td>
                    <td class="border p-2 text-center">${transaction.quantity}</td>
                    <td class="border p-2">${transaction.created_at}</td>
                    <td class="border p-2 text-center">
                        <button class="edit-transaction bg-yellow-500 text-white p-2 rounded shadow-md transition-all duration-300 transform hover:scale-105" data-id="${transaction.id}">✏️ تعديل</button>
                        <button class="delete-transaction bg-red-500 text-white p-2 rounded shadow-md transition-all duration-300 transform hover:scale-105" data-id="${transaction.id}">🗑️ حذف</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            setupTransactionActions();
        })
        .catch(error => console.error("❌ خطأ في تحميل العمليات:", error));
}


// this code belwo working fine
/*function loadTransactions() {
    fetch("/inventory_project/public/index.php?controller=transaction&action=getRecentTransactions")
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById("transactionTable");
            if (!tableBody) {
                console.error("⚠️ `#transactionTable` غير موجود في الصفحة.");
                return;
            }

            tableBody.innerHTML = ""; // ✅ مسح الجدول قبل التحديث

            if (!data.length) {
                tableBody.innerHTML = `<tr><td colspan="4" class="text-center border p-2">لا توجد عمليات صرف بعد</td></tr>`;
                return;
            }

            data.forEach(transaction => {
                let row = `
                    <tr>
                        <td class="border p-2">${transaction.material_name}</td>
                        <td class="border p-2">${transaction.user_name}</td>
                        <td class="border p-2">${transaction.quantity}</td>
                        <td class="border p-2">${transaction.created_at}</td>
                    </tr>`;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => console.error("❌ خطأ في تحميل العمليات:", error));
}*/

// ✅ تحسين عرض الرسائل
function showMessage(message, isSuccess = true) {
    let responseMessage = document.getElementById("responseMessage");
    responseMessage.textContent = message;
    responseMessage.classList.remove("text-green-500", "text-red-500");
    responseMessage.classList.add(isSuccess ? "text-green-500" : "text-red-500");

    // ✅ إضافة تأثير Fade-In/Out
    responseMessage.style.opacity = "1";
    setTimeout(() => {
        responseMessage.style.opacity = "0";
    }, 3000);
}


/******for edit and delete****/


function setupTransactionActions() {
    let transactionTable = document.getElementById("transactionTable");

    if (!transactionTable) {
        console.error("❌ `#transactionTable` غير موجود في الصفحة.");
        return;
    }

    transactionTable.addEventListener("click", function (e) {
        let target = e.target;

        if (target.classList.contains("edit-transaction")) {
            let transactionId = target.dataset.id;
            openEditTransactionModal(transactionId);
        }
    });

    let closeModalBtn = document.getElementById("closeEditTransactionModal");
    if (closeModalBtn) {
        closeModalBtn.addEventListener("click", function () {
            document.getElementById("editTransactionModal").classList.add("hidden");
        });
    } else {
        console.error("❌ `#closeEditTransactionModal` غير موجود في الصفحة!");
    }
}



/*function setupTransactionActions() {
    document.querySelectorAll(".edit-transaction").forEach(button => {
        button.addEventListener("click", function () {
            let transactionId = this.dataset.id;

            // ✅ جلب بيانات العملية من السيرفر
            fetch(`/inventory_project/public/index.php?controller=transaction&action=get&id=${transactionId}`)
                .then(response => response.json())
                .then(transaction => {
                    if (!transaction || transaction.error) {
                        showMessage(transaction.error || "خطأ في جلب البيانات!", false);
                        return;
                    }

                    document.getElementById("editTransactionId").value = transaction.id;
                    document.getElementById("editMaterialName").value = transaction.material_name;
                    document.getElementById("editTransactionQuantity").value = transaction.quantity;

                    // ✅ عرض المودال
                    let modal = document.getElementById("editTransactionModal");
                    modal.classList.remove("hidden");
                    gsap.fromTo(modal, { y: -50, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, ease: "power2.out" });
                })
                .catch(error => console.error("❌ خطأ في جلب بيانات المعاملة:", error));
        });
    });

    // ✅ إغلاق المودال عند الضغط على "إلغاء"
    document.getElementById("closeEditTransactionModal").addEventListener("click", function () {
        let modal = document.getElementById("editTransactionModal");
        gsap.to(modal, { y: -50, opacity: 0, duration: 0.3, ease: "power2.in", onComplete: () => modal.classList.add("hidden") });
    });

    // ✅ حذف معاملة عند الضغط على "حذف"
    document.querySelectorAll(".delete-transaction").forEach(button => {
        button.addEventListener("click", function () {
            let transactionId = this.dataset.id;

            if (confirm("هل أنت متأكد من حذف هذه المعاملة؟")) {
                fetch(`/inventory_project/public/index.php?controller=transaction&action=delete`, {
                    method: "POST",
                    body: JSON.stringify({ id: transactionId }),
                    headers: { "Content-Type": "application/json" }
                })
                    .then(response => response.json())
                    .then(data => {
                        showMessage(data.message || data.error, !data.error);
                        if (!data.error) {
                            loadTransactions();
                        }
                    })
                    .catch(error => console.error("❌ خطأ في حذف المعاملة:", error));
            }
        });
    });
}*/


//****************** To save updated

document.getElementById("editTransactionForm").addEventListener("submit", function (e) {
    e.preventDefault();

    let formData = new FormData(this);
    console.log("📢 البيانات المرسلة إلى السيرفر:", Object.fromEntries(formData.entries()));

    fetch("/inventory_project/public/index.php?controller=transaction&action=update", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            showMessage(data.message || data.error, !data.error);
            if (!data.error) {
                document.getElementById("editTransactionModal").classList.add("hidden");

                // ✅ تحديث الصف مباشرة بدون `Reload`
                updateTransactionRow(formData);
            }
        })
        .catch(error => console.error("❌ خطأ في تعديل المعاملة:", error));
});

function openEditTransactionModal(transactionId) {
    fetch(`/inventory_project/public/index.php?controller=transaction&action=get&id=${transactionId}`)
        .then(response => response.json())
        .then(transaction => {
            if (transaction.error) {
                showMessage(transaction.error, false);
                return;
            }

            let editTransactionId = document.getElementById("editTransactionId");
            let editTransactionMaterialId = document.getElementById("editTransactionMaterialId");
            let editTransactionUserId = document.getElementById("editTransactionUserId");
            let editTransactionMaterial = document.getElementById("editTransactionMaterial");
            let editTransactionUser = document.getElementById("editTransactionUser");
            let editTransactionQuantity = document.getElementById("editTransactionQuantity");

            if (!editTransactionId || !editTransactionMaterialId || !editTransactionUserId || !editTransactionMaterial || !editTransactionUser || !editTransactionQuantity) {
                console.error("❌ بعض الحقول غير موجودة في المودال!");
                return;
            }

            // ✅ تعبئة البيانات في الحقول المناسبة
            editTransactionId.value = transaction.id;
            editTransactionMaterialId.value = transaction.material_id;
            editTransactionUserId.value = transaction.user_id;
            editTransactionMaterial.textContent = transaction.material_name;
            editTransactionUser.textContent = transaction.user_name;
            editTransactionQuantity.value = transaction.quantity;

            document.getElementById("editTransactionModal").classList.remove("hidden");
        })
        .catch(error => console.error("❌ خطأ في جلب بيانات المعاملة:", error));
}

function updateTransactionRow(formData) {
    let transactionId = formData.get("id");
    let newQuantity = formData.get("quantity");

    let row = document.querySelector(`tr[data-id="${transactionId}"]`);
    if (row) {
        row.querySelector(".transaction-quantity").textContent = newQuantity;
    } else {
        console.warn("⚠️ الصف مش موجود! إعادة تحميل الجدول...");
        loadTransactions(); // ✅ إعادة تحميل الجدول بالكامل
    }
}



function deleteTransaction(transactionId, button) {
    if (!confirm("هل أنت متأكد من حذف هذه العملية؟")) return;

    fetch(`/inventory_project/public/index.php?controller=transaction&action=delete`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: transactionId })
    })
        .then(response => response.json())
        .then(data => {
            showMessage(data.message || data.error, !data.error);
            if (!data.error) {
                button.closest("tr").remove();
            }
        })
        .catch(error => console.error("❌ خطأ في حذف المعاملة:", error));
}

// ✅ إضافة حدث الاستماع لزرار الحذف
document.getElementById("transactionTable").addEventListener("click", function (e) {
    if (e.target.classList.contains("delete-transaction")) {
        deleteTransaction(e.target.dataset.id, e.target);
    }
});