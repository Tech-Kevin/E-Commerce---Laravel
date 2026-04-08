@extends('layouts.vendor')

@section('title', 'Sales')
@section('page_title', 'Sales')
@section('page_subtitle', 'Schedule and manage product sales')

@section('content')
    <div class="products-wrapper">
        <div class="products-card">

            <div class="card-header products-header">
                <div>
                    <h3>All Sales</h3>
                    <p>Manage scheduled, active, and expired sales</p>
                </div>

                <button type="button" class="add-product-btn" id="openSaleModal">
                    + Create Sale
                </button>
            </div>

            <div class="table-wrapper">
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Regular Price</th>
                            <th>Sale Price</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($sales as $sale)
                            <tr>
                                <td>
                                    <div class="product-name">
                                        @if($sale->product && $sale->product->getFirstMediaUrl('product_image'))
                                            <img src="{{ $sale->product->getFirstMediaUrl('product_image') }}" alt="" width="40" style="border-radius: 8px;">
                                        @endif
                                        <div>
                                            <h4>{{ $sale->product->name ?? 'Deleted Product' }}</h4>
                                        </div>
                                    </div>
                                </td>
                                <td>₹ {{ $sale->product->price ?? '-' }}</td>
                                <td><span class="sale-price">₹ {{ $sale->sale_price }}</span></td>
                                <td>{{ $sale->start_date->format('d M Y, h:i A') }}</td>
                                <td>{{ $sale->end_date->format('d M Y, h:i A') }}</td>
                                <td>
                                    @if($sale->status === 'active')
                                        <span class="stock-badge in-stock">Active</span>
                                    @elseif($sale->status === 'scheduled')
                                        <span class="stock-badge" style="background: #fff3cd; color: #856404;">Scheduled</span>
                                    @else
                                        <span class="stock-badge low-stock">Expired</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px; align-items: center;">
                                        @if($sale->status !== 'expired')
                                            <button type="button" class="table-action-btn edit-btn"
                                                data-id="{{ $sale->id }}"
                                                data-product-name="{{ $sale->product->name ?? '' }}"
                                                data-sale-price="{{ $sale->sale_price }}"
                                                data-start-date="{{ $sale->start_date->format('Y-m-d\TH:i') }}"
                                                data-end-date="{{ $sale->end_date->format('Y-m-d\TH:i') }}"
                                                onclick="openEditSaleModal(this)">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <form action="{{ route('vendor.sales.destroy', $sale->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-btn" onclick="return confirm('Are you sure? This will remove the sale and reset the product price.')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span style="color: #aaa;">—</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if($sales->isEmpty())
                            <tr>
                                <td colspan="7" class="empty-state">
                                    No sales found. Create your first sale to get started.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Create Sale Modal --}}
    <div class="modal-overlay" id="saleModal">
        <div class="custom-modal">
            <div class="custom-modal-header">
                <div>
                    <h3>Create Sale</h3>
                    <p>Schedule a sale for a product</p>
                </div>
                <button type="button" class="modal-close-btn" id="closeSaleModal">&times;</button>
            </div>

            <div class="custom-modal-body">
                <form action="{{ route('vendor.sales.store') }}" method="POST" class="product-form">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="product_id" class="form-label">Product</label>
                            <select class="form-control" id="product_id" name="product_id" required>
                                <option value="">Select Product</option>
                                <option value="all">All Products</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }} (₹{{ $product->price }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label for="sale_price" class="form-label">Sale Price</label>
                            <input type="number" step="0.01" class="form-control" id="sale_price" name="sale_price" required>
                            <small id="priceHint" style="color: #8b7769; font-size: 12px; margin-top: 4px; display: block;"></small>
                            @error('sale_price')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="start_date" class="form-label">Start Date & Time</label>
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
                            @error('start_date')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="end_date" class="form-label">End Date & Time</label>
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
                            @error('end_date')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="product-form-actions">
                        <button type="button" class="modal-cancel-btn" id="cancelSaleModal">Cancel</button>
                        <button type="submit" class="product-submit-btn">Create Sale</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Sale Modal --}}
    <div class="modal-overlay" id="editSaleModal">
        <div class="custom-modal">
            <div class="custom-modal-header">
                <div>
                    <h3>Edit Sale</h3>
                    <p id="editSaleProduct">Update sale details</p>
                </div>
                <button type="button" class="modal-close-btn" id="closeEditSaleModal">&times;</button>
            </div>

            <div class="custom-modal-body">
                <form method="POST" class="product-form" id="editSaleForm">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="edit_sale_price" class="form-label">Sale Price</label>
                            <input type="number" step="0.01" class="form-control" id="edit_sale_price" name="sale_price" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_start_date" class="form-label">Start Date & Time</label>
                            <input type="datetime-local" class="form-control" id="edit_start_date" name="start_date" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_end_date" class="form-label">End Date & Time</label>
                            <input type="datetime-local" class="form-control" id="edit_end_date" name="end_date" required>
                        </div>
                    </div>

                    <div class="product-form-actions">
                        <button type="button" class="modal-cancel-btn" id="cancelEditSaleModal">Cancel</button>
                        <button type="submit" class="product-submit-btn">Update Sale</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    // Create modal
    const saleModal = document.getElementById('saleModal');
    document.getElementById('openSaleModal').addEventListener('click', () => saleModal.classList.add('active'));
    document.getElementById('closeSaleModal').addEventListener('click', () => saleModal.classList.remove('active'));
    document.getElementById('cancelSaleModal').addEventListener('click', () => saleModal.classList.remove('active'));
    saleModal.addEventListener('click', (e) => { if (e.target === saleModal) saleModal.classList.remove('active'); });

    // Edit modal
    const editSaleModal = document.getElementById('editSaleModal');
    document.getElementById('closeEditSaleModal').addEventListener('click', () => editSaleModal.classList.remove('active'));
    document.getElementById('cancelEditSaleModal').addEventListener('click', () => editSaleModal.classList.remove('active'));
    editSaleModal.addEventListener('click', (e) => { if (e.target === editSaleModal) editSaleModal.classList.remove('active'); });

    function openEditSaleModal(btn) {
        const id = btn.getAttribute('data-id');
        const productName = btn.getAttribute('data-product-name');
        const salePrice = btn.getAttribute('data-sale-price');
        const startDate = btn.getAttribute('data-start-date');
        const endDate = btn.getAttribute('data-end-date');

        document.getElementById('editSaleForm').action = '/vendor/sales/' + id;
        document.getElementById('editSaleProduct').textContent = productName;
        document.getElementById('edit_sale_price').value = salePrice;
        document.getElementById('edit_start_date').value = startDate;
        document.getElementById('edit_end_date').value = endDate;

        editSaleModal.classList.add('active');
    }

    // Price hint on product select
    document.getElementById('product_id').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const price = selected.getAttribute('data-price');
        const hint = document.getElementById('priceHint');
        if (this.value === 'all') {
            hint.textContent = 'Sale will be applied to all products where sale price is lower than regular price';
        } else if (price) {
            hint.textContent = 'Regular price: ₹' + price + ' — sale price must be lower';
        } else {
            hint.textContent = '';
        }
    });
</script>
@endpush
@endsection
