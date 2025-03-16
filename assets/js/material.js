document.addEventListener("DOMContentLoaded", function() {
    fetch("materials/list.php")
        .then(response => response.json())
        .then(data => {
            let list = document.getElementById("materials-list");
            list.innerHTML = data.map(material => `<li>${material.name} - ${material.quantity}</li>`).join("");
        })
        .catch(error => console.error("Error:", error));
});
document.addEventListener("DOMContentLoaded", function() {
    // تحميل الفروع والموردين عند تحميل الصفحة
    fetch("../../app/controllers/BranchController.php?action=list")
        .then(response => response.json())
        .then(data => {
            let branchSelect = document.querySelector("[name='branch_id']");
            data.forEach(branch => {
                let option = document.createElement("option");
                option.value = branch.id;
                option.textContent = branch.name;
                branchSelect.appendChild(option);
            });
        });

    fetch("../../app/controllers/SupplierController.php?action=list")
        .then(response => response.json())
        .then(data => {
            let supplierSelect = document.querySelector("[name='supplier_id']");
            data.forEach(supplier => {
                let option = document.createElement("option");
                option.value = supplier.id;
                option.textContent = supplier.name;
                supplierSelect.appendChild(option);
            });
        });

    // إرسال البيانات إلى السيرفر
    document.getElementById("addMaterialForm").addEventListener("submit", function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("../../app/controllers/MaterialController.php?action=add", {
            method: "POST",
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                document.getElementById("responseMessage").textContent = data;
                this.reset();
            })
            .catch(error => console.error("Error:", error));
    });
});
