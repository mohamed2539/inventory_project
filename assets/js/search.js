document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("searchInput").addEventListener("input", function () {
        let query = this.value.trim();

        if (query.length === 0) {
            document.getElementById("searchResults").classList.add("hidden");
            return;
        }

        fetch(`/inventory_project/public/index.php?controller=material&action=search&query=${query}`)
            .then(response => response.json())
            .then(data => {
                let resultsTable = document.getElementById("resultsTable");
                resultsTable.innerHTML = "";

                if (data.length === 0) {
                    resultsTable.innerHTML = `<tr><td colspan="5" class="text-center p-4">🚨 لا توجد نتائج!</td></tr>`;
                } else {
                    data.forEach(material => {
                        let row = document.createElement("tr");
                        row.innerHTML = `
                            <td class="border p-2">${material.code}</td>
                            <td class="border p-2">${material.name}</td>
                            <td class="border p-2">${material.unit}</td>
                            <td class="border p-2">${material.quantity}</td>
                            <td class="border p-2">${material.price} جنيه</td>
                        `;
                        resultsTable.appendChild(row);
                    });
                }

                document.getElementById("searchResults").classList.remove("hidden");
            })
            .catch(error => console.error("❌ خطأ في البحث:", error));
    });
});
