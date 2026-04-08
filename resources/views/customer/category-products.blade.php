@extends('layouts.store')

@section('title', $category->name . ' Products')

@section('content')
    <section class="page-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2>{{ $category->name }}</h2>
                    <p>Browse all products in this category</p>
                </div>
                <a href="{{ route('home') }}" class="section-link">
                    <i class="fa-solid fa-arrow-left"></i> Back to Home
                </a>
            </div>

            <div class="category-page-layout">
                {{-- Side Panel --}}
                <aside class="filter-panel">
                    <div class="filter-card">
                        <h3><i class="fa-solid fa-layer-group"></i> Categories</h3>
                        <ul class="filter-category-list">
                            @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('category.products', $cat->slug ?? $cat->id) }}"
                                       class="{{ $cat->id === $category->id ? 'active' : '' }}">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="filter-card">
                        <h3><i class="fa-solid fa-arrow-down-wide-short"></i> Sort By</h3>
                        <div class="filter-sort-options">
                            <a href="{{ route('category.products', ['slug' => $category->slug ?? $category->id, 'sort' => 'name_asc', 'min_price' => request('min_price'), 'max_price' => request('max_price')]) }}"
                               class="filter-sort-btn {{ request('sort') == 'name_asc' ? 'active' : '' }}">
                                <i class="fa-solid fa-arrow-down-a-z"></i> Name: A to Z
                            </a>
                            <a href="{{ route('category.products', ['slug' => $category->slug ?? $category->id, 'sort' => 'name_desc', 'min_price' => request('min_price'), 'max_price' => request('max_price')]) }}"
                               class="filter-sort-btn {{ request('sort') == 'name_desc' ? 'active' : '' }}">
                                <i class="fa-solid fa-arrow-down-z-a"></i> Name: Z to A
                            </a>
                            <a href="{{ route('category.products', ['slug' => $category->slug ?? $category->id, 'sort' => 'price_asc', 'min_price' => request('min_price'), 'max_price' => request('max_price')]) }}"
                               class="filter-sort-btn {{ request('sort') == 'price_asc' ? 'active' : '' }}">
                                <i class="fa-solid fa-arrow-down-short-wide"></i> Price: Low to High
                            </a>
                            <a href="{{ route('category.products', ['slug' => $category->slug ?? $category->id, 'sort' => 'price_desc', 'min_price' => request('min_price'), 'max_price' => request('max_price')]) }}"
                               class="filter-sort-btn {{ request('sort') == 'price_desc' ? 'active' : '' }}">
                                <i class="fa-solid fa-arrow-up-wide-short"></i> Price: High to Low
                            </a>
                        </div>
                    </div>

                    <div class="filter-card">
                        <h3><i class="fa-solid fa-indian-rupee-sign"></i> Price Range</h3>
                        <form action="{{ route('category.products', $category->slug ?? $category->id) }}" method="GET" class="filter-price-form">
                            @if(request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                            <div class="filter-price-inputs">
                                <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" class="filter-price-input">
                                <span class="filter-price-sep">-</span>
                                <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" class="filter-price-input">
                            </div>
                            <div class="filter-price-actions">
                                <button type="submit" class="filter-apply-btn">Apply Filter</button>
                                <a href="{{ route('category.products', ['slug' => $category->slug ?? $category->id, 'sort' => request('sort')]) }}" class="filter-clear-btn">Clear</a>
                            </div>
                        </form>
                    </div>
                </aside>

                {{-- Products Area --}}
                <div class="category-products-area">
                    <div class="category-results-bar">
                        <p>Showing <strong>{{ $products->count() }}</strong> product{{ $products->count() !== 1 ? 's' : '' }} in <strong>{{ $category->name }}</strong></p>
                        <div class="active-filters">
                            @if(request('sort'))
                                <span class="active-filter-tag">
                                    @switch(request('sort'))
                                        @case('name_asc') A-Z @break
                                        @case('name_desc') Z-A @break
                                        @case('price_asc') Price: Low-High @break
                                        @case('price_desc') Price: High-Low @break
                                    @endswitch
                                </span>
                            @endif
                            @if(request('min_price') || request('max_price'))
                                <span class="active-filter-tag">
                                    @if(request('min_price') && request('max_price'))
                                        ₹ {{ request('min_price') }} - ₹ {{ request('max_price') }}
                                    @elseif(request('min_price'))
                                        From ₹ {{ request('min_price') }}
                                    @else
                                        Up to ₹ {{ request('max_price') }}
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($products->isEmpty())
                        <div class="empty-cart-card">
                            <div class="empty-cart-content">
                                <i class="fa-solid fa-box-open"></i>
                                <h3>No products found</h3>
                                <p>Try adjusting your filters or browse another category.</p>
                                <a href="{{ route('category.products', $category->slug ?? $category->id) }}" class="primary-btn" style="display:inline-flex; margin-top:10px;">Clear All Filters</a>
                            </div>
                        </div>
                    @else
                        <div class="products-grid products-grid-3">
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
                                        {{-- <span class="product-category">{{ $product->category->name ?? 'General' }}</span> --}}
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
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div id="toast"></div>

@push('scripts')
    <script>
    document.querySelectorAll('.wishlist-toggle-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            e.preventDefault();
            var id   = this.dataset.id;
            var icon = this.querySelector('i');

            fetch('/customer/wishlist/toggle/' + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                showToast(data.message, data.status);
                icon.className   = data.in_wishlist ? 'fa-solid fa-heart' : 'fa-regular fa-heart';
                icon.style.color = data.in_wishlist ? '#e05a2b' : '';
                updateBadge('wishlist-count', data.wishlist_count);
            })
            .catch(function () { showToast('Something went wrong.', false); });
        });
    });
    </script>
@endpush
@endsection
