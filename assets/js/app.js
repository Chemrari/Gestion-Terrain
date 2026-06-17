/**
 * GeoTerrain – Main Application Script
 * Handles: responsive sidebar, form enhancements, UX interactions
 */

document.addEventListener('DOMContentLoaded', function () {

    /* ------------------------------------------------------------------
       1. RESPONSIVE SIDEBAR TOGGLE (mobile ≤ 991px)
    ------------------------------------------------------------------ */
    const sidebar     = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');

    if (sidebar && mainContent) {
        // Create hamburger button
        const toggleBtn = document.createElement('button');
        toggleBtn.id        = 'sidebarToggle';
        toggleBtn.className = 'btn btn-primary d-lg-none';
        toggleBtn.style.cssText = 'position:fixed;top:14px;left:14px;z-index:1100;border-radius:8px;padding:6px 12px;';
        toggleBtn.innerHTML = '<i class="bi bi-list fs-5"></i>';
        document.body.appendChild(toggleBtn);

        // Overlay for mobile
        const overlay = document.createElement('div');
        overlay.id = 'sidebarOverlay';
        overlay.style.cssText = 'display:none;position:fixed;inset:0;background:rgba(15,23,42,.4);z-index:99;backdrop-filter:blur(2px);';
        document.body.appendChild(overlay);

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.style.display = sidebar.classList.contains('active') ? 'block' : 'none';
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.style.display = 'none';
        });
    }

    /* ------------------------------------------------------------------
       2. SMOOTH ACTIVE STATE ON SIDEBAR LINKS
    ------------------------------------------------------------------ */
    const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
    sidebarLinks.forEach(link => {
        if (link.href === window.location.href) {
            link.closest('li')?.classList.add('active');
        }
    });

    /* ------------------------------------------------------------------
       3. AUTO-SET TODAY'S DATE ON PAYMENT DATE INPUTS (if empty)
    ------------------------------------------------------------------ */
    const paymentDateInputs = document.querySelectorAll('input[name="date_paiement"]');
    paymentDateInputs.forEach(input => {
        if (!input.value) {
            input.value = new Date().toISOString().split('T')[0];
        }
    });

    /* ------------------------------------------------------------------
       4. TABLE ROW HOVER HIGHLIGHT
    ------------------------------------------------------------------ */
    const tableRows = document.querySelectorAll('.custom-table tbody tr');
    tableRows.forEach(row => {
        row.style.transition = 'background-color 0.15s ease';
        row.addEventListener('mouseenter', () => row.style.backgroundColor = '#F8FAFC');
        row.addEventListener('mouseleave', () => row.style.backgroundColor = '');
    });

    /* ------------------------------------------------------------------
       5. IMAGE PREVIEW ON TERRAIN FORM FILE INPUT
    ------------------------------------------------------------------ */
    const imageInputs = document.querySelectorAll('input[type="file"][name="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            // Validate type client-side
            const allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!allowed.includes(file.type)) {
                alert('Format non supporté. Veuillez choisir JPG, PNG ou WEBP.');
                this.value = '';
                return;
            }

            // Validate size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('L\'image ne doit pas dépasser 5 Mo.');
                this.value = '';
                return;
            }
        });
    });

    /* ------------------------------------------------------------------
       6. CONFIRM DIALOGS ON DANGER LINKS (delete actions via data attribute)
    ------------------------------------------------------------------ */
    document.querySelectorAll('a[data-confirm]').forEach(el => {
        el.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

});
