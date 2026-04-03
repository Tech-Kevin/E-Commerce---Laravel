@extends('layouts.delivery')

@section('title', 'Assigned Orders')
@section('page_title', 'Assigned Orders')
@section('page_subtitle', 'Manage your current delivery assignments')

@section('content')
    <div class="dashboard-card">
        <div class="card-header">
            <div>
                <h3>Active Orders</h3>
                <p class="card-subtext">Update status as you pick up and deliver orders</p>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Items</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->order_number }}</td>
                        <td>{{ $order->full_name }}</td>
                        <td>{{ $order->phone }}</td>
                        <td>{{ Str::limit($order->address . ', ' . $order->city . ' - ' . $order->pincode, 40) }}</td>
                        <td>{{ $order->items->count() }} item(s)</td>
                        <td>₹ {{ number_format($order->grand_total, 2) }}</td>
                        <td>
                            <span class="status-badge status-{{ $order->status }}" id="status-badge-{{ $order->id }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                @if(in_array($order->status, ['pending','processing','shipped','arriving']))
                                    <button class="btn btn-pickup" data-id="{{ $order->id }}"
                                            data-url="{{ route('delivery.update.status', $order->id) }}"
                                            onclick="updateDeliveryStatus(this, 'picked_up')">
                                        <i class="fa-solid fa-truck-pickup"></i> Pick Up
                                    </button>
                                @endif

                                @if($order->status === 'picked_up')
                                    <button class="btn btn-onway" data-id="{{ $order->id }}"
                                            data-url="{{ route('delivery.update.status', $order->id) }}"
                                            onclick="updateDeliveryStatus(this, 'on_the_way')">
                                        <i class="fa-solid fa-truck-fast"></i> On The Way
                                    </button>
                                @endif

                                @if($order->status === 'on_the_way')
                                    <button class="btn btn-completed" data-id="{{ $order->id }}"
                                            data-url="{{ route('delivery.update.status', $order->id) }}"
                                            onclick="updateDeliveryStatus(this, 'completed')">
                                        <i class="fa-solid fa-flag-checkered"></i> Completed
                                    </button>
                                @endif

                                @if($order->status === 'completed')
                                    <a href="{{ route('delivery.verify', $order->id) }}" class="btn btn-deliver">
                                        <i class="fa-solid fa-clipboard-check"></i> Verify & Pay
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding: 32px; color: #8a7769;">No assigned orders.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@push('scripts')
<script>
function updateDeliveryStatus(btn, status) {
    const url = btn.dataset.url;
    const orderId = btn.dataset.id;

    fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ status }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Failed to update status.');
        }
    })
    .catch(() => alert('Failed to update status.'));
}
</script>
@endpush
@endsection
