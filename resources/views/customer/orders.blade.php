@extends('layouts.store')

@section('title', 'My Orders')

@section('content')
    <section class="page-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2>My Orders</h2>
                    <p>Track all your orders here</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
                    {{ session('success') }}
                </div>
            @endif

            @if($orders->isEmpty())
                <div class="empty-state">
                    <i class="fa-solid fa-box-open"></i>
                    <h3>No orders yet</h3>
                    <a href="{{ route('home') }}" class="primary-btn">Start Shopping</a>
                </div>
            @else
            <div class="table-card">
                <table class="store-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->order_number }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>{{ $order->items->count() }} item(s)</td>
                            <td>
                                <span class="stock-badge {{ $order->status === 'delivered' ? 'in-stock' : ($order->status === 'cancelled' ? 'out-stock' : 'low-stock') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>₹{{ number_format($order->grand_total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </section>
@endsection
