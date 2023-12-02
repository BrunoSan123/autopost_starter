<script>
    // Check if the "darkMode" cookie is set to "dark"
    if (document.cookie.indexOf('darkMode=dark') !== -1) {
        // Get the element by its id
        var wpcontentElement = document.getElementById("wpcontent");

        // Add the "dark" class to the element
        if (wpcontentElement) {
            wpcontentElement.classList.add("dark");
        }
    }
</script>
