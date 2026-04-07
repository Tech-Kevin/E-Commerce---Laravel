@extends('layouts.store')

@section('title', 'Checkout')

@section('content')
    <section class="page-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2>Checkout</h2>
                    <p>Complete your order securely</p>
                </div>
            </div>

            @php
                $cart = session()->get('cart', []);
                $subtotal = 0;
                $shipping = 0;
                foreach ($cart as $item) {
                    $price = $item['sale_price'] ?? $item['price'];
                    $subtotal += $price * $item['quantity'];
                    $shipping += $item['shipping_charge'] ?? 0;
                }
                $grandTotal = $subtotal + $shipping;
            @endphp

            @if(empty($cart))
                <div class="empty-state">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <h3>Your cart is empty</h3>
                    <a href="{{ route('home') }}" class="primary-btn">Continue Shopping</a>
                </div>
            @else
            <form action="{{ route('order.place') }}" method="POST">
                @csrf
                <div class="checkout-layout">
                    <div class="checkout-form-card">
                        <h3>Billing Details</h3>

                        @if($errors->any())
                            <div class="alert alert-error">
                                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <div class="checkout-grid">
                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="full_name" class="form-control"
                                    value="{{ old('full_name', Auth::check() ? Auth::user()->name : '') }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', Auth::check() ? Auth::user()->number : '') }}">
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control"
                                    value="{{ old('address', Auth::check() ? Auth::user()->address : '') }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Pincode</label>
                                <input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}">
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-control">
                                    <option value="cod">Cash on Delivery</option>
                                    <option value="razorpay">Razorpay (Online Payment)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="cart-summary-card">
                        <h3>Order Summary</h3>

                        @foreach($cart as $item)
                            <div class="summary-row">
                                <span>{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                                <span>₹ {{ number_format(($item['sale_price'] ?? $item['price']) * $item['quantity'], 2) }}</span>
                            </div>
                        @endforeach

                        <hr style="border-color:#f2e7dc; margin: 12px 0;">

                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>₹ {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span>₹ {{ number_format($shipping, 2) }}</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span>₹ {{ number_format($grandTotal, 2) }}</span>
                        </div>
                        <button type="submit" class="primary-btn full-btn">Place Order</button>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </section>
@endsection
