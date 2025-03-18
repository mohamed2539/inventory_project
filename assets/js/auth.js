document.addEventListener("DOMContentLoaded", function () {
    console.log("📢 الصفحة تم تحميلها بالكامل!");
    loadUsers();
    setupAuthForms();
    loadBranches(); // ✅ تحميل الفروع للاختيار عند التسجيل
});
document.addEventListener("DOMContentLoaded", function () {
    console.log("📢 الصفحة تم تحميلها بالكامل!");

    // ✅ تعريف عناصر التابات والنماذج
    const loginTab = document.getElementById("loginTab");
    const registerTab = document.getElementById("registerTab");
    const loginContainer = document.getElementById("loginContainer");
    const registerContainer = document.getElementById("registerContainer");

    if (!loginTab || !registerTab || !loginContainer || !registerContainer) {
        console.error("❌ لم يتم العثور على أحد عناصر التابات!");
        return;
    }

    // ✅ التبديل بين التابات عند الضغط
    loginTab.addEventListener("click", function () {
        toggleTab(true);
    });

    registerTab.addEventListener("click", function () {
        toggleTab(false);
    });

    function toggleTab(isLogin) {
        let activeForm = isLogin ? loginContainer : registerContainer;
        let inactiveForm = isLogin ? registerContainer : loginContainer;

        // ✅ تغيير ألوان التابات
        loginTab.classList.toggle("bg-white", isLogin);
        loginTab.classList.toggle("bg-gray-200", !isLogin);
        registerTab.classList.toggle("bg-white", !isLogin);
        registerTab.classList.toggle("bg-gray-200", isLogin);

        // ✅ تحريك التابات بالتدرج
        gsap.to(inactiveForm, { opacity: 0, y: 10, duration: 0.3, onComplete: () => {
                inactiveForm.classList.add("hidden");
                activeForm.classList.remove("hidden");
                gsap.fromTo(activeForm, { opacity: 0, y: -10 }, { opacity: 1, y: 0, duration: 0.5 });
            }});
    }
});


// ✅ تحميل المستخدمين في الجدول
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
                    <td class="border p-2">${user.status === 'active' ? '✅ مفعل' : '❌ غير مفعل'}</td>
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

// ✅ تحميل الفروع
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

// ✅ تجهيز نماذج المصادقة
function setupAuthForms() {
    let loginForm = document.getElementById("loginForm");
    let registerForm = document.getElementById("registerUserForm");

    if (!loginForm || !registerForm) {
        console.error("❌ لم يتم العثور على نماذج تسجيل الدخول أو التسجيل!");
        return;
    }

    loginForm.addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(loginForm);

        console.log("📢 بيانات تسجيل الدخول:", Object.fromEntries(formData.entries())); // ✅ عرض البيانات قبل الإرسال

        fetch("/inventory_project/public/index.php?controller=auth&action=login", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                console.log("📢 الرد من السيرفر:", data); // ✅ عرض الرد من السيرفر
                showMessage(data.message || data.error, !data.error);
                if (!data.error) {
                    window.location.href = "/inventory_project/dashboard.php";
                }
            })
            .catch(error => console.error("❌ خطأ في تسجيل الدخول:", error));
    });

    registerForm.addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(registerForm);

        fetch("/inventory_project/public/index.php?controller=user&action=register", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message || data.error, !data.error);
                if (!data.error) {
                    registerForm.reset();
                    loadUsers();
                }
            })
            .catch(error => console.error("❌ خطأ في تسجيل المستخدم:", error));
    });
}

// ✅ التفاعل مع الجدول (تعديل - حذف)
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

// ✅ فتح المودال عند التعديل
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


document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("loginContainer").addEventListener("click", function () {
        toggleTab(true);
    });

    document.getElementById("registerContainer").addEventListener("click", function () {
        toggleTab(false);
    });

    function toggleTab(isLogin) {
        let activeForm = isLogin ? loginForm : registerForm;
        let inactiveForm = isLogin ? registerForm : loginForm;

        gsap.to(inactiveForm, { opacity: 0, y: 10, duration: 0.3, onComplete: () => {
                inactiveForm.classList.add("hidden");
                activeForm.classList.remove("hidden");
                gsap.fromTo(activeForm, { opacity: 0, y: -10 }, { opacity: 1, y: 0, duration: 0.5 });
            }});
    }
});

/*

document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();
    let formData = new FormData(this);

    console.log("📢 بيانات تسجيل الدخول:", Object.fromEntries(formData.entries())); // ✅ عرض البيانات قبل الإرسال

    fetch("/inventory_project/public/index.php?controller=auth&action=login", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            showMessage(data.message || data.error, !data.error);
            if (!data.error) {
                window.location.href = "/inventory_project/dashboard.php";
            }
        })
        .catch(error => console.error("❌ خطأ في تسجيل الدخول:", error));
});
*/


// ✅ تحسين الرسائل
function showMessage(message, isSuccess) {
    let responseMessage = document.getElementById("responseMessage");
    if (!responseMessage) {
        console.warn("⚠️ العنصر `#responseMessage` غير موجود في الصفحة!");
        return;
    }

    responseMessage.textContent = message;
    responseMessage.classList.remove("text-green-500", "text-red-500");
    responseMessage.classList.add(isSuccess ? "text-green-500" : "text-red-500");

    setTimeout(() => {
        responseMessage.textContent = "";
    }, 3000);
}

// ✅ تحسين التنقل بين التابات
