document.getElementById("fetchMaterial").addEventListener("click", function() {
    let code = document.getElementById("materialCode").value;

    fetch(`../../app/controllers/MaterialController.php?action=get&code=${code}`)
        .then(response => response.json())
        .then(data => {
            let materialInfo = document.getElementById("materialInfo");
            if (data) {
                materialInfo.innerHTML = `
                <p>الاسم: ${data.name}</p>
                <p>الكمية المتاحة: ${data.quantity}</p>
                <input type="number" id="addQty" placeholder="الكمية المراد إضافتها" class="border p-2 w-full mb-2">
                <button id="confirmAdd" class="bg-green-500 text-white p-2 w-full">إضافة</button>
            `;

                document.getElementById("confirmAdd").addEventListener("click", function() {
                    let addQty = document.getElementById("addQty").value;

                    fetch("../../app/controllers/MaterialController.php?action=add_quantity", {
                        method: "POST",
                        body: JSON.stringify({ code: code, quantity: addQty, user_id: 1 }),
                        headers: { "Content-Type": "application/json" }
                    })
                        .then(response => response.text())
                        .then(message => alert(message))
                        .catch(error => console.error("Error:", error));
                });
            } else {
                materialInfo.innerHTML = "<p class='text-red-500'>لم يتم العثور على المادة</p>";
            }
        });
});
