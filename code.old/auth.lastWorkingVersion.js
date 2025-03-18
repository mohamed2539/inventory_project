document.addEventListener("DOMContentLoaded", function () {
    loadUsers(); // ✅ تحميل المستخدمين عند فتح الصفحة

    setupAuthForms(); // ✅ تجهيز الفورمات للتسجيل والتعديل
});

// ✅ تحميل المستخدمين وعرضهم في الجدول
function loadUsers() {
    fetch("/inventory_project/public/index.php?controller=user&action=getAll")
        .then(response => response.json())
        .then(data => {
            let userTable = document.getElementById("userTable");
            userTable.innerHTML = "";

            data.forEach(user => {
                let row = document.createElement("tr");
                row.dataset.id = user.id;
                row.innerHTML = `
                    <td class="border p-2">${user.full_name}</td>
                    <td class="border p-2">${user.username}</td>
                    <td class="border p-2">${user.branch_name || '—'}</td>
                    <td class="border p-2">${user.role}</td>
                    <td class="border p-2">${user.status === 'active' ? 'مفعل ✅' : 'غير مفعل ❌'}</td>
                    <td class="border p-2 text-center">
                        <button class="edit-user bg-yellow-500 text-white p-1 rounded" data-id="${user.id}">تعديل</button>
                        <button class="delete-user bg-red-500 text-white p-1 rounded" data-id="${user.id}">حذف</button>
                    </td>
                `;
                userTable.appendChild(row);
            });

            setupTableActions();
        })
        .catch(error => console.error("❌ خطأ في تحميل المستخدمين:", error));
}
function loadBranches() {
    fetch("/inventory_project/public/index.php?controller=branch&action=getAll")
        .then(response => response.json())
        .then(data => {
            let branchSelects = document.querySelectorAll("#userBranch, #editUserBranch");
            branchSelects.forEach(branchSelect => {
                branchSelect.innerHTML = '<option value="">اختر الفرع</option>';
                data.forEach(branch => {
                    let option = document.createElement("option");
                    option.value = branch.id;
                    option.textContent = branch.name;
                    branchSelect.appendChild(option);
                });
            });
        })
        .catch(error => console.error("❌ خطأ في تحميل الفروع:", error));
}

// ✅ تجهيز التعامل مع تسجيل وتعديل المستخدمين
function setupAuthForms() {
    // تسجيل الدخول
    document.getElementById("loginForm").addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("/inventory_project/public/index.php?controller=auth&action=login", {
            method: "POST", // ✅ استخدم `POST` بدل `GET`
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message || data.error, !data.error);
                if (!data.error) {
                    window.location.href = "/inventory_project/dashboard.php"; // ✅ تحويل المستخدم للصفحة الرئيسية
                }
            })
            .catch(error => console.error("❌ خطأ في تسجيل الدخول:", error));
    });

    document.getElementById("editUserForm").addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        fetch("/inventory_project/public/index.php?controller=user&action=update", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message || data.error, !data.error);
                if (!data.error) {
                    document.getElementById("editUserModal").classList.add("hidden");
                    loadUsers();
                }
            })
            .catch(error => console.error("❌ خطأ في تعديل المستخدم:", error));
    });
}

// ✅ التعامل مع الجدول (تعديل - حذف)
function setupTableActions() {
    document.getElementById("userTable").addEventListener("click", function (e) {
        let target = e.target;

        if (target.classList.contains("edit-user")) {
            openEditUserModal(target.dataset.id);
        } else if (target.classList.contains("delete-user")) {
            deleteUser(target.dataset.id, target);
        }
    });

    document.getElementById("closeEditUserModal").addEventListener("click", function () {
        document.getElementById("editUserModal").classList.add("hidden");
    });
}

// ✅ فتح المودال وتعبئة البيانات عند الضغط على تعديل
function openEditUserModal(userId) {
    fetch(`/inventory_project/public/index.php?controller=user&action=get&id=${userId}`)
        .then(response => response.json())
        .then(user => {
            document.getElementById("editUserId").value = user.id;
            document.getElementById("editUserFullName").value = user.full_name;
            document.getElementById("editUserUsername").value = user.username;
            document.getElementById("editUserBranch").value = user.branch_id || '';
            document.getElementById("editUserRole").value = user.role;
            document.getElementById("editUserStatus").value = user.status;

            let modal = document.getElementById("editUserModal");
            modal.classList.remove("hidden");
            gsap.fromTo(modal, { y: -50, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, ease: "power2.out" });
        })
        .catch(error => console.error("❌ خطأ في تحميل بيانات المستخدم:", error));
}

// ✅ حذف المستخدم
function deleteUser(userId, button) {
    if (confirm("هل أنت متأكد من حذف هذا المستخدم؟")) {
        fetch("/inventory_project/public/index.php?controller=user&action=delete", {
            method: "POST",
            body: JSON.stringify({ id: userId }),
            headers: { "Content-Type": "application/json" }
        })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message || data.error, !data.error);
                if (!data.error) {
                    button.closest("tr").remove();
                }
            })
            .catch(error => console.error("❌ خطأ في حذف المستخدم:", error));
    }
}
document.getElementById("registerUserForm").addEventListener("submit", function (e) {
    e.preventDefault();
    let formData = new FormData(this);

    fetch("/inventory_project/public/index.php?controller=user&action=register", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            showMessage(data.message || data.error, !data.error);
            if (!data.error) {
                this.reset();
                loadUsers(); // ✅ إعادة تحميل المستخدمين في الجدول مباشرةً
            }
        })
        .catch(error => console.error("❌ خطأ في تسجيل المستخدم:", error));
});

// ✅ تحسين عرض الرسائل
function showMessage(message, isSuccess) {
    let responseMessage = document.getElementById("responseMessage");
    responseMessage.textContent = message;
    responseMessage.classList.remove("text-green-500", "text-red-500");
    responseMessage.classList.add(isSuccess ? "text-green-500" : "text-red-500");

    setTimeout(() => {
        responseMessage.textContent = "";
    }, 3000);
}




document.addEventListener("DOMContentLoaded", function () {
    const loginTab = document.getElementById("loginTab");
    const registerTab = document.getElementById("registerTab");
    const loginForm = document.getElementById("loginForm");
    const registerForm = document.getElementById("registerForm");

    loginTab.addEventListener("click", function () {
        toggleTab(true);
    });

    registerTab.addEventListener("click", function () {
        toggleTab(false);
    });

    function toggleTab(isLogin) {
        let activeForm = isLogin ? loginForm : registerForm;
        let inactiveForm = isLogin ? registerForm : loginForm;

        // ✅ تحريك التابات بالتدرج
        gsap.to(inactiveForm, { opacity: 0, y: 10, duration: 0.3, onComplete: () => {
                inactiveForm.classList.add("hidden");
                activeForm.classList.remove("hidden");
                gsap.fromTo(activeForm, { opacity: 0, y: -10 }, { opacity: 1, y: 0, duration: 0.5 });
            }});
    }
});

