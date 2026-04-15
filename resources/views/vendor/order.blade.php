@extends('layouts.vendor')

@section('title', 'Orders')
@section('page_title', 'Orders')
@section('page_subtitle', 'Full god-mode control over every customer order')

@section('content')
    <div class="dashboard-card">
        <div class="card-header">
            <div>
                <h3>All Orders</h3>
                <p class="card-subtext">Edit, mark paid, refund, assign delivery, or delete</p>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Delivery Boy</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->order_number }}<br><small style="color:#9a8375">{{ $order->created_at->format('d M Y') }}</small></td>
                        <td>{{ $order->user->name ?? $order->full_name }}</td>
                        <td>{{ $order->items->count() }} item(s)</td>
                        <td>
                            <select class="order-status-select status-{{ $order->status }}"
                                    data-id="{{ $order->id }}"
                                    data-url="{{ route('vendor.order.status', $order->id) }}">
                                @foreach(['pending','processing','shipped','arriving','delivered','completed','cancelled'] as $s)
                                    <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <span class="sa-pill {{ $order->payment_status === 'paid' ? 'ok' : ($order->payment_status === 'refunded' ? 'warn' : 'muted') }}">
                                {{ ucfirst($order->payment_status ?? 'pending') }}
                            </span>
                        </td>
                        <td>
                            <select class="order-delivery-select"
                                    data-id="{{ $order->id }}"
                                    data-url="{{ route('vendor.order.assign.delivery', $order->id) }}">
                                <option value="">-- Assign --</option>
                                @foreach($deliveryBoys as $boy)
                                    <option value="{{ $boy->id }}" {{ $order->delivery_boy_id == $boy->id ? 'selected' : '' }}>
                                        {{ $boy->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>₹ {{ number_format($order->grand_total, 2) }}</td>
                        <td>
                            <div class="sa-actions">
                                <a href="{{ route('vendor.orders.edit', $order->id) }}" class="sa-btn sa-btn-ghost" title="Edit order">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                @if($order->payment_status !== 'paid')
                                    <button type="button" class="sa-btn sa-btn-ghost js-sa-mark-paid" title="Mark as paid"
                                            data-url="{{ route('vendor.orders.paid', $order->id) }}">
                                        <i class="fa-solid fa-indian-rupee-sign"></i>
                                    </button>
                                @endif
                                @if($order->payment_status === 'paid')
                                    <button type="button" class="sa-btn sa-btn-ghost js-sa-refund" title="Refund"
                                            data-url="{{ route('vendor.orders.refund', $order->id) }}">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </button>
                                @endif
                                @if($order->canBeCancelled())
                                    <button type="button" class="sa-btn sa-btn-ghost js-sa-cancel" title="Cancel order"
                                            data-url="{{ route('vendor.order.cancel', $order->id) }}"
                                            data-order="{{ $order->order_number }}">
                                        <i class="fa-solid fa-circle-xmark" style="color:#ef4444;"></i>
                                    </button>
                                @endif
                                <form action="{{ route('vendor.orders.destroy', $order->id) }}" method="POST"
                                      onsubmit="return confirm('Permanently delete order #{{ $order->order_number }}?');"
                                      style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="sa-btn sa-btn-danger" title="Delete order">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding: 32px; color: #8a7769;">No orders yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@push('scripts')
    <script src="{{ asset('js/vendor/order-status.js') }}"></script>
    <script>
    (function () {
        const token = document.querySelector('meta[name="csrf-token"]').content;

        async function patch(url) {
            const res = await fetch(url, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            });
            return res.json();
        }

        document.querySelectorAll('.js-sa-mark-paid').forEach(btn => {
            btn.addEventListener('click', async () => {
                if (!confirm('Mark this order as paid?')) return;
                const data = await patch(btn.dataset.url);
                if (data.success) location.reload();
            });
        });

        document.querySelectorAll('.js-sa-refund').forEach(btn => {
            btn.addEventListener('click', async () => {
                if (!confirm('Refund this order? It will also be marked cancelled.')) return;
                const data = await patch(btn.dataset.url);
                if (data.success) location.reload();
            });
        });

        document.querySelectorAll('.js-sa-cancel').forEach(btn => {
            btn.addEventListener('click', async () => {
                const orderNumber = btn.dataset.order;
                const reason = prompt(`Cancel order #${orderNumber}?\n\nEnter a reason (min 5 chars):`);
                if (reason === null) return;
                if (!reason || reason.trim().length < 5) {
                    alert('Please provide a reason of at least 5 characters.');
                    return;
                }

                try {
                    const res = await fetch(btn.dataset.url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ cancellation_reason: reason.trim() }),
                    });
                    const data = await res.json();
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to cancel order.');
                    }
                } catch (err) {
                    alert('Request failed: ' + err.message);
                }
            });
        });
    })();
    </script>
@endpush
@endsection
