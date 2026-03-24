@extends('layouts.vendor')

@section('title', 'Products')
@section('page_title', 'Products')
@section('page_subtitle', 'Manage all your products in one place')

@section('content')
    <div class="products-wrapper">
        <div class="products-card">

            <div class="card-header products-header">
                <div>
                    <h3>All Products</h3>
                    <p>View and manage your store inventory</p>
                </div>

                <button type="button" class="add-product-btn" id="openProductModal">
                    + Add Product
                </button>
            </div>

            <div class="table-wrapper">
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Name</th>
                            {{-- <th>Image</th> --}}
                            <th>Sku</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Sale Price</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>

                                    <button type="button" class="table-action-btn edit-btn" data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}" data-description="{{ $product->description }}"
                                        data-full_description="{{ $product->full_description }}" data-sku="{{ $product->sku }}"
                                        data-category="{{ $product->category }}" data-brand="{{ $product->brand }}"
                                        data-stock="{{ $product->stock }}" data-price="{{ $product->price }}"
                                        data-sale_price="{{ $product->sale_price }}"
                                        data-image="{{ $product->image ? asset('storage/' . $product->image) : '' }}"
                                        data-category_id="{{ $product->category_id }}"
                                        data-subcategory_id="{{ $product->subcategory_id }}"
                                        onclick="openEditModal(this)">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('vendor.product.destroy', $product->id) }}" method="post"
                                        enctype="multipart/form-data" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn"
                                            onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="product-name">
                                        @if($product->getFirstMediaUrl('product_image'))
                                            <img src="{{ $product->getFirstMediaUrl('product_image') }}" alt="Product Image" width="150">
                                        @else
                                            <p>No image</p>
                                        @endif

                                        <div>
                                            <h4>{{ $product->name }}</h4>
                                            <p>{{ $product->description }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->category?->name ?? '-'}}</td>
                                <td>{{ $product->brand }}</td>

                                <td>
                                    <span class="stock-badge {{ $product->stock > 10 ? 'in-stock' : 'low-stock' }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>

                                <td>₹{{ $product->price }}</td>

                                <td>
                                    @if($product->sale_price)
                                        <span class="sale-price">₹{{ $product->sale_price }}</span>
                                    @else
                                        <span class="no-sale">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        @if($products->isEmpty())
                            <tr>
                                <td colspan="7" class="empty-state">
                                    No products found. Start by adding your first product.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    {{-- Product Modal --}}
    <div class="modal-overlay" id="productModal">
        <div class="custom-modal">
            <div class="custom-modal-header">
                <div>
                    <h3>Add Product</h3>
                    <p>Create a new product for your store</p>
                </div>
                <button type="button" class="modal-close-btn" id="closeProductModal">&times;</button>
            </div>

            <div class="custom-modal-body">
                <form action="{{ route('vendor.product.store') }}" method="post" class="product-form"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                            @error('name')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control textarea-control" id="description" name="description"
                                rows="2">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label for="full_description" class="form-label">Full Description</label>
                            <textarea class="form-control textarea-control" id="full_description" name="full_description"
                                rows="5">{{ old('full_description') }}</textarea>
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
                            <label for="category_id" class="form-label">Category</label>
                                <select class="form-control" id="category_id" name="category_id">
                                    <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                </select>
                        @error('category_id')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="subcategory_id" class="form-label">Subcategory</label>
                        <select class="form-control" id="subcategory_id" name="subcategory_id">
                            <option value="">Select Subcategory</option>
                        </select>
                        @error('subcategory_id')
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
                            <input type="number" class="form-control" id="sale_price" name="sale_price"
                                value="{{ old('sale_price') }}">
                            @error('sale_price')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label for="image" class="form-label">Product Image</label>

                            <div class="custom-file-upload">
                                <input type="file" id="image" name="image" class="file-input" accept="image/*">
                                <label for="image" class="file-label">
                                    <span class="file-btn">Choose File</span>
                                    <span class="file-name" id="fileName">No file chosen</span>
                                </label>
                            </div>

                            @error('image')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="product-form-actions">
                        <button type="button" class="modal-cancel-btn" id="cancelProductModal">Cancel</button>
                        <button type="submit" class="product-submit-btn">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Model --}}

    <div class="modal-overlay" id="editProductModal">
        <div class="custom-modal">
            <div class="custom-modal-header">
                <div>
                    <h3>Edit Product</h3>
                    <p>Update product information</p>
                </div>
                <button type="button" class="modal-close-btn" id="closeEditProductModal">&times;</button>
            </div>

            <div class="custom-modal-body">
                @if ($errors->any())
                    <div class="alert alert-danger" style="color: red; margin-bottom: 15px;">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="post" class="product-form" id="editProductForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="edit_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name">
                        </div>

                        <div class="form-group full-width">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control textarea-control" id="edit_description" name="description"
                                rows="2"></textarea>
                        </div>

                        <div class="form-group full-width">
                            <label for="edit_full_description" class="form-label">Full Description</label>
                            <textarea class="form-control textarea-control" id="edit_full_description"
                                name="full_description" rows="5"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="edit_sku" class="form-label">Sku</label>
                            <input type="text" class="form-control" id="edit_sku" name="sku">
                        </div>

                        <div class="form-group">
                            <label for="edit_category_id" class="form-label">Category</label>
                            <select class="form-control" id="edit_category_id" name="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_subcategory_id" class="form-label">Subcategory</label>
                            <select class="form-control" id="edit_subcategory_id" name="subcategory_id">
                                <option value="">Select Subcategory</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_brand" class="form-label">Brand</label>
                            <input type="text" class="form-control" id="edit_brand" name="brand">
                        </div>

                        <div class="form-group">
                            <label for="edit_stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="edit_stock" name="stock">
                        </div>

                        <div class="form-group">
                            <label for="edit_price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="edit_price" name="price">
                        </div>

                        <div class="form-group">
                            <label for="edit_sale_price" class="form-label">Sale Price</label>
                            <input type="number" class="form-control" id="edit_sale_price" name="sale_price">
                        </div>
                    </div>
                    <div class="form-group full-width">
                        

                        
                    </div>
                    <div class="form-group full-width">
                        <label for="edit_image" class="form-label">Change Product Image</label>

                        <div class="custom-file-upload">
                            <input type="file" id="edit_image" name="image" class="file-input" accept="image/*">
                            <label for="edit_image" class="file-label">
                                <span class="file-btn">Choose File</span>
                                <span class="file-name" id="editFileName">No file chosen</span>
                            </label>
                        </div>

                        @error('image')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="product-form-actions">
                        <button type="button" class="modal-cancel-btn" id="cancelEditProductModal">Cancel</button>
                        <button type="submit" class="product-submit-btn">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        /* =========================
           ADD PRODUCT MODAL
        ========================= */
        const productModal = document.getElementById('productModal');
        const openProductModal = document.getElementById('openProductModal');
        const closeProductModal = document.getElementById('closeProductModal');
        const cancelProductModal = document.getElementById('cancelProductModal');

        function openModal() {
            if (productModal) {
                productModal.classList.add('active');
                document.body.classList.add('modal-open');
            }
        }

        function closeModal() {
            if (productModal) {
                productModal.classList.remove('active');
                document.body.classList.remove('modal-open');
            }
        }

        if (openProductModal) {
            openProductModal.addEventListener('click', openModal);
        }

        if (closeProductModal) {
            closeProductModal.addEventListener('click', closeModal);
        }

        if (cancelProductModal) {
            cancelProductModal.addEventListener('click', closeModal);
        }

        if (productModal) {
            productModal.addEventListener('click', function (e) {
                if (e.target === productModal) {
                    closeModal();
                }
            });
        }

        /* =========================
           ADD PRODUCT FILE INPUT
        ========================= */
        const imageInput = document.getElementById('image');
        const fileName = document.getElementById('fileName');

        if (imageInput && fileName) {
            imageInput.addEventListener('change', function () {
                fileName.textContent = this.files.length ? this.files[0].name : 'No file chosen';
            });
        }

        /* =========================
           EDIT PRODUCT MODAL
        ========================= */
        const editProductModal = document.getElementById('editProductModal');
        const editProductForm = document.getElementById('editProductForm');
        const closeEditProductModal = document.getElementById('closeEditProductModal');
        const cancelEditProductModal = document.getElementById('cancelEditProductModal');

        const currentImagePreview = document.getElementById('currentImagePreview');
        const editImageInput = document.getElementById('edit_image');
        const editFileName = document.getElementById('editFileName');

        function openEditModal(button) {
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const description = button.getAttribute('data-description');
            const fullDescription = button.getAttribute('data-full_description');
            const sku = button.getAttribute('data-sku');
            const category = button.getAttribute('data-category');
            const brand = button.getAttribute('data-brand');
            const stock = button.getAttribute('data-stock');
            const price = button.getAttribute('data-price');
            const salePrice = button.getAttribute('data-sale_price');
            const image = button.getAttribute('data-image');
            const categoryId = button.getAttribute('data-category_id');
            const subcategoryId = button.getAttribute('data-subcategory_id');

            if (editProductForm) {
                editProductForm.action = `/vendor/products/${id}/edit`;
            }

            document.getElementById('edit_name').value = name ?? '';
            document.getElementById('edit_description').value = description ?? '';
            document.getElementById('edit_full_description').value = fullDescription ?? '';
            document.getElementById('edit_sku').value = sku ?? '';
            document.getElementById('edit_category').value = category ?? '';
            document.getElementById('edit_brand').value = brand ?? '';
            document.getElementById('edit_stock').value = stock ?? '';
            document.getElementById('edit_price').value = price ?? '';
            document.getElementById('edit_sale_price').value = salePrice ?? '';
            document.getElementById('edit_category_id').value = categoryId ?? '';
            loadSubcategories(categoryId, document.getElementById('edit_subcategory_id'), subcategoryId);

            if (currentImagePreview) {
                if (image) {
                    currentImagePreview.innerHTML = `<img src="${image}" alt="Product Image">`;
                } else {
                    currentImagePreview.innerHTML = `<span>No image</span>`;
                }
            }

            if (editImageInput) {
                editImageInput.value = '';
            }

            if (editFileName) {
                editFileName.textContent = 'No file chosen';
            }

            if (editProductModal) {
                editProductModal.classList.add('active');
                document.body.classList.add('modal-open');
            }
        }

        function closeEditModal() {
            if (editProductModal) {
                editProductModal.classList.remove('active');
                document.body.classList.remove('modal-open');
            }
        }

        if (closeEditProductModal) {
            closeEditProductModal.addEventListener('click', closeEditModal);
        }

        if (cancelEditProductModal) {
            cancelEditProductModal.addEventListener('click', closeEditModal);
        }

        if (editProductModal) {
            editProductModal.addEventListener('click', function (e) {
                if (e.target === editProductModal) {
                    closeEditModal();
                }
            });
        }

        /* =========================
           EDIT PRODUCT FILE INPUT
        ========================= */
        if (editImageInput && editFileName) {
            editImageInput.addEventListener('change', function () {
                if (this.files.length) {
                    editFileName.textContent = this.files[0].name;

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        if (currentImagePreview) {
                            currentImagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                        }
                    };
                    reader.readAsDataURL(this.files[0]);
                } else {
                    editFileName.textContent = 'No file chosen';
                }
            });
        }
    </script>
    <script>
    const subcategoryBaseUrl = "{{ url('vendor/subcategories') }}";

    const categorySelect = document.getElementById('category_id');
    const subcategorySelect = document.getElementById('subcategory_id');

    const editCategorySelect = document.getElementById('edit_category_id');
    const editSubcategorySelect = document.getElementById('edit_subcategory_id');

    function loadSubcategories(categoryId, targetSelect, selectedSubcategoryId = null) {
        if (!categoryId) {
            targetSelect.innerHTML = '<option value="">Select Subcategory</option>';
            return;
        }

        targetSelect.innerHTML = '<option value="">Loading...</option>';

        fetch(`${subcategoryBaseUrl}/${categoryId}`)
            .then(response => response.json())
            .then(data => {
                targetSelect.innerHTML = '<option value="">Select Subcategory</option>';

                if (data.status && data.subcategories.length > 0) {
                    data.subcategories.forEach(subcategory => {
                        const selected = selectedSubcategoryId == subcategory.id ? 'selected' : '';
                        targetSelect.innerHTML += `<option value="${subcategory.id}" ${selected}>${subcategory.name}</option>`;
                    });
                }
            })
            .catch(error => {
                console.error('Subcategory fetch error:', error);
                targetSelect.innerHTML = '<option value="">Select Subcategory</option>';
            });
    }

    if (categorySelect) {
        categorySelect.addEventListener('change', function () {
            loadSubcategories(this.value, subcategorySelect);
        });
    }

    if (editCategorySelect) {
        editCategorySelect.addEventListener('change', function () {
            loadSubcategories(this.value, editSubcategorySelect);
        });
    }
</script>
@endsection