@extends('layouts.store')

@section('title', __('store.my_orders'))

@section('content')
    <section class="page-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2><i class="fa-solid fa-bag-shopping" style="color:var(--accent);margin-right:8px;"></i>{{ __('store.my_orders') }}</h2>
                    <p>{{ __('store.track_orders') }}</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:18px;">
                    <i class="fa-solid fa-circle-check" style="margin-right:6px;"></i> {{ session('success') }}
                </div>
            @endif

            @if($orders->isEmpty())
                <div class="empty-state">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <h3>{{ __('store.no_orders') }}</h3>
                    <p>You haven't placed any orders yet. Start shopping to see your orders here.</p>
                    <a href="{{ route('home') }}" class="primary-btn"><i class="fa-solid fa-bag-shopping"></i> {{ __('store.start_shopping') }}</a>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:16px;">
                    @foreach($orders as $order)
                    <div class="order-card">
                        <div class="order-card-header">
                            <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                                <h3>
                                    <i class="fa-solid fa-hashtag" style="color:var(--accent);font-size:13px;"></i>
                                    {{ $order->order_number }}
                                </h3>
                                <span class="order-date">
                                    <i class="fa-regular fa-calendar" style="margin-right:4px;"></i>
                                    {{ $order->created_at->format('d M Y, h:i A') }}
                                </span>
                            </div>
                            <span class="order-status {{ $order->status }}">
                                @switch($order->status)
                                    @case('pending') <i class="fa-solid fa-clock"></i> @break
                                    @case('processing') <i class="fa-solid fa-gear"></i> @break
                                    @case('shipped') <i class="fa-solid fa-truck"></i> @break
                                    @case('arriving') <i class="fa-solid fa-truck-fast"></i> @break
                                    @case('delivered') <i class="fa-solid fa-circle-check"></i> @break
                                    @case('cancelled') <i class="fa-solid fa-circle-xmark"></i> @break
                                @endswitch
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        {{-- Order Items Preview --}}
                        <div class="order-items-preview">
                            @foreach($order->items->take(4) as $item)
                                <div class="order-item-thumb" title="{{ $item->product?->name ?? $item->product_name ?? 'Product' }}">
                                    @if($item->product && $item->product->getFirstMediaUrl('product_image'))
                                        <img src="{{ $item->product->getFirstMediaUrl('product_image') }}" alt="">
                                    @else
                                        <i class="fa-solid fa-box" style="color:var(--text-faint);font-size:18px;"></i>
                                    @endif
                                </div>
                            @endforeach
                            @if($order->items->count() > 4)
                                <div class="order-item-thumb" style="font-size:12px;font-weight:700;color:var(--text-muted);">
                                    +{{ $order->items->count() - 4 }}
                                </div>
                            @endif
                            <span style="margin-left:8px;font-size:13px;color:var(--text-muted);align-self:center;">
                                {{ __('store.item_count', ['count' => $order->items->count()]) }}
                            </span>
                        </div>

                        <div class="order-card-footer">
                            <span class="order-total">₹ {{ number_format($order->grand_total, 2) }}</span>
                            @if($order->payment_method)
                                <span style="font-size:12px;font-weight:600;color:var(--text-faint);display:flex;align-items:center;gap:4px;">
                                    <i class="fa-solid fa-credit-card"></i>
                                    {{ ucfirst($order->payment_method) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
