document.querySelectorAll('.order-status-select').forEach(function (select) {
    select.addEventListener('change', function () {
        const url    = this.dataset.url;
        const status = this.value;
        const el     = this;

        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ status }),
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                el.className = 'order-status-select status-' + data.status;
            }
        })
        .catch(() => alert('Failed to update status.'));
    });
});
