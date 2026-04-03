@extends('layouts.delivery')

@section('title', 'Completed Orders')
@section('page_title', 'Completed Orders')
@section('page_subtitle', 'View all your successfully delivered orders')

@section('content')
    <div class="dashboard-card">
        <div class="card-header">
            <div>
                <h3>Delivered Orders</h3>
                <p class="card-subtext">History of all completed deliveries</p>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Address</th>
                        <th>Items</th>
                        <th>Amount</th>
                        <th>Delivered On</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->order_number }}</td>
                        <td>{{ $order->full_name }}</td>
                        <td>{{ Str::limit($order->address . ', ' . $order->city, 40) }}</td>
                        <td>{{ $order->items->count() }} item(s)</td>
                        <td>₹ {{ number_format($order->grand_total, 2) }}</td>
                        <td>{{ $order->updated_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 32px; color: #8a7769;">No completed deliveries yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
