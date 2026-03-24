@extends('layouts.vendor')

@section('title', 'Add Product')
@section('page_title', 'Add Product')
@section('page_subtitle', 'Create a new product for your store')

@section('content')
    <div class="product-form-wrapper">
        <div class="product-form-card">
            <div class="card-header product-form-header">
                <div>
                    <h3>Product Information</h3>
                    <p>Fill in the details below to add a new product to your inventory.</p>
                </div>
            </div>

            <form action="{{ route('vendor.product.store') }}" method="post" enctype="multipart/form-data" class="product-form">
                @csrf

                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control textarea-control" id="description" name="description" rows="2" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group full-width">
                        <label for="full_description" class="form-label">Full Description</label>
                        <textarea class="form-control textarea-control" id="full_description" name="full_description" rows="5">{{ old('full_description') }}</textarea>
                        @error('full_description')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sku" class="form-label">Sku</label>
                        <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku') }}">
                        @error('sku')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" class="form-control" id="category" name="category" value="{{ old('category') }}">
                        @error('category')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="brand" class="form-label">Brand</label>
                        <input type="text" class="form-control" id="brand" name="brand" value="{{ old('brand') }}">
                        @error('brand')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock') }}">
                        @error('stock')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}">
                        @error('price')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sale_price" class="form-label">Sale Price</label>
                        <input type="number" class="form-control" id="sale_price" name="sale_price" value="{{ old('sale_price') }}">
                        @error('sale_price')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="image" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="image" name="image">
                        @error('image')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="product-form-actions">
                    <button type="submit" class="product-submit-btn">Add Product</button>
                </div>
            </form>
        </div>
    </div>
@endsection