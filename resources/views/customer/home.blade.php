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
                <div class="category-card">Electronics</div>
                <div class="category-card">Fashion</div>
                <div class="category-card">Accessories</div>
                <div class="category-card">Home Decor</div>
                <div class="category-card">Beauty</div>
                <div class="category-card">Footwear</div>
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
                <a href="#" class="section-link">View All</a>
            </div>

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
                            <span class="product-category">{{ $product->category ?? 'General' }}</span>
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
                                <button class="wishlist-btn"> 
                                    <i class="fa-regular fa-heart"></i>
                                </button>
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
@endsection