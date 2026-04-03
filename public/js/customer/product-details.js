document.querySelectorAll('.add-to-cart-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        var productId = this.dataset.id;

        fetch('/customer/cart/add/' + productId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            showToast(data.message, data.status);
            updateBadge('cart-count', data.cart_count);
        })
        .catch(function () { showToast('Something went wrong.', false); });
    });
});

document.querySelectorAll('.wishlist-toggle-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        var id = this.dataset.id;
        var btn = this;

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
            if (data.in_wishlist) {
                btn.innerHTML   = '<i class="fa-solid fa-heart" style="margin-right:6px;"></i>In Wishlist';
                btn.style.color = '#e05a2b';
            } else {
                btn.innerHTML   = '<i class="fa-regular fa-heart" style="margin-right:6px;"></i>Add to Wishlist';
                btn.style.color = '';
            }
            updateBadge('wishlist-count', data.wishlist_count);
        })
        .catch(function () { showToast('Something went wrong.', false); });
    });
});
