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
                <input type="number" id="dispenseQty" placeholder="الكمية المراد صرفها" class="border p-2 w-full mb-2">
                <button id="confirmDispense" class="bg-red-500 text-white p-2 w-full">صرف</button>
            `;

                document.getElementById("confirmDispense").addEventListener("click", function() {
                    let dispenseQty = document.getElementById("dispenseQty").value;

                    fetch("../../app/controllers/MaterialController.php?action=dispense", {
                        method: "POST",
                        body: JSON.stringify({ code: code, quantity: dispenseQty, user_id: 1 }),
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
