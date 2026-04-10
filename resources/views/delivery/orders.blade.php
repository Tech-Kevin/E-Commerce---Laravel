@extends('layouts.delivery')

@section('title', __('delivery.nav_assigned_orders'))
@section('page_title', __('delivery.nav_assigned_orders'))
@section('page_subtitle', __('delivery.manage_assignments'))

@section('content')
    <div class="dashboard-card">
        <div class="card-header">
            <div>
                <h3>{{ __('delivery.active_orders') }}</h3>
                <p class="card-subtext">{{ __('delivery.update_status_hint') }}</p>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>{{ __('delivery.order_id') }}</th>
                        <th>{{ __('delivery.customer') }}</th>
                        <th>{{ __('delivery.phone') }}</th>
                        <th>{{ __('delivery.address') }}</th>
                        <th>{{ __('delivery.items') }}</th>
                        <th>{{ __('delivery.amount') }}</th>
                        <th>{{ __('delivery.status') }}</th>
                        <th>{{ __('delivery.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->order_number }}</td>
                        <td>{{ $order->full_name }}</td>
                        <td>{{ $order->phone }}</td>
                        <td>{{ Str::limit($order->address . ', ' . $order->city . ' - ' . $order->pincode, 40) }}</td>
                        <td>{{ __('delivery.item_count', ['count' => $order->items->count()]) }}</td>
                        <td>₹ {{ number_format($order->grand_total, 2) }}</td>
                        <td>
                            <span class="status-badge status-{{ $order->status }}" id="status-badge-{{ $order->id }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                @if(in_array($order->status, ['pending','processing','shipped','arriving']))
                                    <button class="btn btn-pickup" data-id="{{ $order->id }}"
                                            data-url="{{ route('delivery.update.status', $order->id) }}"
                                            onclick="updateDeliveryStatus(this, 'picked_up')">
                                        <i class="fa-solid fa-truck-pickup"></i> {{ __('delivery.pick_up') }}
                                    </button>
                                @endif

                                @if($order->status === 'picked_up')
                                    <button class="btn btn-onway" data-id="{{ $order->id }}"
                                            data-url="{{ route('delivery.update.status', $order->id) }}"
                                            onclick="updateDeliveryStatus(this, 'on_the_way')">
                                        <i class="fa-solid fa-truck-fast"></i> {{ __('delivery.on_the_way') }}
                                    </button>
                                @endif

                                @if($order->status === 'on_the_way')
                                    <button class="btn btn-completed" data-id="{{ $order->id }}"
                                            data-url="{{ route('delivery.update.status', $order->id) }}"
                                            onclick="updateDeliveryStatus(this, 'completed')">
                                        <i class="fa-solid fa-flag-checkered"></i> {{ __('delivery.completed') }}
                                    </button>
                                @endif

                                @if($order->status === 'completed')
                                    <a href="{{ route('delivery.verify', $order->id) }}" class="btn btn-deliver">
                                        <i class="fa-solid fa-clipboard-check"></i> {{ __('delivery.verify_pay') }}
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding: 32px; color: #8a7769;">{{ __('delivery.no_assigned_orders') }}</td>
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
                    <span>{{ __('delivery.phone') }}</span>
                    <strong>{{ $order->phone }}</strong>
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
                    <span>{{ __('delivery.status') }}</span>
                    <span class="status-badge status-{{ $order->status }}">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
                <div class="mobile-card-actions">
                    @if(in_array($order->status, ['pending','processing','shipped','arriving']))
                        <button class="btn btn-pickup" data-id="{{ $order->id }}"
                                data-url="{{ route('delivery.update.status', $order->id) }}"
                                onclick="updateDeliveryStatus(this, 'picked_up')">
                            <i class="fa-solid fa-truck-pickup"></i> {{ __('delivery.pick_up') }}
                        </button>
                    @endif
                    @if($order->status === 'picked_up')
                        <button class="btn btn-onway" data-id="{{ $order->id }}"
                                data-url="{{ route('delivery.update.status', $order->id) }}"
                                onclick="updateDeliveryStatus(this, 'on_the_way')">
                            <i class="fa-solid fa-truck-fast"></i> {{ __('delivery.on_the_way') }}
                        </button>
                    @endif
                    @if($order->status === 'on_the_way')
                        <button class="btn btn-completed" data-id="{{ $order->id }}"
                                data-url="{{ route('delivery.update.status', $order->id) }}"
                                onclick="updateDeliveryStatus(this, 'completed')">
                            <i class="fa-solid fa-flag-checkered"></i> {{ __('delivery.completed') }}
                        </button>
                    @endif
                    @if($order->status === 'completed')
                        <a href="{{ route('delivery.verify', $order->id) }}" class="btn btn-deliver">
                            <i class="fa-solid fa-clipboard-check"></i> {{ __('delivery.verify_pay') }}
                        </a>
                    @endif
                </div>
            </div>
            @empty
            <p style="text-align:center; padding: 32px; color: #8a7769;">{{ __('delivery.no_assigned_orders') }}</p>
            @endforelse
        </div>
    </div>

@push('scripts')
<script>
function updateDeliveryStatus(btn, status) {
    const url = btn.dataset.url;
    const orderId = btn.dataset.id;

    fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ status }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || @json(__('delivery.failed_update_status')));
        }
    })
    .catch(() => alert(@json(__('delivery.failed_update_status'))));
}
</script>
@endpush
@endsection
