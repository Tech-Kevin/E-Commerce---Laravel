/**
 * Super Admin — shared UI helpers
 * Modal open/close and misc utilities used across the super-admin pages.
 */
(function () {
    function openModal(selector) {
        const modal = document.querySelector(selector);
        if (!modal) return;
        modal.classList.add('open');
    }

    function closeModal(el) {
        const modal = el.closest('.sa-modal');
        if (modal) modal.classList.remove('open');
    }

    document.addEventListener('click', function (e) {
        const opener = e.target.closest('[data-sa-modal]');
        if (opener) {
            e.preventDefault();
            openModal(opener.dataset.saModal);
            return;
        }

        if (e.target.matches('[data-sa-close]') || e.target.closest('[data-sa-close]')) {
            closeModal(e.target);
            return;
        }

        // Click on backdrop closes modal
        if (e.target.classList.contains('sa-modal')) {
            e.target.classList.remove('open');
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.sa-modal.open').forEach(m => m.classList.remove('open'));
        }
    });

    window.SuperAdmin = { openModal, closeModal };
})();
