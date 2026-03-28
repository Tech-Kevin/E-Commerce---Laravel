@extends('layouts.store')

@section('title', 'Home')

@section('content')
    <section class="hero-section">
        <div class="store-container hero-grid">
            <div class="hero-content">
                <span class="hero-badge">New Collection</span>
                <h1>Shop smarter with premium everyday essentials</h1>
                <p>
                    Discover trending products, best prices, and a smooth shopping experience designed for comfort.
                </p>

                <div class="hero-actions">
                    <a href="#" class="primary-btn">Shop Now</a>
                    <a href="#" class="secondary-btn">Explore Categories</a>
                </div>
            </div>

            <div class="hero-card">
                <div class="hero-card-inner">
                    <h3>Special Offer</h3>
                    <p>Up to 40% off on selected products this week.</p>
                    <a href="#" class="mini-btn">View Deals</a>
                </div>
            </div>
        </div>
    </section>

    <section class="category-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2>Shop by Category</h2>
                    <p>Browse products from popular categories</p>
                </div>
            </div>

            <div class="category-grid">
                {{-- <div class="category-card">Electronics</div> --}}
                @php
                    $activecategory = request()->query('category');
                @endphp
                @foreach($categories as $cat)
                    <a href="{{ route('home', ['category' => $cat->name]) }}"
                       class="category-card {{ $activecategory == $cat->name ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="products-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2>Featured Products</h2>
                    <p>Top picks for you</p>
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
                            <span class="product-category">{{ $product->category->name ?? 'General' }}</span>
                            <h3>{{ $product->name }}</h3>
                            <p>{{ $product->description }}</p>

                            <div class="product-meta">
                                <div class="price-block">
                                    @if($product->sale_price)
                                        <span class="old-price">₹{{ $product->price }}</span>
                                        <span class="sale-price">₹{{ $product->sale_price }}</span>
                                    @else
                                        <span class="regular-price">₹{{ $product->price }}</span>
                                    @endif
                                </div>

                                @if($product->stock > 0)
                                    <span class="stock-badge in-stock">In Stock</span>
                                @else
                                    <span class="stock-badge out-stock">Out of Stock</span>
                                @endif
                            </div>

                            <div class="product-actions">
                                <a href="{{ route('product.details', ['id' => $product->id]) }}" class="product-btn">View Details</a>
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
    </section>

    <section class="promo-strip">
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

    {{-- Toast --}}
    <div id="toast" style="
        display:none; position:fixed; bottom:28px; right:28px; z-index:9999;
        background:#2f241f; color:#fff; padding:14px 22px; border-radius:12px;
        font-size:14px; font-weight:600; box-shadow:0 4px 20px rgba(0,0,0,.18);
        transition:opacity .3s ease;"></div>

    <script>
    function showToast(message, success) {
        const t = document.getElementById('toast');
        t.textContent = message;
        t.style.background = success ? '#2f241f' : '#c0392b';
        t.style.display = 'block'; t.style.opacity = '1';
        setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.style.display = 'none', 300); }, 2500);
    }

    document.querySelectorAll('.wishlist-toggle-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const icon = this.querySelector('i');
            fetch(`/customer/wishlist/toggle/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                showToast(data.message, data.status);
                icon.className = data.in_wishlist ? 'fa-solid fa-heart' : 'fa-regular fa-heart';
                icon.style.color = data.in_wishlist ? '#e05a2b' : '';
            })
            .catch(() => showToast('Something went wrong.', false));
        });
    });
    </script>
@endsection