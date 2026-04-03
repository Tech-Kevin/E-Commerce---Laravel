document.querySelectorAll('.wishlist-toggle-btn').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        e.preventDefault();
        var id   = this.dataset.id;
        var icon = this.querySelector('i');

        fetch('/customer/wishlist/toggle/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            showToast(data.message, data.status);
            icon.className  = data.in_wishlist ? 'fa-solid fa-heart' : 'fa-regular fa-heart';
            icon.style.color = data.in_wishlist ? '#e05a2b' : '';
            updateBadge('wishlist-count', data.wishlist_count);
        })
        .catch(function () { showToast('Something went wrong.', false); });
    });
});
