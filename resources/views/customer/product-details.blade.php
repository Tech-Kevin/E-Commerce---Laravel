@extends('layouts.store')

@section('title', 'Product Details')

@section('content')
    <section class="page-section">
        <div class="store-container">
            <div class="product-details-layout">
                <div class="details-image-card">
                    <div class="details-main-image">
                        <img src="{{ $data->getFirstMediaUrl('product_image') }}" alt="{{ $data->name }}"
                            class="main-image">
                    </div>
                </div>

                <div class="details-content-card">
                    <span class="product-category">{{ $data->category }}</span>
                    <h1> {{ $data->name }}</h1>
                    <p class="details-desc">
                        {{ $data->description }}
                    </p>

                    <div class="details-price">
                        <span class="old-price">₹{{ $data->price }}</span>
                        <span class="sale-price">₹{{ $data->sale_price }}</span>
                    </div>

                    <div class="details-actions">
                        {{-- <a href="{{ route('add.to.cart', ['id' => $data->id]) }}" class="primary-btn">Add to Cart</a>
                        --}}
                        {{-- <button class="primary-btn">Add to Cart</button> --}}
                        <button type="button" class="primary-btn add-to-cart-btn" data-id="{{ $data->id }}">
                            Add to Cart
                        </button>
                        <button class="secondary-btn">Add to Wishlist</button>
                    </div>

                    <div class="details-info-list">
                        <div><strong>SKU :</strong> {{ $data->sku }}</div>
                        <div><strong>Category :</strong> {{ $data->category }}</div>
                        <div><strong>Brand :</strong> {{ $data->brand }}</div>
                        <div><strong>Stock :</strong> {{ $data->stock }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        const csrfToken = '{{ csrf_token() }}';

        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.dataset.id;

                fetch(`/customer/cart/add/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            alert(data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
@endsection