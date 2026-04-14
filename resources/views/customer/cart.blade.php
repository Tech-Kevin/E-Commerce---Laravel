@extends('layouts.store')

@section('title', __('store.cart'))

@section('content')
<section class="page-section">
    <div class="store-container">
        <div class="section-heading">
            <div>
                <h2><i class="fa-solid fa-cart-shopping" style="color:var(--accent);margin-right:8px;"></i>{{ __('store.shopping_cart') }}</h2>
                <p>{{ __('store.review_products') }}</p>
            </div>
            @if(count($cart) > 0)
                <span style="font-size:13px;font-weight:700;color:var(--accent);background:var(--accent-soft);padding:6px 14px;border-radius:var(--radius-full);">
                    {{ count($cart) }} {{ count($cart) === 1 ? 'item' : 'items' }}
                </span>
            @endif
        </div>

        @if(count($cart) > 0)
            <div class="cart-layout">
                <div class="cart-items-card">
                    <div class="cart-card-header">
                        <h3>{{ __('store.cart_items') }}</h3>
                        <span>{{ __('store.product_count', ['count' => count($cart)]) }}</span>
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
                                        <div class="product-placeholder" style="font-size:24px;">
                                            <i class="fa-solid fa-box-open"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="cart-item-info">
                                    <span class="cart-item-badge">{{ __('store.in_cart') }}</span>
                                    <h3>{{ $item['name'] }}</h3>
                                    <div class="cart-price-row">
                                        <span class="cart-sale-price">₹ {{ number_format($price, 2) }}</span>
                                        @if(isset($item['sale_price']) && $item['sale_price'] && isset($item['price']))
                                            <span class="old-price">₹ {{ number_format($item['price'], 2) }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="cart-item-qty-block">
                                    <label>{{ __('store.quantity') }}</label>
                                    <div class="cart-item-qty">
                                        <button type="button" class="qty-btn decrease-btn" data-id="{{ $item['id'] }}">-</button>
                                        <input type="text" value="{{ $item['quantity'] }}" readonly id="qty-input-{{ $item['id'] }}">
                                        <button type="button" class="qty-btn increase-btn" data-id="{{ $item['id'] }}">+</button>
                                    </div>
                                </div>

                                <div class="cart-item-total-block">
                                    <label>{{ __('store.total') }}</label>
                                    <div class="cart-item-total" id="item-total-{{ $item['id'] }}">
                                        ₹ {{ number_format($itemTotal, 2) }}
                                    </div>

                                    <button type="button" class="remove-cart-btn" data-id="{{ $item['id'] }}">
                                        <i class="fa-solid fa-trash" style="margin-right:4px;font-size:11px;"></i> {{ __('store.remove') }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="cart-summary-card">
                    <div class="summary-top">
                        <h3 style="display:flex;align-items:center;gap:8px;">
                            <i class="fa-solid fa-receipt" style="color:var(--accent);font-size:16px;"></i>
                            {{ __('store.order_summary') }}
                        </h3>
                        <p>{{ __('store.final_amount') }}</p>
                    </div>

                    <div class="summary-row">
                        <span>{{ __('store.subtotal') }}</span>
                        <span id="cart-subtotal" style="font-weight:700;">₹ {{ number_format($subtotal, 2) }}</span>
                    </div>

                    <div class="summary-row">
                        <span>{{ __('store.shipping') }}</span>
                        <span id="cart-shipping" style="font-weight:700;{{ $shipping == 0 ? 'color:var(--success);' : '' }}">
                            {{ $shipping == 0 ? 'Free' : '₹ ' . number_format($shipping, 2) }}
                        </span>
                    </div>

                    <div class="summary-row total">
                        <span>{{ __('store.grand_total') }}</span>
                        <span id="cart-grand-total">₹ {{ number_format($grand_total, 2) }}</span>
                    </div>

                    <a href="{{ route('customer.checkout') }}" class="primary-btn full-btn" style="margin-top:8px;">
                        <i class="fa-solid fa-lock"></i> {{ __('store.proceed_checkout') }}
                    </a>

                    <a href="{{ route('home') }}" style="display:block;text-align:center;margin-top:12px;font-size:13px;font-weight:600;color:var(--text-muted);">
                        <i class="fa-solid fa-arrow-left" style="margin-right:4px;"></i> {{ __('store.continue_shopping') }}
                    </a>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fa-solid fa-cart-shopping"></i>
                <h3>{{ __('store.cart_empty') }}</h3>
                <p>{{ __('store.nothing_added') }}</p>
                <a href="{{ route('home') }}" class="primary-btn"><i class="fa-solid fa-bag-shopping"></i> {{ __('store.continue_shopping') }}</a>
            </div>
        @endif
    </div>
</section>

@push('scripts')
    <script src="{{ asset('js/customer/cart.js') }}"></script>
@endpush
@endsection
