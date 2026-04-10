@extends('layouts.store')

@section('title', __('store.home'))

@section('content')
    {{-- ========== HERO SECTION ========== --}}
    <section class="hero-section reveal">
        <div class="store-container hero-grid">
            <div class="hero-content">
                <span class="hero-badge">{{ __('store.new_collection') }}</span>
                <h1>{{ __('store.hero_title') }}</h1>
                <p>{{ __('store.hero_subtitle') }}</p>

                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="stat-number" data-target="{{ $products->count() }}">0</span>
                        <span class="stat-label">{{ __('store.products') }}</span>
                    </div>
                    <div class="hero-stat">
                        <span class="stat-number" data-target="{{ $categories->count() }}">0</span>
                        <span class="stat-label">{{ __('store.categories') }}</span>
                    </div>
                    <div class="hero-stat">
                        <span class="stat-number" data-target="{{ $bestSellers->sum('total_sold') }}">0</span>
                        <span class="stat-label">{{ __('store.items_sold') }}</span>
                    </div>
                </div>

                <div class="hero-actions">
                    <a href="#products-section" class="primary-btn">{{ __('store.shop_now') }}</a>
                    <a href="#category-section" class="secondary-btn">{{ __('store.explore_categories') }}</a>
                </div>
            </div>

            <div class="hero-card">
                <div class="hero-card-inner">
                    <h3>{{ __('store.special_offer') }}</h3>
                    <p>{{ __('store.offer_text') }}</p>
                    <a href="#products-section" class="mini-btn">{{ __('store.view_deals') }}</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== CATEGORIES ========== --}}
    <section class="category-section reveal" id="category-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2>{{ __('store.shop_by_category') }}</h2>
                    <p>{{ __('store.browse_categories') }}</p>
                </div>
            </div>

            <div class="category-grid">
                @php
                    $activecategory = request()->query('category');
                    $catIcons = ['fa-laptop', 'fa-shirt', 'fa-couch', 'fa-futbol', 'fa-book', 'fa-gem', 'fa-blender', 'fa-baby', 'fa-car', 'fa-pills'];
                @endphp
                @foreach($categories as $index => $cat)
                    <a href="{{ route('home', ['category' => $cat->name]) }}"
                       class="category-card {{ $activecategory == $cat->name ? 'active' : '' }}">
                        <i class="fa-solid {{ $catIcons[$index % count($catIcons)] }} category-icon"></i>
                        <span>{{ $cat->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ========== ALL PRODUCTS ========== --}}
    <section class="products-section reveal" id="products-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2>{{ __('store.all_products') }}</h2>
                    <p>{{ __('store.browse_collection') }}</p>
                </div>
                <a href="{{ route('home') }}" class="section-link">{{ __('store.view_all') }}</a>
            </div>

            @if (request('search'))
                <p class="filter-label">{!! __('store.search_results_for', ['query' => request('search')]) !!}</p>
            @elseif (request('category'))
                <p class="filter-label">{!! __('store.showing_results_for', ['query' => request('category')]) !!}</p>
            @endif

            @if ($products->isEmpty())
                <p class="filter-label">{{ __('store.no_products_found') }}</p>
            @endif

            <div class="products-grid">
                @foreach ($products as $product)
                    <a href="{{ route('product.details', ['id' => $product->id]) }}" class="product-card">
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
                                        <span class="regular-price">₹ {{ $product->price }}</span>
                                    @endif
                                </div>

                                @if($product->stock > 0)
                                    <span class="stock-badge in-stock">{{ __('store.in_stock') }}</span>
                                @else
                                    <span class="stock-badge out-stock">{{ __('store.out_of_stock') }}</span>
                                @endif
                            </div>

                            <div class="product-actions">
                                <span class="product-btn">{{ __('store.view_details') }}</span>
                                @auth
                                <button class="wishlist-btn wishlist-toggle-btn" data-id="{{ $product->id }}" id="wl-{{ $product->id }}">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                                @endauth
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ========== NEW ARRIVALS ========== --}}
    @if($newArrivals->isNotEmpty())
    <section class="newarrivals-section reveal">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2>{{ __('store.new_arrivals') }}</h2>
                    <p>{{ __('store.fresh_additions') }}</p>
                </div>
            </div>

            <div class="scroll-row">
                <button class="scroll-btn scroll-left" aria-label="Scroll left"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="scroll-track">
                    @foreach ($newArrivals as $product)
                        <a href="{{ route('product.details', ['id' => $product->id]) }}" class="product-card scroll-card">
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
                                <div class="price-block">
                                    @if($product->sale_price)
                                        <span class="old-price">₹ {{ $product->price }}</span>
                                        <span class="sale-price">₹ {{ $product->sale_price }}</span>
                                    @else
                                        <span class="regular-price">₹ {{ $product->price }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <button class="scroll-btn scroll-right" aria-label="Scroll right"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>
    </section>
    @endif

    {{-- ========== BEST SELLERS ========== --}}
    @if($bestSellers->isNotEmpty())
    <section class="bestsellers-section reveal">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2>{{ __('store.best_sellers') }}</h2>
                    <p>{{ __('store.most_loved') }}</p>
                </div>
            </div>

            <div class="scroll-row">
                <button class="scroll-btn scroll-left" aria-label="Scroll left"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="scroll-track">
                    @foreach ($bestSellers as $product)
                        <a href="{{ route('product.details', ['id' => $product->id]) }}" class="product-card scroll-card">
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
                                <div class="price-block">
                                    @if($product->sale_price)
                                        <span class="old-price">₹ {{ $product->price }}</span>
                                        <span class="sale-price">₹ {{ $product->sale_price }}</span>
                                    @else
                                        <span class="regular-price">₹ {{ $product->price }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <button class="scroll-btn scroll-right" aria-label="Scroll right"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>
    </section>
    @endif

    {{-- ========== NEWSLETTER CTA ========== --}}
    <section class="newsletter-section reveal">
        <div class="store-container">
            <div class="newsletter-card">
                <div class="newsletter-content">
                    <i class="fa-solid fa-envelope-open-text newsletter-icon"></i>
                    <h2>{{ __('store.stay_in_loop') }}</h2>
                    <p>{{ __('store.newsletter_text') }}</p>
                    <form class="newsletter-form" onsubmit="event.preventDefault(); showToast(@json(__('store.thanks_subscribe')), true);">
                        <input type="email" placeholder="{{ __('store.enter_email') }}" required>
                        <button type="submit" class="primary-btn">{{ __('store.subscribe') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== PROMO STRIP ========== --}}
    <section class="promo-strip reveal">
        <div class="store-container promo-grid">
            <div class="promo-box">
                <i class="fa-solid fa-truck-fast"></i>
                <div>
                    <h4>{{ __('store.fast_delivery') }}</h4>
                    <p>{{ __('store.fast_delivery_text') }}</p>
                </div>
            </div>

            <div class="promo-box">
                <i class="fa-solid fa-shield-heart"></i>
                <div>
                    <h4>{{ __('store.secure_payments') }}</h4>
                    <p>{{ __('store.secure_payments_text') }}</p>
                </div>
            </div>

            <div class="promo-box">
                <i class="fa-solid fa-rotate-left"></i>
                <div>
                    <h4>{{ __('store.easy_returns') }}</h4>
                    <p>{{ __('store.easy_returns_text') }}</p>
                </div>
            </div>
        </div>
    </section>

    <div id="toast"></div>

@push('scripts')
    <script src="{{ asset('js/customer/home.js') }}"></script>
@endpush
@endsection
