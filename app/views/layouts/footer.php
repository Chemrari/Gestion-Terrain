<!-- Bootstrap 5 JS Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom App JS -->
<script src="<?= base_url('assets/js/app.js') ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Auto-dismiss toast after 4 seconds with fade-out
    const toast = document.getElementById('toast-notification');
    if (toast) {
        setTimeout(function () {
            toast.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            toast.style.opacity   = '0';
            toast.style.transform = 'translateX(120%)';
            setTimeout(() => toast.remove(), 500);
        }, 4000);
    }
});
</script>

</body>
</html>
