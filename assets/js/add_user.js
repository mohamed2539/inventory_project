document.getElementById("addUserForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch("../../app/controllers/UserController.php?action=add", {
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
