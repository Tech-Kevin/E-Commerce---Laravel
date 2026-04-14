@extends('layouts.vendor')

@section('title', 'Super Admin Dashboard')
@section('page_title', 'God Mode Dashboard')
@section('page_subtitle', 'Full control over every part of the platform')

@section('content')
    <section class="sa-hero">
        <div class="sa-hero-text">
            <span class="sa-hero-badge"><i class="fa-solid fa-crown"></i> Super Admin</span>
            <h2><i class="fa-solid fa-bolt-lightning"></i> Welcome back, {{ Auth::user()->name }}</h2>
            <p>You have full control: products, categories, users, orders, site content, branding and system tools. Everything flows through this panel.</p>
        </div>
        <div class="sa-hero-stats">
            <div class="sa-hero-stat">
                <strong>₹{{ number_format($totalRevenue, 0) }}</strong>
                <span>Revenue</span>
            </div>
            <div class="sa-hero-stat">
                <strong>{{ $totalOrders }}</strong>
                <span>Orders</span>
            </div>
            <div class="sa-hero-stat">
                <strong>{{ $totalUsers }}</strong>
                <span>Users</span>
            </div>
        </div>
    </section>

    <section class="stats-grid">
        <div class="stats-card">
            <div class="stats-card-icon sales">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
            <div class="stats-card-info">
                <h3>Total Revenue</h3>
                <h2>₹ {{ number_format($totalRevenue, 2) }}</h2>
                <p>All completed orders</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon orders">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div class="stats-card-info">
                <h3>Total Orders</h3>
                <h2>{{ $totalOrders }}</h2>
                <p>All time orders</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon products">
                <i class="fa-solid fa-box"></i>
            </div>
            <div class="stats-card-info">
                <h3>Total Products</h3>
                <h2>{{ $totalProducts }}</h2>
                <p>Listed in store</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon customers">
                <i class="fa-solid fa-user-group"></i>
            </div>
            <div class="stats-card-info">
                <h3>Total Customers</h3>
                <h2>{{ $totalCustomers }}</h2>
                <p>Registered customers</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon products">
                <i class="fa-solid fa-motorcycle"></i>
            </div>
            <div class="stats-card-info">
                <h3>Delivery Boys</h3>
                <h2>{{ $totalDeliveryBoys }}</h2>
                <p>Active delivery members</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon orders">
                <i class="fa-solid fa-users-gear"></i>
            </div>
            <div class="stats-card-info">
                <h3>Total Users</h3>
                <h2>{{ $totalUsers }}</h2>
                <p>{{ $totalVendors }} vendors in system</p>
            </div>
        </div>
    </section>

    <section class="dashboard-card">
        <div class="card-header">
            <div>
                <h3>Super Admin Quick Actions</h3>
                <p class="card-subtext">Everything you need to run the platform — one click away</p>
            </div>
        </div>
        <div class="super-admin-grid">
            <a href="{{ route('vendor.users.index') }}" class="super-admin-card">
                <i class="fa-solid fa-user-shield"></i>
                <h4>User Control Center</h4>
                <p>Create, impersonate, reset passwords, change roles, block</p>
            </a>
            <a href="{{ route('vendor.categories') }}" class="super-admin-card">
                <i class="fa-solid fa-layer-group"></i>
                <h4>Categories & Subcategories</h4>
                <p>Full taxonomy control for the entire catalog</p>
            </a>
            <a href="{{ route('vendor.product.show') }}" class="super-admin-card">
                <i class="fa-solid fa-box-open"></i>
                <h4>Products & Bulk Edit</h4>
                <p>Bulk price updates, stock changes and deletes</p>
            </a>
            <a href="{{ route('vendor.orders') }}" class="super-admin-card">
                <i class="fa-solid fa-bag-shopping"></i>
                <h4>Order God Mode</h4>
                <p>Edit items, totals, refund and delete any order</p>
            </a>
            <a href="{{ route('vendor.site.content') }}" class="super-admin-card">
                <i class="fa-solid fa-palette"></i>
                <h4>Site Content</h4>
                <p>Logo, favicon, hero banner, footer, contacts</p>
            </a>
            <a href="{{ route('vendor.settings') }}" class="super-admin-card">
                <i class="fa-solid fa-brush"></i>
                <h4>Theme + Branding</h4>
                <p>Customize colors, name, and overall look</p>
            </a>
            <a href="{{ route('vendor.delivery-boys') }}" class="super-admin-card">
                <i class="fa-solid fa-truck-fast"></i>
                <h4>Delivery Control</h4>
                <p>Manage delivery team activity and access</p>
            </a>
            <a href="{{ route('vendor.system') }}" class="super-admin-card">
                <i class="fa-solid fa-server"></i>
                <h4>System Tools</h4>
                <p>Clear cache, run migrations, view logs</p>
            </a>
        </div>
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-card large-card">
            <div class="card-header">
                <h3>Recent Orders</h3>
                <a href="{{ route('vendor.orders') }}" class="card-link">View All</a>
            </div>

            <div class="table-wrapper">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        <tr>
                            <td>#{{ $order->order_number }}</td>
                            <td>{{ $order->user->name ?? $order->full_name }}</td>
                            <td>{{ $order->items->count() }} item(s)</td>
                            <td>
                                <select class="order-status-select status-{{ $order->status }}"
                                        data-id="{{ $order->id }}"
                                        data-url="{{ route('vendor.order.status', $order->id) }}">
                                    @foreach(['pending','processing','shipped','arriving','delivered','cancelled'] as $s)
                                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                            {{ ucfirst($s) }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>₹ {{ number_format($order->grand_total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 24px; color: #8a7769;">No orders yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dashboard-card side-card">
            <div class="card-header">
                <h3>Top Products</h3>
            </div>

            <div class="product-list">
                @forelse($topProducts as $product)
                <div class="product-item">
                    <div class="product-thumb">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
                    <div class="product-details">
                        <h4>{{ $product->name }}</h4>
                        <p>{{ $product->orders_count }} order(s)</p>
                    </div>
                </div>
                @empty
                <p style="color: #8a7769; padding: 16px 0;">No products yet.</p>
                @endforelse
            </div>
        </div>
    </section>

@push('scripts')
    <script src="{{ asset('js/vendor/order-status.js') }}"></script>
@endpush
@endsection
