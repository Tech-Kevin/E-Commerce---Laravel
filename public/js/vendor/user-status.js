function setStatusVisuals(row, label, isActive) {
    if (row) {
        row.classList.toggle('user-row-inactive', !isActive);
    }

    if (!label) {
        return;
    }

    label.textContent = isActive ? 'Active' : 'Blocked';
    label.classList.toggle('active', isActive);
    label.classList.toggle('blocked', !isActive);
}

document.querySelectorAll('.js-user-status-toggle').forEach(function (toggle) {
    toggle.addEventListener('change', function () {
        const checkbox = this;
        const statusWrap = checkbox.closest('.user-status-wrap');
        const statusLabel = statusWrap ? statusWrap.querySelector('.js-user-status-label') : null;
        const row = checkbox.closest('tr');
        const previousState = !checkbox.checked;

        checkbox.disabled = true;

        fetch(checkbox.dataset.url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                is_active: checkbox.checked ? 1 : 0,
            }),
        })
            .then(async function (response) {
                const data = await response.json().catch(function () {
                    return {};
                });

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to update status.');
                }

                return data;
            })
            .then(function (data) {
                setStatusVisuals(row, statusLabel, !!data.is_active);
            })
            .catch(function (error) {
                checkbox.checked = previousState;
                setStatusVisuals(row, statusLabel, previousState);
                alert(error.message || 'Failed to update status.');
            })
            .finally(function () {
                checkbox.disabled = false;
            });
    });
});
