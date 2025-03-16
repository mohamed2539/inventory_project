document.getElementById("searchQuery").addEventListener("input", function() {
    let query = this.value.trim();

    if (query.length < 2) {
        document.getElementById("searchResults").innerHTML = "";
        return;
    }

    fetch(`../../app/controllers/MaterialController.php?action=search&query=${query}`)
        .then(response => response.json())
        .then(data => {
            let resultsDiv = document.getElementById("searchResults");
            if (data.length > 0) {
                resultsDiv.innerHTML = data.map(material => `
                <p>${material.name} - ${material.code} - ${material.quantity} قطعة</p>
            `).join("");
            } else {
                resultsDiv.innerHTML = "<p class='text-red-500'>لا توجد نتائج</p>";
            }
        })
        .catch(error => console.error("Error:", error));
});
