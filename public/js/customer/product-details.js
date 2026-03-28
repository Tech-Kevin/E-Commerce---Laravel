document.querySelectorAll('.add-to-cart-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        const productId = this.dataset.id;

        fetch('/customer/cart/add/' + productId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => showToast(data.message, data.status))
        .catch(() => showToast('Something went wrong.', false));
    });
});

document.querySelectorAll('.wishlist-toggle-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        const id = this.dataset.id;
        const btn = this;

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
            if (data.in_wishlist) {
                btn.innerHTML   = '<i class="fa-solid fa-heart" style="margin-right:6px;"></i>In Wishlist';
                btn.style.color = '#e05a2b';
            } else {
                btn.innerHTML   = '<i class="fa-regular fa-heart" style="margin-right:6px;"></i>Add to Wishlist';
                btn.style.color = '';
            }
        })
        .catch(() => showToast('Something went wrong.', false));
    });
});
