document.addEventListener('DOMContentLoaded', function() {
    // Handle dropdown buttons
    document.querySelectorAll('.btn-dropdown').forEach(button => {
        const icon = button.querySelector('.fa-angle-down');

        // Set initial state
        if (button.getAttribute('aria-expanded') === 'true') {
            icon.style.transform = 'rotate(180deg)';
        }

        // Set up collapse event listener
        const collapseId = button.getAttribute('data-bs-target');
        const collapseElement = document.querySelector(collapseId);

        if (collapseElement) {
            collapseElement.addEventListener('show.bs.collapse', () => {
                icon.style.transition = 'transform 0.2s ease';
                icon.style.transform = 'rotate(180deg)';
            });

            collapseElement.addEventListener('hide.bs.collapse', () => {
                icon.style.transition = 'transform 0.2s ease';
                icon.style.transform = 'rotate(0deg)';
            });
        }
    });
});
