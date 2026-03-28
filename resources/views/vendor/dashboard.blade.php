@extends('layouts.vendor')

@section('title', 'Vendor Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Track store activity, orders and performance')

@section('content')
    <section class="stats-grid">
        <div class="stats-card">
            <div class="stats-card-icon sales">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
            <div class="stats-card-info">
                <h3>Total Revenue</h3>
                <h2>₹ {{ number_format($totalRevenue, 2) }}</h2>
                <p>All completed orders</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon orders">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div class="stats-card-info">
                <h3>Total Orders</h3>
                <h2>{{ $totalOrders }}</h2>
                <p>All time orders</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon products">
                <i class="fa-solid fa-box"></i>
            </div>
            <div class="stats-card-info">
                <h3>Total Products</h3>
                <h2>{{ $totalProducts }}</h2>
                <p>Listed in store</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon customers">
                <i class="fa-solid fa-user-group"></i>
            </div>
            <div class="stats-card-info">
                <h3>Total Customers</h3>
                <h2>{{ $totalCustomers }}</h2>
                <p>Registered customers</p>
            </div>
        </div>
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-card large-card">
            <div class="card-header">
                <h3>Recent Orders</h3>
                <a href="{{ route('vendor.orders') }}" class="card-link">View All</a>
            </div>

            <div class="table-wrapper">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td>#{{ $order->order_number }}</td>
                            <td>{{ $order->user->name ?? $order->full_name }}</td>
                            <td>{{ $order->items->count() }} item(s)</td>
                            <td>
                                <select class="order-status-select status-{{ $order->status }}"
                                        data-id="{{ $order->id }}"
                                        data-url="{{ route('vendor.order.status', $order->id) }}">
                                    @foreach(['pending','processing','shipped','arriving','delivered','cancelled'] as $s)
                                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                            {{ ucfirst($s) }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>₹ {{ number_format($order->grand_total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 24px; color: #8a7769;">No orders yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dashboard-card side-card">
            <div class="card-header">
                <h3>Top Products</h3>
            </div>

            <div class="product-list">
                @forelse($topProducts as $product)
                <div class="product-item">
                    <div class="product-thumb">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
                    <div class="product-details">
                        <h4>{{ $product->name }}</h4>
                        <p>{{ $product->orders_count }} order(s)</p>
                    </div>
                </div>
                @empty
                <p style="color: #8a7769; padding: 16px 0;">No products yet.</p>
                @endforelse
            </div>
        </div>
    </section>

@push('scripts')
    <script src="{{ asset('js/vendor/order-status.js') }}"></script>
@endpush
@endsection
