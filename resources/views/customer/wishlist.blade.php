@extends('layouts.store')

@section('title', 'Wishlist')

@section('content')
    <section class="page-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2>My Wishlist</h2>
                    <p>Save products you want to buy later</p>
                </div>
            </div>

            <div class="products-grid">
                <div class="product-card">
                    <div class="product-image-wrap">
                        <div class="product-placeholder">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                    </div>
                    <div class="product-card-body">
                        <span class="product-category">Accessories</span>
                        <h3>Smart Watch</h3>
                        <p>Stylish and functional watch for daily use</p>
                        <div class="product-meta">
                            <span class="regular-price">₹3,999</span>
                        </div>
                        <div class="product-actions">
                            <a href="#" class="product-btn">Move to Cart</a>
                            <button class="wishlist-btn">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection