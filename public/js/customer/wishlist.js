document.querySelectorAll('.add-to-cart-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const id      = this.dataset.id;
        const cartUrl = this.dataset.cartUrl;

        fetch('/customer/cart/add/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status) {
                window.location.href = cartUrl;
            }
        });
    });
});
