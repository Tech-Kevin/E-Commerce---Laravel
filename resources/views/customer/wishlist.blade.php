@extends('layouts.store')

@section('title', __('store.my_wishlist'))

@section('content')
    <section class="page-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2><i class="fa-solid fa-heart" style="color:#ef4444;margin-right:8px;"></i>{{ __('store.my_wishlist') }}</h2>
                    <p>{{ __('store.save_for_later') }}</p>
                </div>
                @if($wishlistItems->isNotEmpty())
                    <span style="font-size:13px;font-weight:700;color:var(--accent);background:var(--accent-soft);padding:6px 14px;border-radius:var(--radius-full);">
                        {{ $wishlistItems->count() }} {{ $wishlistItems->count() === 1 ? 'item' : 'items' }}
                    </span>
                @endif
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:18px;">
                    <i class="fa-solid fa-circle-check" style="margin-right:6px;"></i> {{ session('success') }}
                </div>
            @endif

            @if($wishlistItems->isEmpty())
                <div class="empty-state">
                    <i class="fa-regular fa-heart"></i>
                    <h3>{{ __('store.wishlist_empty') }}</h3>
                    <p>{{ __('store.nothing_added') }}</p>
                    <a href="{{ route('home') }}" class="primary-btn"><i class="fa-solid fa-bag-shopping"></i> {{ __('store.continue_shopping') }}</a>
                </div>
            @else
            <div class="products-grid">
                @foreach($wishlistItems as $item)
                @php $product = $item->product; @endphp
                @if($product)
                <div class="product-card" style="position:relative;">
                    <div class="product-image-wrap">
                        @if($product->sale_price)
                            @php $d = round((($product->price - $product->sale_price) / $product->price) * 100); @endphp
                            <span class="product-badge sale">-{{ $d }}%</span>
                        @endif
                        @if($product->getFirstMediaUrl('product_image'))
                            <img src="{{ $product->getFirstMediaUrl('product_image') }}" alt="{{ $product->name }}" class="product-image">
                        @else
                            <div class="product-placeholder"><i class="fa-solid fa-box-open"></i></div>
                        @endif
                    </div>
                    <div class="product-card-body">
                        <h3>{{ $product->name }}</h3>
                        @if($product->description)
                            <p>{{ Str::limit($product->description, 60) }}</p>
                        @endif
                        <div class="product-meta">
                            <div class="price-block">
                                @if($product->sale_price)
                                    <span class="sale-price">₹ {{ number_format($product->sale_price, 0) }}</span>
                                    <span class="old-price">₹ {{ number_format($product->price, 0) }}</span>
                                @else
                                    <span class="regular-price">₹ {{ number_format($product->price, 0) }}</span>
                                @endif
                            </div>
                            @if($product->stock > 0)
                                <span class="stock-badge in-stock">{{ __('store.in_stock') }}</span>
                            @else
                                <span class="stock-badge out-stock">{{ __('store.out_of_stock') }}</span>
                            @endif
                        </div>
                        <div class="product-actions">
                            <button class="product-btn add-to-cart-btn" data-id="{{ $product->id }}">
                                <i class="fa-solid fa-cart-plus"></i> {{ __('store.move_to_cart') }}
                            </button>
                            <form action="{{ route('wishlist.remove', $product->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="wishlist-btn" style="border-color:rgba(239,68,68,0.2);color:#ef4444;background:var(--danger-bg);">
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
