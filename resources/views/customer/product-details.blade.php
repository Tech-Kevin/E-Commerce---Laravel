@extends('layouts.store')

@section('title', $data->name)

@section('content')
    <div id="toast"></div>

    <section class="page-section">
        <div class="store-container">

            <div style="margin-bottom:16px;">
                <a href="{{ route('home') }}" style="color:#b28868; font-size:14px;">
                    <i class="fa-solid fa-arrow-left" style="margin-right:6px;"></i>{{ __('store.back_to_products') }}
                </a>
            </div>

            <div class="product-details-layout">
                <div class="details-image-card">
                    <div class="details-main-image">
                        @if($data->getFirstMediaUrl('product_image'))
                            <img src="{{ $data->getFirstMediaUrl('product_image') }}" alt="{{ $data->name }}" class="main-image">
                        @else
                            <div class="product-placeholder" style="height:320px; display:flex; align-items:center; justify-content:center; background:#f2e7dc; border-radius:12px;">
                                <i class="fa-solid fa-box-open" style="font-size:64px; color:#c9a890;"></i>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="details-content-card">
                    @if($data->category)
                        <span class="product-category">{{ $data->category->name }}</span>
                    @endif

                    <h1>{{ $data->name }}</h1>

                    <p class="details-desc">{{ $data->description }}</p>

                    <div class="details-price">
                        @if($data->sale_price)
                            <span class="sale-price" style="font-size:28px; font-weight:700; color:#e05a2b;">
                                ₹ {{ number_format($data->sale_price, 2) }}
                            </span>
                            <span class="old-price" style="font-size:18px; color:#aaa; text-decoration:line-through; margin-left:10px;">
                                ₹ {{ number_format($data->price, 2) }}
                            </span>
                        @else
                            <span class="regular-price" style="font-size:28px; font-weight:700; color:#2f241f;">
                                ₹ {{ number_format($data->price, 2) }}
                            </span>
                        @endif
                    </div>

                    @if($data->stock > 0)
                        <span class="stock-badge in-stock" style="margin-bottom:16px; display:inline-block;">
                            {{ __('store.in_stock_left', ['count' => $data->stock]) }}
                        </span>
                    @else
                        <span class="stock-badge out-stock" style="margin-bottom:16px; display:inline-block;">
                            {{ __('store.out_of_stock') }}
                        </span>
                    @endif

                    <div class="details-actions">
                        <button type="button" class="primary-btn add-to-cart-btn"
                            data-id="{{ $data->id }}"
                            {{ $data->stock == 0 ? 'disabled' : '' }}>
                            <i class="fa-solid fa-cart-plus" style="margin-right:6px;"></i>{{ __('store.add_to_cart') }}
                        </button>

                        @auth
                        <button type="button" class="secondary-btn wishlist-toggle-btn"
                            data-id="{{ $data->id }}"
                            id="wishlist-btn-{{ $data->id }}">
                            <i class="fa-regular fa-heart" style="margin-right:6px;"></i>{{ __('store.add_to_wishlist') }}
                        </button>
                        @endauth
                    </div>

                    <div class="details-info-list" style="margin-top:20px;">
                        @if($data->sku)
                            <div><strong>{{ __('store.sku') }}:</strong> {{ $data->sku }}</div>
                        @endif
                        @if($data->category)
                            <div><strong>{{ __('store.category') }}:</strong> {{ $data->category->name }}</div>
                        @endif
                        @if($data->subcategory)
                            <div><strong>{{ __('store.subcategory') }}:</strong> {{ $data->subcategory->name }}</div>
                        @endif
                        @if($data->brand)
                            <div><strong>{{ __('store.brand') }}:</strong> {{ $data->brand }}</div>
                        @endif
                        <div><strong>{{ __('store.stock') }}:</strong> {{ $data->stock }}</div>
                    </div>

                    @if($data->full_description)
                    <div style="margin-top:24px; padding-top:20px; border-top:1px solid #f2e7dc;">
                        <h3 style="margin-bottom:10px; font-size:16px;">{{ __('store.product_description') }}</h3>
                        <p style="color:#6d5c53; line-height:1.7; font-size:14px;">{{ $data->full_description }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @if($suggestedProducts->isNotEmpty())
            <div class="suggested-section">
                <h2>{{ __('store.you_may_also_like') }}</h2>
                <div class="products-grid">
                    @foreach($suggestedProducts as $product)
                        <div class="product-card">
                            <div class="product-image-wrap">
                                @if($product->getFirstMediaUrl('product_image'))
                                    <img src="{{ $product->getFirstMediaUrl('product_image') }}" alt="{{ $product->name }}" class="product-image">
                                @else
                                    <div class="product-placeholder">
                                        <i class="fa-solid fa-box-open"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="product-card-body">
                                <h3>{{ $product->name }}</h3>
                                <div class="product-meta">
                                    <div class="price-block">
                                        @if($product->sale_price)
                                            <span class="old-price">₹ {{ $product->price }}</span>
                                            <span class="sale-price">₹ {{ $product->sale_price }}</span>
                                        @else
                                            <span class="sale-price">₹ {{ $product->price }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="product-actions">
                                    <a href="{{ route('product.details', $product->id) }}" class="product-btn" style="text-decoration:none;">
                                        {{ __('store.view_details') }}
                                    </a>
                                    @auth
                                    <button class="wishlist-btn wishlist-toggle-btn" data-id="{{ $product->id }}" id="wl-{{ $product->id }}">
                                        <i class="fa-regular fa-heart"></i>
                                    </button>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </section>

@push('scripts')
    <script src="{{ asset('js/customer/product-details.js') }}"></script>
@endpush
@endsection
