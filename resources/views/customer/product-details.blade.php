@extends('layouts.store')

@section('title', $data->name)

@section('content')
    <div id="toast"></div>

    <section class="page-section">
        <div class="store-container">

            {{-- Breadcrumb --}}
            <nav style="margin-bottom:20px;display:flex;align-items:center;gap:8px;font-size:13px;font-weight:500;color:var(--text-faint);">
                <a href="{{ route('home') }}" style="color:var(--text-muted);transition:color 0.2s;">{{ __('store.home') }}</a>
                <i class="fa-solid fa-chevron-right" style="font-size:9px;"></i>
                @if($data->category)
                    <a href="{{ route('category.products', $data->category->slug ?? $data->category->id) }}" style="color:var(--text-muted);transition:color 0.2s;">{{ $data->category->name }}</a>
                    <i class="fa-solid fa-chevron-right" style="font-size:9px;"></i>
                @endif
                <span style="color:var(--text-primary);font-weight:600;">{{ Str::limit($data->name, 40) }}</span>
            </nav>

            <div class="product-details-layout">
                <div class="details-image-card">
                    <div class="details-main-image">
                        @if($data->sale_price)
                            @php $discount = round((($data->price - $data->sale_price) / $data->price) * 100); @endphp
                            <span class="product-badge sale" style="position:absolute;top:16px;left:16px;z-index:2;">-{{ $discount }}% OFF</span>
                        @endif
                        @if($data->getFirstMediaUrl('product_image'))
                            <img src="{{ $data->getFirstMediaUrl('product_image') }}" alt="{{ $data->name }}" class="main-image">
                        @else
                            <div class="product-placeholder" style="height:100%;border-radius:var(--radius-lg);">
                                <i class="fa-solid fa-box-open" style="font-size:72px;"></i>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="details-content-card">
                    @if($data->category)
                        <span class="product-category">{{ $data->category->name }}</span>
                    @endif

                    <h1>{{ $data->name }}</h1>

                    @if($data->description)
                        <p class="details-desc">{{ $data->description }}</p>
                    @endif

                    <div class="details-price">
                        @if($data->sale_price)
                            <span style="font-size:32px; font-weight:900; color:var(--accent); letter-spacing:-0.5px;">
                                ₹ {{ number_format($data->sale_price, 2) }}
                            </span>
                            <span style="font-size:18px; color:var(--text-faint); text-decoration:line-through;">
                                ₹ {{ number_format($data->price, 2) }}
                            </span>
                            @php $discount = round((($data->price - $data->sale_price) / $data->price) * 100); @endphp
                            <span class="discount-pct" style="font-size:13px;padding:4px 10px;">Save {{ $discount }}%</span>
                        @else
                            <span style="font-size:32px; font-weight:900; color:var(--text-primary); letter-spacing:-0.5px;">
                                ₹ {{ number_format($data->price, 2) }}
                            </span>
                        @endif
                    </div>

                    @if($data->stock > 0)
                        <span class="stock-badge in-stock" style="margin-bottom:20px; display:inline-flex;align-items:center;gap:6px;padding:6px 14px;">
                            <i class="fa-solid fa-circle-check"></i> {{ __('store.in_stock_left', ['count' => $data->stock]) }}
                        </span>
                    @else
                        <span class="stock-badge out-stock" style="margin-bottom:20px; display:inline-flex;align-items:center;gap:6px;padding:6px 14px;">
                            <i class="fa-solid fa-circle-xmark"></i> {{ __('store.out_of_stock') }}
                        </span>
                    @endif

                    <div class="details-actions">
                        <button type="button" class="primary-btn add-to-cart-btn"
                            data-id="{{ $data->id }}"
                            {{ $data->stock == 0 ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>
                            <i class="fa-solid fa-cart-plus"></i> {{ __('store.add_to_cart') }}
                        </button>

                        @auth
                        <button type="button" class="secondary-btn wishlist-toggle-btn"
                            data-id="{{ $data->id }}"
                            id="wishlist-btn-{{ $data->id }}">
                            <i class="fa-regular fa-heart"></i> {{ __('store.add_to_wishlist') }}
                        </button>
                        @endauth
                    </div>

                    {{-- Product Info Grid --}}
                    <div class="details-info-list" style="margin-top:24px;">
                        @if($data->sku)
                            <div><strong>{{ __('store.sku') }}:</strong> <code style="background:var(--bg-warm);padding:2px 8px;border-radius:6px;font-size:13px;">{{ $data->sku }}</code></div>
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
                        <div><strong>{{ __('store.stock') }}:</strong> {{ $data->stock }} {{ __('store.units') }}</div>
                    </div>

                    @if($data->full_description)
                    <div style="margin-top:28px; padding-top:24px; border-top:1.5px solid var(--border-light);">
                        <h3 style="margin-bottom:12px; font-size:16px; font-weight:800; color:var(--text-primary); display:flex;align-items:center;gap:8px;">
                            <i class="fa-solid fa-align-left" style="color:var(--accent);font-size:14px;"></i>
                            {{ __('store.product_description') }}
                        </h3>
                        <p style="color:var(--text-muted); line-height:1.8; font-size:14px;">{{ $data->full_description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Suggested Products --}}
            @if($suggestedProducts->isNotEmpty())
            <div class="suggested-section reveal">
                <h2><i class="fa-solid fa-wand-magic-sparkles" style="color:var(--accent);margin-right:8px;"></i>{{ __('store.you_may_also_like') }}</h2>
                <div class="products-grid">
                    @foreach($suggestedProducts as $product)
                        <a href="{{ route('product.details', $product->id) }}" class="product-card">
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
                                <div class="product-meta">
                                    <div class="price-block">
                                        @if($product->sale_price)
                                            <span class="sale-price">₹ {{ number_format($product->sale_price, 0) }}</span>
                                            <span class="old-price">₹ {{ number_format($product->price, 0) }}</span>
                                        @else
                                            <span class="regular-price">₹ {{ number_format($product->price, 0) }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="product-actions">
                                    <span class="product-btn">{{ __('store.view_details') }}</span>
                                    @auth
                                    <button class="wishlist-btn wishlist-toggle-btn" data-id="{{ $product->id }}" id="wl-{{ $product->id }}" onclick="event.preventDefault();event.stopPropagation();">
                                        <i class="fa-regular fa-heart"></i>
                                    </button>
                                    @endauth
                                </div>
                            </div>
                        </a>
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
