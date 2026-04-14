@extends('layouts.store')

@section('title', __('store.checkout'))

@section('content')
    <section class="page-section">
        <div class="store-container">

            {{-- Checkout Steps --}}
            <div class="checkout-steps">
                <div class="checkout-step done">
                    <span class="checkout-step-num"><i class="fa-solid fa-check"></i></span>
                    <span>{{ __('store.cart') }}</span>
                </div>
                <div class="checkout-step-line done"></div>
                <div class="checkout-step active">
                    <span class="checkout-step-num">2</span>
                    <span>{{ __('store.checkout') }}</span>
                </div>
                <div class="checkout-step-line"></div>
                <div class="checkout-step">
                    <span class="checkout-step-num">3</span>
                    <span>{{ __('store.confirmation') }}</span>
                </div>
            </div>

            <div class="section-heading">
                <div>
                    <h2>{{ __('store.checkout') }}</h2>
                    <p>{{ __('store.complete_order') }}</p>
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
                    <h3>{{ __('store.cart_empty') }}</h3>
                    <p>Add some products to your cart before checkout.</p>
                    <a href="{{ route('home') }}" class="primary-btn"><i class="fa-solid fa-bag-shopping"></i> {{ __('store.continue_shopping') }}</a>
                </div>
            @else
            <form action="{{ route('order.place') }}" method="POST">
                @csrf
                <div class="checkout-layout">
                    <div class="checkout-form-card">
                        <h3 style="display:flex;align-items:center;gap:8px;">
                            <i class="fa-solid fa-location-dot" style="color:var(--accent);font-size:16px;"></i>
                            {{ __('store.billing_details') }}
                        </h3>

                        @if($errors->any())
                            <div class="alert alert-error">
                                <ul style="margin:0;padding-left:16px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <div class="checkout-grid">
                            <div class="form-group">
                                <label class="form-label">{{ __('store.full_name') }}</label>
                                <input type="text" name="full_name" class="form-control"
                                    value="{{ old('full_name', Auth::check() ? Auth::user()->name : '') }}" placeholder="John Doe">
                            </div>

                            <div class="form-group">
                                <label class="form-label">{{ __('store.phone') }}</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', Auth::check() ? Auth::user()->number : '') }}" placeholder="+91 9876543210">
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">{{ __('store.address') }}</label>
                                <input type="text" name="address" class="form-control"
                                    value="{{ old('address', Auth::check() ? Auth::user()->address : '') }}" placeholder="123 Street Name, Area">
                            </div>

                            <div class="form-group">
                                <label class="form-label">{{ __('store.city') }}</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city') }}" placeholder="Mumbai">
                            </div>

                            <div class="form-group">
                                <label class="form-label">{{ __('store.pincode') }}</label>
                                <input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}" placeholder="400001">
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">{{ __('store.payment_method') }}</label>
                                <select name="payment_method" class="form-control">
                                    <option value="cod">{{ __('store.cod') }}</option>
                                    <option value="razorpay">{{ __('store.razorpay') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="cart-summary-card">
                        <div class="summary-top">
                            <h3 style="display:flex;align-items:center;gap:8px;">
                                <i class="fa-solid fa-receipt" style="color:var(--accent);font-size:16px;"></i>
                                {{ __('store.order_summary') }}
                            </h3>
                            <p>{{ count($cart) }} item(s) in your order</p>
                        </div>

                        @foreach($cart as $item)
                            <div class="summary-row" style="font-size:13px;">
                                <span style="display:flex;align-items:center;gap:8px;">
                                    <span style="width:24px;height:24px;border-radius:6px;background:var(--accent-soft);color:var(--accent);display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:800;">{{ $item['quantity'] }}</span>
                                    {{ Str::limit($item['name'], 25) }}
                                </span>
                                <span style="font-weight:700;">₹ {{ number_format(($item['sale_price'] ?? $item['price']) * $item['quantity'], 2) }}</span>
                            </div>
                        @endforeach

                        <div style="border-top:1.5px solid var(--border-light);margin-top:8px;"></div>

                        <div class="summary-row">
                            <span>{{ __('store.subtotal') }}</span>
                            <span style="font-weight:700;">₹ {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>{{ __('store.shipping') }}</span>
                            <span style="font-weight:700;{{ $shipping == 0 ? 'color:var(--success);' : '' }}">
                                {{ $shipping == 0 ? 'Free' : '₹ ' . number_format($shipping, 2) }}
                            </span>
                        </div>
                        <div class="summary-row total">
                            <span>{{ __('store.total') }}</span>
                            <span>₹ {{ number_format($grandTotal, 2) }}</span>
                        </div>
                        <button type="submit" class="primary-btn full-btn" style="margin-top:8px;">
                            <i class="fa-solid fa-lock"></i> {{ __('store.place_order') }}
                        </button>
                        <p style="text-align:center;font-size:12px;color:var(--text-faint);margin-top:12px;">
                            <i class="fa-solid fa-shield-halved" style="margin-right:4px;"></i>
                            Your payment is secure and encrypted
                        </p>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </section>
@endsection
