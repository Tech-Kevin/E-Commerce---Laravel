@extends('layouts.delivery')

@section('title', __('delivery.nav_completed_orders'))
@section('page_title', __('delivery.nav_completed_orders'))
@section('page_subtitle', __('delivery.view_completed_subtitle'))

@section('content')
    <div class="dashboard-card">
        <div class="card-header">
            <div>
                <h3>{{ __('delivery.delivered_orders') }}</h3>
                <p class="card-subtext">{{ __('delivery.completed_history') }}</p>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>{{ __('delivery.order_id') }}</th>
                        <th>{{ __('delivery.customer') }}</th>
                        <th>{{ __('delivery.address') }}</th>
                        <th>{{ __('delivery.items') }}</th>
                        <th>{{ __('delivery.amount') }}</th>
                        <th>{{ __('delivery.delivered_on') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->order_number }}</td>
                        <td>{{ $order->full_name }}</td>
                        <td>{{ Str::limit($order->address . ', ' . $order->city, 40) }}</td>
                        <td>{{ __('delivery.item_count', ['count' => $order->items->count()]) }}</td>
                        <td>₹ {{ number_format($order->grand_total, 2) }}</td>
                        <td>{{ $order->updated_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 32px; color: #8a7769;">{{ __('delivery.no_completed_yet') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile card view --}}
        <div class="mobile-card">
            @forelse($orders as $order)
            <div class="mobile-card-item">
                <div class="mobile-card-row">
                    <span>{{ __('delivery.order') }}</span>
                    <strong>#{{ $order->order_number }}</strong>
                </div>
                <div class="mobile-card-row">
                    <span>{{ __('delivery.customer') }}</span>
                    <strong>{{ $order->full_name }}</strong>
                </div>
                <div class="mobile-card-row">
                    <span>{{ __('delivery.address') }}</span>
                    <strong>{{ Str::limit($order->address . ', ' . $order->city, 35) }}</strong>
                </div>
                <div class="mobile-card-row">
                    <span>{{ __('delivery.items') }}</span>
                    <strong>{{ __('delivery.item_count', ['count' => $order->items->count()]) }}</strong>
                </div>
                <div class="mobile-card-row">
                    <span>{{ __('delivery.amount') }}</span>
                    <strong>₹ {{ number_format($order->grand_total, 2) }}</strong>
                </div>
                <div class="mobile-card-row">
                    <span>{{ __('delivery.delivered') }}</span>
                    <strong>{{ $order->updated_at->format('d M Y, h:i A') }}</strong>
                </div>
            </div>
            @empty
            <p style="text-align:center; padding: 32px; color: #8a7769;">{{ __('delivery.no_completed_yet') }}</p>
            @endforelse
        </div>
    </div>
@endsection
