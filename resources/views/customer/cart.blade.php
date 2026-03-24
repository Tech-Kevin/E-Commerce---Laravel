@extends('layouts.store')

@section('title', 'Cart')

@section('content')
<section class="page-section">
    <div class="store-container">
        <div class="section-heading">
            <div>
                <h2>Shopping Cart</h2>
                <p>Review products before checkout</p>
            </div>
        </div>

        @if(count($cart) > 0)
            <div class="cart-layout">
                <div class="cart-items-card">
                    <div class="cart-card-header">
                        <h3>Cart Items</h3>
                        <span>{{ count($cart) }} Product(s)</span>
                    </div>

                    <div id="cart-items-wrapper">
                        @foreach($cart as $item)
                            @php
                                $price = $item['sale_price'] ?? $item['price'];
                                $itemTotal = $price * $item['quantity'];
                            @endphp

                            <div class="cart-item" id="cart-item-{{ $item['id'] }}">
                                <div class="cart-item-image">
                                    @if($item['image'])
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                                    @else
                                        <div class="product-placeholder">
                                            <i class="fa-solid fa-box-open"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="cart-item-info">
                                    <span class="cart-item-badge">In Cart</span>
                                    <h3>{{ $item['name'] }}</h3>
                                    <p>{{ $item['description'] }}</p>
                                    <div class="cart-price-row">
                                        <span class="cart-sale-price">₹ {{ number_format($price, 2) }}</span>
                                    </div>
                                </div>

                                <div class="cart-item-qty-block">
                                    <label>Quantity</label>
                                    <div class="cart-item-qty">
                                        <button type="button" class="qty-btn decrease-btn" data-id="{{ $item['id'] }}">-</button>
                                        <input type="text" value="{{ $item['quantity'] }}" readonly id="qty-input-{{ $item['id'] }}">
                                        <button type="button" class="qty-btn increase-btn" data-id="{{ $item['id'] }}">+</button>
                                    </div>
                                </div>

                                <div class="cart-item-total-block">
                                    <label>Total</label>
                                    <div class="cart-item-total" id="item-total-{{ $item['id'] }}">
                                        ₹ {{ number_format($itemTotal, 2) }}
                                    </div>

                                    <button type="button" class="remove-cart-btn" data-id="{{ $item['id'] }}">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="cart-summary-card">
                    <div class="summary-top">
                        <h3>Order Summary</h3>
                        <p>Final amount for your purchase</p>
                    </div>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="cart-subtotal">₹ {{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Shipping</span>
                        <span id="cart-shipping">₹ {{ number_format($shipping, 2) }}</span>
                    </div>

                    <div class="summary-row total">
                        <span>Grand Total</span>
                        <span id="cart-grand-total">₹ {{ number_format($grand_total, 2) }}</span>
                    </div>

                    <a href="{{ route('customer.checkout') }}" class="primary-btn full-btn">Proceed to Checkout</a>
                </div>
            </div>
        @else
            <div class="table-card empty-cart-card">
                <div class="empty-cart-content">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <h3>Your cart is empty</h3>
                    <p>Looks like you have not added anything yet.</p>
                    <a href="{{ route('home') }}" class="primary-btn">Continue Shopping</a>
                </div>
            </div>
        @endif
    </div>
</section>

<script>
    const csrfToken = '{{ csrf_token() }}';

    function updateCartQuantity(productId, quantity) {
        fetch(`/customer/cart/update/${productId}`, {
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
                document.getElementById(`qty-input-${productId}`).value = data.quantity;
                document.getElementById(`item-total-${productId}`).innerText = `₹ ${data.item_total}`;
                document.getElementById('cart-subtotal').innerText = `₹ ${data.subtotal}`;
                document.getElementById('cart-shipping').innerText = `₹ ${data.shipping}`;
                document.getElementById('cart-grand-total').innerText = `₹ ${data.grand_total}`;
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function removeCartItem(productId) {
        fetch(`/customer/cart/remove/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                const row = document.getElementById(`cart-item-${productId}`);
                if (row) row.remove();

                document.getElementById('cart-subtotal').innerText = `₹ ${data.subtotal}`;
                document.getElementById('cart-shipping').innerText = `₹ ${data.shipping}`;
                document.getElementById('cart-grand-total').innerText = `₹ ${data.grand_total}`;

                if (data.cart_count === 0) {
                    location.reload();
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    document.querySelectorAll('.increase-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const input = document.getElementById(`qty-input-${id}`);
            let quantity = parseInt(input.value);
            quantity++;
            updateCartQuantity(id, quantity);
        });
    });

    document.querySelectorAll('.decrease-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const input = document.getElementById(`qty-input-${id}`);
            let quantity = parseInt(input.value);

            if (quantity > 1) {
                quantity--;
                updateCartQuantity(id, quantity);
            }
        });
    });

    document.querySelectorAll('.remove-cart-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            removeCartItem(id);
        });
    });
</script>
@endsection