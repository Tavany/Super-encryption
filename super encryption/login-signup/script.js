function toggleSidebar() {
    var sidebar = document.getElementById("sidebar");
    var menuIcon = document.getElementById("menu-icon");

    if (sidebar.style.display === "block" || sidebar.style.display === "") {
        sidebar.style.display = "none";
        // Remove the click outside event listener
        document.removeEventListener("click", handleOutsideClick);
    } else {
        sidebar.style.display = "block";
        // Add the click outside event listener
        document.addEventListener("click", handleOutsideClick);
    }
}

function handleOutsideClick(event) {
    var sidebar = document.getElementById("sidebar");
    var menuIcon = document.getElementById("menu-icon");

    // Check if the click target is not within the sidebar or the menu icon
    if (!sidebar.contains(event.target) && event.target !== menuIcon) {
        sidebar.style.display = "none";
        // Remove the click outside event listener
        document.removeEventListener("click", handleOutsideClick);
    }
}
