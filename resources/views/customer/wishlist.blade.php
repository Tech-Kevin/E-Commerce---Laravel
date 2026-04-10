@extends('layouts.store')

@section('title', __('store.my_wishlist'))

@section('content')
    <section class="page-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2>{{ __('store.my_wishlist') }}</h2>
                    <p>{{ __('store.save_for_later') }}</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
                    {{ session('success') }}
                </div>
            @endif

            @if($wishlistItems->isEmpty())
                <div class="table-card empty-cart-card">
                <div class="empty-cart-content">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <h3>{{ __('store.wishlist_empty') }}</h3>
                    <p>{{ __('store.nothing_added') }}</p>
                    <a href="{{ route('home') }}" class="primary-btn">{{ __('store.continue_shopping') }}</a>
                </div>
            </div>
            @else
            <div class="products-grid">
                @foreach($wishlistItems as $item)
                @php $product = $item->product; @endphp
                @if($product)
                <div class="product-card">
                    <div class="product-image-wrap">
                        @if($product->getFirstMediaUrl('product_image'))
                            <img src="{{ $product->getFirstMediaUrl('product_image') }}" alt="{{ $product->name }}" class="product-img">
                        @else
                            <div class="product-placeholder">
                                <i class="fa-solid fa-box-open"></i>
                            </div>
                        @endif
                    </div>
                    <div class="product-card-body">
                        <h3>{{ $product->name }}</h3>
                        <p>{{ $product->description }}</p>
                        <div class="product-meta">
                            @if($product->sale_price)
                                <span class="sale-price">₹ {{ number_format($product->sale_price, 2) }}</span>
                                <span class="old-price">₹{{ number_format($product->price, 2) }}</span>
                            @else
                                <span class="regular-price">₹ {{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                        <div class="product-actions">
                            <button class="product-btn add-to-cart-btn" data-id="{{ $product->id }}" data-cart-url="{{ route('cart.index') }}">{{ __('store.move_to_cart') }}</button>
                            <form action="{{ route('wishlist.remove', $product->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="wishlist-btn">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
            @endif
        </div>
    </section>

@push('scripts')
    <script src="{{ asset('js/customer/wishlist.js') }}"></script>
@endpush
@endsection
