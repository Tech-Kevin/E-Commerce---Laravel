const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function updateCartQuantity(productId, quantity) {
    fetch('/customer/cart/update/' + productId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            document.getElementById('qty-input-' + productId).value       = data.quantity;
            document.getElementById('item-total-' + productId).innerText  = '₹ ' + data.item_total;
            document.getElementById('cart-subtotal').innerText             = '₹ ' + data.subtotal;
            document.getElementById('cart-shipping').innerText             = '₹ ' + data.shipping;
            document.getElementById('cart-grand-total').innerText         = '₹ ' + data.grand_total;
        }
    })
    .catch(error => console.error('Error:', error));
}

function removeCartItem(productId) {
    fetch('/customer/cart/remove/' + productId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status) {
            const row = document.getElementById('cart-item-' + productId);
            if (row) row.remove();

            document.getElementById('cart-subtotal').innerText    = '₹ ' + data.subtotal;
            document.getElementById('cart-shipping').innerText    = '₹ ' + data.shipping;
            document.getElementById('cart-grand-total').innerText = '₹ ' + data.grand_total;

            if (data.cart_count === 0) {
                location.reload();
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

document.querySelectorAll('.increase-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        const id    = this.dataset.id;
        const input = document.getElementById('qty-input-' + id);
        updateCartQuantity(id, parseInt(input.value) + 1);
    });
});

document.querySelectorAll('.decrease-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        const id       = this.dataset.id;
        const input    = document.getElementById('qty-input-' + id);
        const quantity = parseInt(input.value);
        if (quantity > 1) {
            updateCartQuantity(id, quantity - 1);
        }
    });
});

document.querySelectorAll('.remove-cart-btn').forEach(function (button) {
    button.addEventListener('click', function () {
        removeCartItem(this.dataset.id);
    });
});
