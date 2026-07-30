// Auto dismiss alerts after 5 seconds
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {
        const alerts = document.querySelectorAll(".alert");
        alerts.forEach(function (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Confirm Delete
function confirmDelete(event) {
    if (!confirm("Apakah Anda yakin ingin menghapus data ini?")) {
        event.preventDefault();
        return false;
    }
    return true;
}

// Toggle Theme
function toggleTheme() {
    const body = document.body;
    const icon = document.getElementById("themeIcon");

    if (body.classList.contains("dark-mode")) {
        body.classList.remove("dark-mode");
        icon.classList.remove("fa-sun");
        icon.classList.add("fa-moon");
        localStorage.setItem("theme", "light");
    } else {
        body.classList.add("dark-mode");
        icon.classList.remove("fa-moon");
        icon.classList.add("fa-sun");
        localStorage.setItem("theme", "dark");
    }
}

// Load theme from localStorage
document.addEventListener("DOMContentLoaded", function () {
    const theme = localStorage.getItem("theme") || "light";
    const body = document.body;
    const icon = document.getElementById("themeIcon");

    if (theme === "dark") {
        body.classList.add("dark-mode");
        if (icon) {
            icon.classList.remove("fa-moon");
            icon.classList.add("fa-sun");
        }
    }
});
