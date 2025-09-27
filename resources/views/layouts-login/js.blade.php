<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".choices-select").forEach(function(el) {
            new Choices(el, {
                searchEnabled: true,
                placeholder: true,
                removeItemButton: false
            });
        });
    });
</script>
