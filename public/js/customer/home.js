document.querySelectorAll('.wishlist-toggle-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const id   = this.dataset.id;
        const icon = this.querySelector('i');

        fetch('/customer/wishlist/toggle/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            showToast(data.message, data.status);
            icon.className  = data.in_wishlist ? 'fa-solid fa-heart' : 'fa-regular fa-heart';
            icon.style.color = data.in_wishlist ? '#e05a2b' : '';
        })
        .catch(() => showToast('Something went wrong.', false));
    });
});
