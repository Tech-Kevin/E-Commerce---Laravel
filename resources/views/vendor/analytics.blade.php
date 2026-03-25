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
                <h2>₹1,48,500</h2>
                <p>+18.4% this month</p>
            </div>
        </div>

        <div class="stats-card analytics-stat-card">
            <div class="stats-card-icon orders">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div class="stats-card-info">
                <h3>Total Orders</h3>
                <h2>1,286</h2>
                <p>+9.2% this week</p>
            </div>
        </div>

        <div class="stats-card analytics-stat-card">
            <div class="stats-card-icon customers">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stats-card-info">
                <h3>New Customers</h3>
                <h2>324</h2>
                <p>+12.8% growth</p>
            </div>
        </div>

        <div class="stats-card analytics-stat-card">
            <div class="stats-card-icon products">
                <i class="fa-solid fa-star"></i>
            </div>
            <div class="stats-card-info">
                <h3>Conversion Rate</h3>
                <h2>4.9%</h2>
                <p>Strong performance</p>
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

                <div class="analytics-filter-pills">
                    <button type="button" class="analytics-pill active">Yearly</button>
                    <button type="button" class="analytics-pill">Monthly</button>
                    <button type="button" class="analytics-pill">Weekly</button>
                </div>
            </div>

            <div class="chart-box">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="dashboard-card analytics-chart-card side-chart-card">
            <div class="card-header analytics-card-header">
                <div>
                    <h3>Traffic Sources</h3>
                    <p class="card-subtext">Where your buyers are coming from</p>
                </div>
            </div>

            <div class="chart-box doughnut-chart-box">
                <canvas id="trafficChart"></canvas>
            </div>

            <div class="traffic-legend">
                <div class="legend-item">
                    <span class="legend-dot dot-one"></span>
                    <span>Direct</span>
                    <strong>38%</strong>
                </div>
                <div class="legend-item">
                    <span class="legend-dot dot-two"></span>
                    <span>Social</span>
                    <strong>24%</strong>
                </div>
                <div class="legend-item">
                    <span class="legend-dot dot-three"></span>
                    <span>Search</span>
                    <strong>21%</strong>
                </div>
                <div class="legend-item">
                    <span class="legend-dot dot-four"></span>
                    <span>Ads</span>
                    <strong>17%</strong>
                </div>
            </div>
        </div>
    </section>

    <section class="analytics-bottom-grid">
        <div class="dashboard-card analytics-chart-card">
            <div class="card-header analytics-card-header">
                <div>
                    <h3>Orders vs Customers</h3>
                    <p class="card-subtext">Weekly acquisition and order trend</p>
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
                <div class="analytics-product-item">
                    <div class="analytics-product-rank">1</div>
                    <div class="analytics-product-info">
                        <h4>Wireless Headphones</h4>
                        <p>320 sales • ₹48,000 revenue</p>
                    </div>
                    <span class="analytics-product-growth">+18%</span>
                </div>

                <div class="analytics-product-item">
                    <div class="analytics-product-rank">2</div>
                    <div class="analytics-product-info">
                        <h4>Smart Watch</h4>
                        <p>280 sales • ₹39,500 revenue</p>
                    </div>
                    <span class="analytics-product-growth">+14%</span>
                </div>

                <div class="analytics-product-item">
                    <div class="analytics-product-rank">3</div>
                    <div class="analytics-product-info">
                        <h4>Bluetooth Speaker</h4>
                        <p>210 sales • ₹29,800 revenue</p>
                    </div>
                    <span class="analytics-product-growth">+11%</span>
                </div>

                <div class="analytics-product-item">
                    <div class="analytics-product-rank">4</div>
                    <div class="analytics-product-info">
                        <h4>Phone Cover</h4>
                        <p>180 sales • ₹16,200 revenue</p>
                    </div>
                    <span class="analytics-product-growth">+9%</span>
                </div>
            </div>
        </div>
    </section>

    <section class="analytics-bottom-grid">
        <div class="dashboard-card">
            <div class="card-header analytics-card-header">
                <div>
                    <h3>Recent Activity</h3>
                    <p class="card-subtext">Latest store insights and events</p>
                </div>
            </div>

            <div class="activity-timeline">
                <div class="activity-item">
                    <div class="activity-dot"></div>
                    <div class="activity-content">
                        <h4>New order spike detected</h4>
                        <p>Orders increased by 24% in the last 24 hours.</p>
                        <span>10 minutes ago</span>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-dot"></div>
                    <div class="activity-content">
                        <h4>Best-selling product updated</h4>
                        <p>Wireless Headphones moved to #1 this week.</p>
                        <span>1 hour ago</span>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-dot"></div>
                    <div class="activity-content">
                        <h4>Returning customer rate improved</h4>
                        <p>Repeat purchases are up by 8.5% this month.</p>
                        <span>3 hours ago</span>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-dot"></div>
                    <div class="activity-content">
                        <h4>Campaign traffic increased</h4>
                        <p>Social media campaign brought 1.2K new visitors.</p>
                        <span>Yesterday</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-card analytics-goal-card">
            <div class="card-header analytics-card-header">
                <div>
                    <h3>Goals Progress</h3>
                    <p class="card-subtext">Track your monthly store targets</p>
                </div>
            </div>

            <div class="goal-progress-list">
                <div class="goal-item">
                    <div class="goal-header">
                        <span>Revenue Goal</span>
                        <strong>78%</strong>
                    </div>
                    <div class="goal-track">
                        <div class="goal-fill fill-revenue"></div>
                    </div>
                </div>

                <div class="goal-item">
                    <div class="goal-header">
                        <span>Order Goal</span>
                        <strong>64%</strong>
                    </div>
                    <div class="goal-track">
                        <div class="goal-fill fill-orders"></div>
                    </div>
                </div>

                <div class="goal-item">
                    <div class="goal-header">
                        <span>Customer Goal</span>
                        <strong>81%</strong>
                    </div>
                    <div class="goal-track">
                        <div class="goal-fill fill-customers"></div>
                    </div>
                </div>

                <div class="goal-item">
                    <div class="goal-header">
                        <span>Retention Goal</span>
                        <strong>57%</strong>
                    </div>
                    <div class="goal-track">
                        <div class="goal-fill fill-retention"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartCommonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#6d5c53',
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: '#2f241f',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    padding: 12,
                    cornerRadius: 10
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#8a7769'
                    },
                    grid: {
                        display: false
                    }
                },
                y: {
                    ticks: {
                        color: '#8a7769'
                    },
                    grid: {
                        color: '#f2e7dc'
                    }
                }
            }
        };

        const revenueChart = new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Revenue',
                    data: [12000, 18000, 15000, 22000, 26000, 24000, 30000, 34000, 32000, 38000, 42000, 46000],
                    borderColor: '#e67e4d',
                    backgroundColor: 'rgba(230, 126, 77, 0.12)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#f29d62',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: chartCommonOptions
        });

        const trafficChart = new Chart(document.getElementById('trafficChart'), {
            type: 'doughnut',
            data: {
                labels: ['Direct', 'Social', 'Search', 'Ads'],
                datasets: [{
                    data: [38, 24, 21, 17],
                    backgroundColor: ['#f29d62', '#e67e4d', '#f3c49e', '#f7dcc5'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#2f241f',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        padding: 12,
                        cornerRadius: 10
                    }
                }
            }
        });

        const ordersCustomersChart = new Chart(document.getElementById('ordersCustomersChart'), {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [
                    {
                        label: 'Orders',
                        data: [32, 45, 38, 52, 60, 48, 55],
                        backgroundColor: '#f29d62',
                        borderRadius: 8
                    },
                    {
                        label: 'Customers',
                        data: [20, 30, 24, 35, 42, 33, 39],
                        backgroundColor: '#f7dcc5',
                        borderRadius: 8
                    }
                ]
            },
            options: chartCommonOptions
        });
    </script>
@endsection