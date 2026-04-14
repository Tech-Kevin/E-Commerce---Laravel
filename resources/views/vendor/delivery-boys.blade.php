@extends('layouts.vendor')

@section('title', 'Delivery Boys')
@section('page_title', 'Delivery Boys')
@section('page_subtitle', 'View and manage your delivery team')

@section('content')
    <div class="dashboard-card">
        <div class="card-header">
            <div>
                <h3>Delivery Team</h3>
                <p class="card-subtext">Assigned riders who handle your order deliveries</p>
            </div>
        </div>

        @if($deliveryBoys->isEmpty())
            <p style="padding: 24px; color: #8a7769; text-align: center;">No delivery boys found.</p>
        @else
            <div class="table-wrapper">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Delivery Boy</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Active Orders</th>
                            <th>Delivered Orders</th>
                            <th>Joined</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deliveryBoys as $index => $deliveryBoy)
                            <tr class="{{ $deliveryBoy->is_active ? '' : 'user-row-inactive' }}">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div class="customer-avatar" style="width: 36px; height: 36px; font-size: 14px;">
                                            {{ strtoupper(substr($deliveryBoy->name, 0, 1)) }}
                                        </div>
                                        <span style="font-weight: 600;">{{ $deliveryBoy->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $deliveryBoy->email }}</td>
                                <td>{{ $deliveryBoy->number ?? 'N/A' }}</td>
                                <td>{{ $deliveryBoy->address ? Str::limit($deliveryBoy->address, 30) : 'N/A' }}</td>
                                <td>
                                    <span class="badge-status" style="background: #fff3e6; color: #e67e22;">
                                        {{ $deliveryBoy->active_orders_count }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-status" style="background: #edf9f0; color: #2d9b57;">
                                        {{ $deliveryBoy->delivered_orders_count }}
                                    </span>
                                </td>
                                <td>{{ $deliveryBoy->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="user-status-wrap">
                                        <label class="status-switch">
                                            <input
                                                type="checkbox"
                                                class="js-user-status-toggle"
                                                data-url="{{ route('vendor.users.status', $deliveryBoy->id) }}"
                                                {{ $deliveryBoy->is_active ? 'checked' : '' }}
                                            >
                                            <span class="status-slider"></span>
                                        </label>
                                        <span class="user-status-pill js-user-status-label {{ $deliveryBoy->is_active ? 'active' : 'blocked' }}">
                                            {{ $deliveryBoy->is_active ? 'Active' : 'Blocked' }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/vendor/user-status.js') }}"></script>
@endpush
