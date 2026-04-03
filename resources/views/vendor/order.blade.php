@extends('layouts.vendor')

@section('title', 'Orders')
@section('page_title', 'Orders')
@section('page_subtitle', 'Manage all customer orders from one place')

@section('content')
    <div class="dashboard-card">
        <div class="card-header">
            <div>
                <h3>Recent Orders</h3>
                <p class="card-subtext">Track placed, shipped and delivered orders</p>
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
                        <th>Delivery Boy</th>
                        <th>Date</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->order_number }}</td>
                        <td>{{ $order->user->name ?? $order->full_name }}</td>
                        <td>{{ $order->items->count() }} item(s)</td>
                        <td>
                            <select class="order-status-select status-{{ $order->status }}"
                                    data-id="{{ $order->id }}"
                                    data-url="{{ route('vendor.order.status', $order->id) }}">
                                @foreach(['pending','processing','shipped','arriving','picked_up','on_the_way','completed','delivered','cancelled'] as $s)
                                    <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
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
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>₹ {{ number_format($order->grand_total, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding: 32px; color: #8a7769;">No orders yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@push('scripts')
    <script src="{{ asset('js/vendor/order-status.js') }}"></script>
@endpush
@endsection
