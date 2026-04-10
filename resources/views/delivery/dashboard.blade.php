@extends('layouts.delivery')

@section('title', __('delivery.dashboard'))
@section('page_title', __('delivery.dashboard'))
@section('page_subtitle', __('delivery.track_deliveries'))

@section('content')
    <section class="stats-grid">
        <div class="stats-card">
            <div class="stats-card-icon assigned">
                <i class="fa-solid fa-box"></i>
            </div>
            <div class="stats-card-info">
                <h3>{{ __('delivery.assigned_orders') }}</h3>
                <h2>{{ $totalAssigned }}</h2>
                <p>{{ __('delivery.pending_delivery') }}</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon picked">
                <i class="fa-solid fa-hand-holding-box fa-solid fa-truck-pickup"></i>
            </div>
            <div class="stats-card-info">
                <h3>{{ __('delivery.picked_up') }}</h3>
                <h2>{{ $totalPickedUp }}</h2>
                <p>{{ __('delivery.collected_from_vendor') }}</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon onway">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
            <div class="stats-card-info">
                <h3>{{ __('delivery.on_the_way') }}</h3>
                <h2>{{ $totalOnTheWay }}</h2>
                <p>{{ __('delivery.in_transit') }}</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon completed">
                <i class="fa-solid fa-flag-checkered"></i>
            </div>
            <div class="stats-card-info">
                <h3>{{ __('delivery.completed') }}</h3>
                <h2>{{ $totalCompleted }}</h2>
                <p>{{ __('delivery.awaiting_verification') }}</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon delivered">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="stats-card-info">
                <h3>{{ __('delivery.delivered_paid') }}</h3>
                <h2>{{ $totalDelivered }}</h2>
                <p>{{ __('delivery.successfully_completed') }}</p>
            </div>
        </div>
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-card large-card">
            <div class="card-header">
                <h3>{{ __('delivery.recent_orders') }}</h3>
                <a href="{{ route('delivery.orders') }}" class="card-link">{{ __('delivery.view_all') }}</a>
            </div>

            <div class="table-wrapper">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>{{ __('delivery.order_id') }}</th>
                            <th>{{ __('delivery.customer') }}</th>
                            <th>{{ __('delivery.address') }}</th>
                            <th>{{ __('delivery.status') }}</th>
                            <th>{{ __('delivery.amount') }}</th>
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
                            <td colspan="5" style="text-align:center; padding: 24px; color: #8a7769;">{{ __('delivery.no_orders_yet') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile card view --}}
            <div class="mobile-card">
                @forelse($recentOrders as $order)
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
                        <strong>{{ Str::limit($order->address, 30) }}</strong>
                    </div>
                    <div class="mobile-card-row">
                        <span>{{ __('delivery.status') }}</span>
                        <span class="status-badge status-{{ $order->status }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                    </div>
                    <div class="mobile-card-row">
                        <span>{{ __('delivery.amount') }}</span>
                        <strong>₹ {{ number_format($order->grand_total, 2) }}</strong>
                    </div>
                </div>
                @empty
                <p style="text-align:center; padding: 24px; color: #8a7769;">{{ __('delivery.no_orders_yet') }}</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
