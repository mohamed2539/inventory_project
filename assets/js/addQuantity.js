document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("confirmAdd").addEventListener("click", function () {
        let materialCode = document.getElementById("materialCode").value;
        let addQuantity = parseInt(document.getElementById("addQuantity").value, 10);

        if (!materialCode || isNaN(addQuantity) || addQuantity <= 0) {
            showMessage("يرجى إدخال كمية صالحة!", false);
            return;
        }

        fetch("/inventory_project/public/index.php?controller=material&action=updateStock", {
            method: "POST",
            body: JSON.stringify({ code: materialCode, quantity: addQuantity }),
            headers: { "Content-Type": "application/json" }
        })
            .then(response => response.json())
            .then(data => {
                showMessage(data.message || data.error, !data.error);
                if (!data.error) {
                    document.getElementById("currentQuantity").textContent = data.new_quantity;
                    document.getElementById("addQuantity").value = "";
                }
            })
            .catch(error => console.error("❌ خطأ في تحديث الكمية:", error));
    });
});

function fetchMaterial() {
    let materialCode = document.getElementById("materialCode").value;
    if (!materialCode) return;

    fetch(`/inventory_project/public/index.php?controller=material&action=getByCode&code=${materialCode}`)
        .then(response => response.json())
        .then(material => {
            if (!material || material.error) {
                showMessage(material.error || "لم يتم العثور على المادة!", false);
                return;
            }

            document.getElementById("materialName").textContent = material.name;
            document.getElementById("materialUnit").textContent = material.unit;
            document.getElementById("currentQuantity").textContent = material.quantity;
            document.getElementById("materialDetails").classList.remove("hidden");
        })
        .catch(error => console.error("❌ خطأ في تحميل بيانات المادة:", error));
}

function showMessage(message, isSuccess) {
    let responseMessage = document.getElementById("responseMessage");
    responseMessage.textContent = message;
    responseMessage.classList.remove("text-green-500", "text-red-500");
    responseMessage.classList.add(isSuccess ? "text-green-500" : "text-red-500");

    setTimeout(() => {
        responseMessage.textContent = "";
    }, 3000);
}
