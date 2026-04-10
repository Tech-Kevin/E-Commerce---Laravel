@extends('layouts.store')

@section('title', __('store.my_orders'))

@section('content')
    <section class="page-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2>{{ __('store.my_orders') }}</h2>
                    <p>{{ __('store.track_orders') }}</p>
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
                    <h3>{{ __('store.no_orders') }}</h3>
                    <a href="{{ route('home') }}" class="primary-btn">{{ __('store.start_shopping') }}</a>
                </div>
            @else
            <div class="table-card">
                <table class="store-table">
                    <thead>
                        <tr>
                            <th>{{ __('store.order_id') }}</th>
                            <th>{{ __('store.date') }}</th>
                            <th>{{ __('store.items') }}</th>
                            <th>{{ __('store.status') }}</th>
                            <th>{{ __('store.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->order_number }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>{{ __('store.item_count', ['count' => $order->items->count()]) }}</td>
                            <td>
                                <span class="stock-badge {{ $order->status === 'delivered' ? 'in-stock' : ($order->status === 'cancelled' ? 'out-stock' : 'low-stock') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>₹ {{ number_format($order->grand_total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </section>
@endsection
