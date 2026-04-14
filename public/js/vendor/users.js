function getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.content : '';
}

function updateStatusChip(row, label, isActive) {
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

function patchUser(url, payload) {
    return fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
            'Accept': 'application/json',
        },
        body: JSON.stringify(payload),
    }).then(async function (response) {
        const data = await response.json().catch(function () {
            return {};
        });

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Unable to update user.');
        }

        return data;
    });
}

document.querySelectorAll('.js-admin-role-select').forEach(function (select) {
    select.addEventListener('change', function () {
        const el = this;
        const previous = el.dataset.previous || el.value;
        const nextRole = el.value;

        el.disabled = true;
        patchUser(el.dataset.url, { role: nextRole })
            .then(function () {
                el.dataset.previous = nextRole;
            })
            .catch(function (error) {
                el.value = previous;
                alert(error.message || 'Unable to change role.');
            })
            .finally(function () {
                el.disabled = false;
            });
    });
});

document.querySelectorAll('.js-admin-status-toggle').forEach(function (toggle) {
    toggle.addEventListener('change', function () {
        const checkbox = this;
        const row = checkbox.closest('tr');
        const label = row ? row.querySelector('.js-user-status-label') : null;
        const previousState = !checkbox.checked;

        checkbox.disabled = true;
        patchUser(checkbox.dataset.url, { is_active: checkbox.checked ? 1 : 0 })
            .then(function (data) {
                updateStatusChip(row, label, !!data.user.is_active);
            })
            .catch(function (error) {
                checkbox.checked = previousState;
                updateStatusChip(row, label, previousState);
                alert(error.message || 'Unable to update status.');
            })
            .finally(function () {
                checkbox.disabled = false;
            });
    });
});

document.querySelectorAll('.js-admin-delete-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        const el = this;
        const name = el.dataset.name || 'this user';
        if (!confirm('Delete ' + name + '? This action cannot be undone.')) {
            return;
        }

        el.disabled = true;
        fetch(el.dataset.url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
            },
        })
            .then(async function (response) {
                const data = await response.json().catch(function () {
                    return {};
                });

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Unable to delete user.');
                }

                return data;
            })
            .then(function () {
                const row = el.closest('tr');
                if (row) {
                    row.remove();
                }
            })
            .catch(function (error) {
                alert(error.message || 'Unable to delete user.');
                el.disabled = false;
            });
    });
});

const searchInput = document.getElementById('userSearchInput');
if (searchInput) {
    searchInput.addEventListener('input', function () {
        const keyword = (this.value || '').trim().toLowerCase();
        const rows = document.querySelectorAll('#usersTableBody tr[data-search]');

        rows.forEach(function (row) {
            const hay = row.dataset.search || '';
            row.style.display = hay.includes(keyword) ? '' : 'none';
        });
    });
}
