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

            @if(session('error'))
                <div class="alert alert-error" style="margin-bottom:18px;">
                    <i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i> {{ session('error') }}
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
                            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                                @if($order->payment_method)
                                    <span style="font-size:12px;font-weight:600;color:var(--text-faint);display:flex;align-items:center;gap:4px;">
                                        <i class="fa-solid fa-credit-card"></i>
                                        {{ ucfirst($order->payment_method) }}
                                    </span>
                                @endif
                                @if($order->canBeCancelled())
                                    <button type="button" class="cancel-order-btn" data-order-id="{{ $order->id }}" data-order-number="{{ $order->order_number }}">
                                        <i class="fa-solid fa-circle-xmark"></i> Cancel Order
                                    </button>
                                @endif
                            </div>
                        </div>

                        @if($order->status === 'cancelled' && $order->cancellation_reason)
                            <div class="order-cancel-note">
                                <i class="fa-solid fa-circle-info"></i>
                                <div>
                                    <strong>Cancelled{{ $order->cancelled_by ? ' by ' . $order->cancelled_by : '' }}</strong>
                                    @if($order->cancelled_at)
                                        <span style="color:var(--text-faint);font-weight:500;"> · {{ $order->cancelled_at->format('d M Y, h:i A') }}</span>
                                    @endif
                                    <p>{{ $order->cancellation_reason }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Cancel Order Modal --}}
    <div id="cancelOrderModal" class="cancel-modal" aria-hidden="true">
        <div class="cancel-modal-backdrop" data-cancel-close></div>
        <div class="cancel-modal-card" role="dialog" aria-modal="true" aria-labelledby="cancelModalTitle">
            <div class="cancel-modal-head">
                <h3 id="cancelModalTitle"><i class="fa-solid fa-circle-xmark" style="color:var(--danger);"></i> Cancel Order</h3>
                <button type="button" class="cancel-modal-close" data-cancel-close aria-label="Close">&times;</button>
            </div>
            <form id="cancelOrderForm" method="POST">
                @csrf
                <div class="cancel-modal-body">
                    <p style="color:var(--text-muted);font-size:13.5px;margin:0 0 14px;">
                        Cancelling <strong id="cancelOrderNumber"></strong>. This cannot be undone. Reserved stock will be released.
                    </p>
                    <label for="cancellation_reason" class="form-label">Reason for cancellation <span style="color:var(--danger);">*</span></label>
                    <textarea name="cancellation_reason" id="cancellation_reason" class="form-control" rows="4" minlength="5" maxlength="500" required placeholder="Tell us why you're cancelling (minimum 5 characters)"></textarea>
                </div>
                <div class="cancel-modal-foot">
                    <button type="button" class="secondary-btn" data-cancel-close>Keep Order</button>
                    <button type="submit" class="danger-btn"><i class="fa-solid fa-circle-xmark"></i> Cancel Order</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    (function () {
        const modal = document.getElementById('cancelOrderModal');
        const form = document.getElementById('cancelOrderForm');
        const numberEl = document.getElementById('cancelOrderNumber');
        const reasonInput = document.getElementById('cancellation_reason');
        const cancelBase = "{{ url('customer/orders') }}";

        function openModal(orderId, orderNumber) {
            form.action = `${cancelBase}/${orderId}/cancel`;
            numberEl.textContent = '#' + orderNumber;
            reasonInput.value = '';
            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
            setTimeout(() => reasonInput.focus(), 50);
        }

        function closeModal() {
            modal.classList.remove('open');
            modal.setAttribute('aria-hidden', 'true');
        }

        document.querySelectorAll('.cancel-order-btn').forEach(btn => {
            btn.addEventListener('click', () => openModal(btn.dataset.orderId, btn.dataset.orderNumber));
        });

        modal.querySelectorAll('[data-cancel-close]').forEach(el => el.addEventListener('click', closeModal));
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
    })();
    </script>
    @endpush
@endsection
