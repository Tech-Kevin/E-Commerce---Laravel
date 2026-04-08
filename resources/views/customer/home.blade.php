@extends('layouts.store')

@section('title', 'Home')

@section('content')
    {{-- ========== HERO SECTION ========== --}}
    <section class="hero-section reveal">
        <div class="store-container hero-grid">
            <div class="hero-content">
                <span class="hero-badge">New Collection</span>
                <h1>Shop smarter with premium everyday essentials</h1>
                <p>
                    Discover trending products, best prices, and a smooth shopping experience designed for comfort.
                </p>

                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="stat-number" data-target="{{ $products->count() }}">0</span>
                        <span class="stat-label">Products</span>
                    </div>
                    <div class="hero-stat">
                        <span class="stat-number" data-target="{{ $categories->count() }}">0</span>
                        <span class="stat-label">Categories</span>
                    </div>
                    <div class="hero-stat">
                        <span class="stat-number" data-target="{{ $bestSellers->sum('total_sold') }}">0</span>
                        <span class="stat-label">Items Sold</span>
                    </div>
                </div>

                <div class="hero-actions">
                    <a href="#products-section" class="primary-btn">Shop Now</a>
                    <a href="#category-section" class="secondary-btn">Explore Categories</a>
                </div>
            </div>

            <div class="hero-card">
                <div class="hero-card-inner">
                    <h3>Special Offer</h3>
                    <p>Up to 40% off on selected products this week.</p>
                    <a href="#products-section" class="mini-btn">View Deals</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== CATEGORIES ========== --}}
    <section class="category-section reveal" id="category-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2>Shop by Category</h2>
                    <p>Browse products from popular categories</p>
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
                    <h2>All Products</h2>
                    <p>Browse our full collection</p>
                </div>
                <a href="{{ route('home') }}" class="section-link">View All</a>
            </div>

            @if (request('search'))
                <p class="filter-label">Search results for "<strong>{{ request('search') }}</strong>"</p>
            @elseif (request('category'))
                <p class="filter-label">Showing results for "<strong>{{ request('category') }}</strong>"</p>
            @endif

            @if ($products->isEmpty())
                <p class="filter-label">No products found.</p>
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
                                    <span class="stock-badge in-stock">In Stock</span>
                                @else
                                    <span class="stock-badge out-stock">Out of Stock</span>
                                @endif
                            </div>

                            <div class="product-actions">
                                <span class="product-btn">View Details</span>
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
                    <h2>{{-- <i class="fa-solid fa-sparkles" style="color: #e67e4d;"></i>  --}}New Arrivals</h2>
                    <p>Fresh additions to our store</p>
                </div>
            </div>

            <div class="scroll-row">
                <button class="scroll-btn scroll-left" aria-label="Scroll left"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="scroll-track">
                    @foreach ($newArrivals as $product)
                        <a href="{{ route('product.details', ['id' => $product->id]) }}" class="product-card scroll-card">
                            {{-- <div class="newarrival-badge"><i class="fa-solid fa-bolt"></i> New</div> --}}
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
                    <h2>{{-- <i class="fa-solid fa-fire" style="color: #e67e4d;"></i> --}}</i>Best Sellers</h2>
                    <p>Most loved products by our customers</p>
                </div>
            </div>

            <div class="scroll-row">
                <button class="scroll-btn scroll-left" aria-label="Scroll left"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="scroll-track">
                    @foreach ($bestSellers as $product)
                        <a href="{{ route('product.details', ['id' => $product->id]) }}" class="product-card scroll-card">
                            {{-- <div class="bestseller-badge"><i class="fa-solid fa-fire-flame-curved"></i> {{ $product->total_sold }} sold</div> --}}
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
                    <h2>Stay in the loop</h2>
                    <p>Get notified about new arrivals, exclusive deals, and more.</p>
                    <form class="newsletter-form" onsubmit="event.preventDefault(); showToast('Thanks for subscribing!', true);">
                        <input type="email" placeholder="Enter your email address" required>
                        <button type="submit" class="primary-btn">Subscribe</button>
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
                    <h4>Fast Delivery</h4>
                    <p>Quick shipping on all major orders</p>
                </div>
            </div>

            <div class="promo-box">
                <i class="fa-solid fa-shield-heart"></i>
                <div>
                    <h4>Secure Payments</h4>
                    <p>Trusted and safe checkout process</p>
                </div>
            </div>

            <div class="promo-box">
                <i class="fa-solid fa-rotate-left"></i>
                <div>
                    <h4>Easy Returns</h4>
                    <p>Simple return process for eligible products</p>
                </div>
            </div>
        </div>
    </section>

    <div id="toast"></div>

@push('scripts')
    <script src="{{ asset('js/customer/home.js') }}"></script>
@endpush
@endsection
