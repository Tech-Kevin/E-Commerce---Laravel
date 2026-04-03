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

// Delivery boy assignment
document.querySelectorAll('.order-delivery-select').forEach(function (select) {
    select.addEventListener('change', function () {
        const url = this.dataset.url;
        const delivery_boy_id = this.value;
        const el = this;

        if (!delivery_boy_id) return;

        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ delivery_boy_id }),
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                el.style.borderColor = '#2d9b57';
                setTimeout(() => el.style.borderColor = '', 2000);
            } else {
                alert('Failed to assign delivery boy.');
            }
        })
        .catch(() => alert('Failed to assign delivery boy.'));
    });
});
