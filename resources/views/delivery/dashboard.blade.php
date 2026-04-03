@extends('layouts.delivery')

@section('title', 'Delivery Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Track your deliveries and performance')

@section('content')
    <section class="stats-grid">
        <div class="stats-card">
            <div class="stats-card-icon assigned">
                <i class="fa-solid fa-box"></i>
            </div>
            <div class="stats-card-info">
                <h3>Assigned Orders</h3>
                <h2>{{ $totalAssigned }}</h2>
                <p>Pending delivery</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon picked">
                <i class="fa-solid fa-hand-holding-box fa-solid fa-truck-pickup"></i>
            </div>
            <div class="stats-card-info">
                <h3>Picked Up</h3>
                <h2>{{ $totalPickedUp }}</h2>
                <p>Collected from vendor</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon onway">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
            <div class="stats-card-info">
                <h3>On The Way</h3>
                <h2>{{ $totalOnTheWay }}</h2>
                <p>In transit to customer</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon completed">
                <i class="fa-solid fa-flag-checkered"></i>
            </div>
            <div class="stats-card-info">
                <h3>Completed</h3>
                <h2>{{ $totalCompleted }}</h2>
                <p>Awaiting verification</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon delivered">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="stats-card-info">
                <h3>Delivered & Paid</h3>
                <h2>{{ $totalDelivered }}</h2>
                <p>Successfully completed</p>
            </div>
        </div>
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-card large-card">
            <div class="card-header">
                <h3>Recent Orders</h3>
                <a href="{{ route('delivery.orders') }}" class="card-link">View All</a>
            </div>

            <div class="table-wrapper">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td>#{{ $order->order_number }}</td>
                            <td>{{ $order->full_name }}</td>
                            <td>{{ Str::limit($order->address, 30) }}</td>
                            <td><span class="status-badge status-{{ $order->status }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span></td>
                            <td>₹ {{ number_format($order->grand_total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 24px; color: #8a7769;">No orders assigned yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
