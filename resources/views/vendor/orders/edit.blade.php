@extends('layouts.vendor')

@section('title', 'Edit Order #' . $order->order_number)
@section('page_title', 'Edit Order #' . $order->order_number)
@section('page_subtitle', 'Modify items, totals, payment and shipping in one place')

@section('content')
    <form action="{{ route('vendor.orders.update', $order->id) }}" method="POST" class="sa-form">
        @csrf
        @method('PUT')

        <div class="sa-form-grid">
            <div class="sa-card">
                <div class="sa-card-head">
                    <div>
                        <h3><i class="fa-solid fa-user"></i> Customer & Shipping</h3>
                        <p>Update delivery details</p>
                    </div>
                </div>
                <div class="sa-card-body sa-grid-2">
                    <div>
                        <label>Full Name</label>
                        <input class="sa-input" type="text" name="full_name" value="{{ old('full_name', $order->full_name) }}" required>
                    </div>
                    <div>
                        <label>Phone</label>
                        <input class="sa-input" type="text" name="phone" value="{{ old('phone', $order->phone) }}" required>
                    </div>
                    <div class="sa-col-span-2">
                        <label>Address</label>
                        <input class="sa-input" type="text" name="address" value="{{ old('address', $order->address) }}" required>
                    </div>
                    <div>
                        <label>City</label>
                        <input class="sa-input" type="text" name="city" value="{{ old('city', $order->city) }}" required>
                    </div>
                    <div>
                        <label>Pincode</label>
                        <input class="sa-input" type="text" name="pincode" value="{{ old('pincode', $order->pincode) }}" required>
                    </div>
                </div>
            </div>

            <div class="sa-card">
                <div class="sa-card-head">
                    <div>
                        <h3><i class="fa-solid fa-credit-card"></i> Status & Payment</h3>
                        <p>Change status, payment and assignee</p>
                    </div>
                </div>
                <div class="sa-card-body sa-grid-2">
                    <div>
                        <label>Order Status</label>
                        <select class="sa-input" name="status">
                            @foreach(['pending','processing','shipped','arriving','delivered','completed','cancelled'] as $s)
                                <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Payment Status</label>
                        <select class="sa-input" name="payment_status">
                            @foreach(['pending','paid','refunded','failed'] as $s)
                                <option value="{{ $s }}" @selected($order->payment_status === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Payment Method</label>
                        <input class="sa-input" type="text" name="payment_method" value="{{ old('payment_method', $order->payment_method) }}">
                    </div>
                    <div>
                        <label>Shipping Charge</label>
                        <input class="sa-input" type="number" step="0.01" name="shipping" value="{{ old('shipping', $order->shipping) }}" required>
                    </div>
                    <div class="sa-col-span-2">
                        <label>Delivery Boy</label>
                        <select class="sa-input" name="delivery_boy_id">
                            <option value="">— Unassigned —</option>
                            @foreach($deliveryBoys as $boy)
                                <option value="{{ $boy->id }}" @selected($order->delivery_boy_id == $boy->id)>{{ $boy->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="sa-card" style="margin-top: 18px;">
            <div class="sa-card-head">
                <div>
                    <h3><i class="fa-solid fa-box-open"></i> Order Items</h3>
                    <p>Add, edit or remove items — totals recalculate on save</p>
                </div>
                <button type="button" id="saAddItemBtn" class="sa-btn sa-btn-primary"><i class="fa-solid fa-plus"></i> Add Item</button>
            </div>

            <div class="sa-table-wrap">
                <table class="sa-table" id="saItemsTable">
                    <thead>
                        <tr>
                            <th style="width:40%">Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Line Total</th>
                            <th style="width:60px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $i => $item)
                            <tr class="sa-item-row">
                                <td>
                                    <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item->id }}">
                                    <select class="sa-input sa-item-product" name="items[{{ $i }}][product_id]">
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" @selected($item->product_id == $product->id)>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="items[{{ $i }}][product_name]" value="{{ $item->product_name }}">
                                </td>
                                <td><input class="sa-input sa-item-price" type="number" step="0.01" name="items[{{ $i }}][price]" value="{{ $item->price }}"></td>
                                <td><input class="sa-input sa-item-qty" type="number" min="1" name="items[{{ $i }}][quantity]" value="{{ $item->quantity }}"></td>
                                <td class="sa-item-total">₹ {{ number_format($item->price * $item->quantity, 2) }}</td>
                                <td><button type="button" class="sa-btn sa-btn-danger sa-item-remove"><i class="fa-solid fa-xmark"></i></button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="sa-order-totals">
                <div><span>Subtotal</span><strong id="saSubtotal">₹ {{ number_format($order->subtotal, 2) }}</strong></div>
                <div><span>Shipping</span><strong id="saShip">₹ {{ number_format($order->shipping, 2) }}</strong></div>
                <div class="sa-grand"><span>Grand Total</span><strong id="saGrand">₹ {{ number_format($order->grand_total, 2) }}</strong></div>
            </div>
        </div>

        <div class="sa-form-actions">
            <a href="{{ route('vendor.orders') }}" class="sa-btn sa-btn-ghost">Cancel</a>
            <button type="submit" class="sa-btn sa-btn-primary"><i class="fa-solid fa-save"></i> Save All Changes</button>
        </div>
    </form>

    {{-- Hidden product template row --}}
    <template id="saItemTemplate">
        <tr class="sa-item-row">
            <td>
                <input type="hidden" name="items[__i__][id]" value="">
                <select class="sa-input sa-item-product" name="items[__i__][product_id]">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="items[__i__][product_name]" value="">
            </td>
            <td><input class="sa-input sa-item-price" type="number" step="0.01" name="items[__i__][price]" value="0"></td>
            <td><input class="sa-input sa-item-qty" type="number" min="1" name="items[__i__][quantity]" value="1"></td>
            <td class="sa-item-total">₹ 0.00</td>
            <td><button type="button" class="sa-btn sa-btn-danger sa-item-remove"><i class="fa-solid fa-xmark"></i></button></td>
        </tr>
    </template>
@endsection

@push('scripts')
<script>
(function () {
    const table = document.getElementById('saItemsTable');
    const tbody = table.querySelector('tbody');
    const template = document.getElementById('saItemTemplate').innerHTML;
    const shipInput = document.querySelector('input[name="shipping"]');
    const subtotalEl = document.getElementById('saSubtotal');
    const shipEl = document.getElementById('saShip');
    const grandEl = document.getElementById('saGrand');

    function fmt(n) { return '₹ ' + Number(n).toFixed(2); }

    function recalc() {
        let subtotal = 0;
        tbody.querySelectorAll('.sa-item-row').forEach(row => {
            const p = parseFloat(row.querySelector('.sa-item-price').value) || 0;
            const q = parseInt(row.querySelector('.sa-item-qty').value) || 0;
            const line = p * q;
            row.querySelector('.sa-item-total').textContent = fmt(line);
            subtotal += line;
        });
        const ship = parseFloat(shipInput.value) || 0;
        subtotalEl.textContent = fmt(subtotal);
        shipEl.textContent = fmt(ship);
        grandEl.textContent = fmt(subtotal + ship);
    }

    tbody.addEventListener('input', e => {
        if (e.target.matches('.sa-item-price, .sa-item-qty')) recalc();
        if (e.target.matches('.sa-item-product')) {
            const opt = e.target.selectedOptions[0];
            const row = e.target.closest('.sa-item-row');
            row.querySelector('.sa-item-price').value = opt.dataset.price || 0;
            row.querySelector('input[name$="[product_name]"]').value = opt.textContent.trim();
            recalc();
        }
    });

    tbody.addEventListener('click', e => {
        if (e.target.closest('.sa-item-remove')) {
            e.target.closest('.sa-item-row').remove();
            recalc();
        }
    });

    shipInput.addEventListener('input', recalc);

    document.getElementById('saAddItemBtn').addEventListener('click', () => {
        const idx = tbody.querySelectorAll('.sa-item-row').length + Math.floor(Math.random() * 9999);
        tbody.insertAdjacentHTML('beforeend', template.replaceAll('__i__', idx));
        const row = tbody.lastElementChild;
        const sel = row.querySelector('.sa-item-product');
        const opt = sel.selectedOptions[0];
        row.querySelector('.sa-item-price').value = opt.dataset.price || 0;
        row.querySelector('input[name$="[product_name]"]').value = opt.textContent.trim();
        recalc();
    });
})();
</script>
@endpush
