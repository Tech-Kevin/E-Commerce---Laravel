@extends('layouts.vendor')

@section('title', 'Analytics')
@section('page_title', 'Analytics')
@section('page_subtitle', 'Track store growth, customer activity and sales performance')

@section('content')
    <section class="stats-grid analytics-stats-grid">
        <div class="stats-card analytics-stat-card">
            <div class="stats-card-icon sales">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div class="stats-card-info">
                <h3>Total Revenue</h3>
                <h2>₹ {{ number_format($totalRevenue, 2) }}</h2>
                <p>{{ $revenueGrowth >= 0 ? '+' : '' }}{{ $revenueGrowth }}% this month</p>
            </div>
        </div>

        <div class="stats-card analytics-stat-card">
            <div class="stats-card-icon orders">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div class="stats-card-info">
                <h3>Total Orders</h3>
                <h2>{{ number_format($totalOrders) }}</h2>
                <p>{{ $ordersGrowth >= 0 ? '+' : '' }}{{ $ordersGrowth }}% this week</p>
            </div>
        </div>

        <div class="stats-card analytics-stat-card">
            <div class="stats-card-icon customers">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stats-card-info">
                <h3>New Customers</h3>
                <h2>{{ number_format($newCustomersThisMonth) }}</h2>
                <p>{{ $customerGrowth >= 0 ? '+' : '' }}{{ $customerGrowth }}% growth</p>
            </div>
        </div>

        <div class="stats-card analytics-stat-card">
            <div class="stats-card-icon products">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
            <div class="stats-card-info">
                <h3>Avg. Order Value</h3>
                <h2>₹ {{ number_format($avgOrderValue, 2) }}</h2>
                <p>Per order average</p>
            </div>
        </div>
    </section>

    <section class="analytics-main-grid">
        <div class="dashboard-card analytics-chart-card large-chart-card">
            <div class="card-header analytics-card-header">
                <div>
                    <h3>Revenue Overview</h3>
                    <p class="card-subtext">Monthly sales performance across the year</p>
                </div>
            </div>

            <div class="chart-box">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="dashboard-card analytics-chart-card side-chart-card">
            <div class="card-header analytics-card-header">
                <div>
                    <h3>Order Status</h3>
                    <p class="card-subtext">Distribution of order statuses</p>
                </div>
            </div>

            <div class="chart-box doughnut-chart-box">
                <canvas id="statusChart"></canvas>
            </div>

            <div class="traffic-legend">
                @php
                    $statusLabels = array_keys($statusCounts);
                    $dotClasses = ['dot-one', 'dot-two', 'dot-three', 'dot-four', 'dot-one'];
                    $totalStatusOrders = array_sum($statusCounts) ?: 1;
                @endphp
                @foreach($statusCounts as $status => $count)
                    <div class="legend-item">
                        <span class="legend-dot {{ $dotClasses[$loop->index % count($dotClasses)] }}"></span>
                        <span>{{ ucfirst($status) }}</span>
                        <strong>{{ round(($count / $totalStatusOrders) * 100) }}%</strong>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="analytics-bottom-grid">
        <div class="dashboard-card analytics-chart-card">
            <div class="card-header analytics-card-header">
                <div>
                    <h3>Orders vs Customers</h3>
                    <p class="card-subtext">Last 7 days acquisition and order trend</p>
                </div>
            </div>

            <div class="chart-box medium-chart-box">
                <canvas id="ordersCustomersChart"></canvas>
            </div>
        </div>

        <div class="dashboard-card analytics-list-card">
            <div class="card-header analytics-card-header">
                <div>
                    <h3>Top Products</h3>
                    <p class="card-subtext">Best performing products this month</p>
                </div>
            </div>

            <div class="analytics-product-list">
                @forelse($topProducts as $product)
                    <div class="analytics-product-item">
                        <div class="analytics-product-rank">{{ $loop->iteration }}</div>
                        <div class="analytics-product-info">
                            <h4>{{ $product->product_name }}</h4>
                            <p>{{ $product->total_sold }} sales &bull; ₹ {{ number_format($product->total_revenue, 2) }} revenue</p>
                        </div>
                    </div>
                @empty
                    <p style="padding: 1rem; color: #8a7769;">No product sales this month yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="analytics-bottom-grid">
        <div class="dashboard-card">
            <div class="card-header analytics-card-header">
                <div>
                    <h3>Recent Activity</h3>
                    <p class="card-subtext">Latest orders and events</p>
                </div>
            </div>

            <div class="activity-timeline">
                @forelse($recentOrders as $order)
                    <div class="activity-item">
                        <div class="activity-dot"></div>
                        <div class="activity-content">
                            <h4>Order #{{ $order->order_number }} — {{ ucfirst($order->status) }}</h4>
                            <p>{{ $order->user->name ?? 'Guest' }} placed an order of ₹ {{ number_format($order->grand_total, 2) }}</p>
                            <span>{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <p style="padding: 1rem; color: #8a7769;">No recent orders.</p>
                @endforelse
            </div>
        </div>

        <div class="dashboard-card analytics-goal-card">
            <div class="card-header analytics-card-header">
                <div>
                    <h3>Monthly Summary</h3>
                    <p class="card-subtext">This month's performance snapshot</p>
                </div>
            </div>

            <div class="goal-progress-list">
                @php
                    $ordersThisMonth = \App\Models\Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
                    $deliveredThisMonth = \App\Models\Order::where('status', 'delivered')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
                    $cancelledThisMonth = \App\Models\Order::where('status', 'cancelled')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
                    $paidThisMonth = \App\Models\Order::where('payment_status', 'paid')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();

                    $deliveredPct = $ordersThisMonth > 0 ? round(($deliveredThisMonth / $ordersThisMonth) * 100) : 0;
                    $cancelledPct = $ordersThisMonth > 0 ? round(($cancelledThisMonth / $ordersThisMonth) * 100) : 0;
                    $paidPct = $ordersThisMonth > 0 ? round(($paidThisMonth / $ordersThisMonth) * 100) : 0;
                @endphp

                <div class="goal-item">
                    <div class="goal-header">
                        <span>Revenue This Month</span>
                        <strong>₹ {{ number_format($revenueThisMonth ?? 0, 2) }}</strong>
                    </div>
                    <div class="goal-track">
                        <div class="goal-fill fill-revenue" style="width: {{ min($totalRevenue > 0 ? round(($revenueThisMonth / $totalRevenue) * 100) : 0, 100) }}%"></div>
                    </div>
                </div>

                <div class="goal-item">
                    <div class="goal-header">
                        <span>Delivered Orders</span>
                        <strong>{{ $deliveredPct }}%</strong>
                    </div>
                    <div class="goal-track">
                        <div class="goal-fill fill-orders" style="width: {{ $deliveredPct }}%"></div>
                    </div>
                </div>

                <div class="goal-item">
                    <div class="goal-header">
                        <span>Payment Collected</span>
                        <strong>{{ $paidPct }}%</strong>
                    </div>
                    <div class="goal-track">
                        <div class="goal-fill fill-customers" style="width: {{ $paidPct }}%"></div>
                    </div>
                </div>

                <div class="goal-item">
                    <div class="goal-header">
                        <span>Cancellation Rate</span>
                        <strong>{{ $cancelledPct }}%</strong>
                    </div>
                    <div class="goal-track">
                        <div class="goal-fill fill-retention" style="width: {{ $cancelledPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const analyticsData = {
            revenueData: @json($revenueData),
            statusLabels: @json(array_map('ucfirst', array_keys($statusCounts))),
            statusData: @json(array_values($statusCounts)),
            dailyLabels: @json($dailyLabels),
            dailyOrders: @json($dailyOrders),
            dailyCustomers: @json($dailyCustomers),
        };
    </script>
    <script src="{{ asset('js/vendor/analytics.js') }}"></script>
@endpush
@endsection
