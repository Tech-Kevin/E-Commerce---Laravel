document.querySelectorAll('.add-to-cart-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var id      = this.dataset.id;
        var cartUrl = this.dataset.cartUrl;

        fetch('/customer/cart/add/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.status) {
                updateBadge('cart-count', data.cart_count);
                window.location.href = cartUrl;
            }
        });
    });
});
