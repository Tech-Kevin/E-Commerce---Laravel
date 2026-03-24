@extends('layouts.vendor')

@section('title', 'Vendor Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Track store activity, orders and performance')

@section('content')
    <section class="stats-grid">
        <div class="stats-card">
            <div class="stats-card-icon sales">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
            <div class="stats-card-info">
                <h3>Total Sales</h3>
                <h2>₹48,500</h2>
                <p>+12.5% from last week</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon orders">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div class="stats-card-info">
                <h3>Total Orders</h3>
                <h2>320</h2>
                <p>+8 new orders today</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon products">
                <i class="fa-solid fa-box"></i>
            </div>
            <div class="stats-card-info">
                <h3>Total Products</h3>
                <h2>85</h2>
                <p>12 low stock items</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon customers">
                <i class="fa-solid fa-user-group"></i>
            </div>
            <div class="stats-card-info">
                <h3>Total Customers</h3>
                <h2>1,240</h2>
                <p>+24 joined this month</p>
            </div>
        </div>
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-card large-card">
            <div class="card-header">
                <h3>Recent Orders</h3>
                <a href="#" class="card-link">View All</a>
            </div>

            <div class="table-wrapper">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Status</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#ORD1025</td>
                            <td>Rahul Shah</td>
                            <td>Wireless Headphones</td>
                            <td><span class="status-badge processing">Processing</span></td>
                            <td>₹2,499</td>
                        </tr>
                        <tr>
                            <td>#ORD1024</td>
                            <td>Neha Patel</td>
                            <td>Smart Watch</td>
                            <td><span class="status-badge delivered">Delivered</span></td>
                            <td>₹3,999</td>
                        </tr>
                        <tr>
                            <td>#ORD1023</td>
                            <td>Amit Joshi</td>
                            <td>Bluetooth Speaker</td>
                            <td><span class="status-badge pending">Pending</span></td>
                            <td>₹1,699</td>
                        </tr>
                        <tr>
                            <td>#ORD1022</td>
                            <td>Priya Mehta</td>
                            <td>Phone Cover</td>
                            <td><span class="status-badge delivered">Delivered</span></td>
                            <td>₹499</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dashboard-card side-card">
            <div class="card-header">
                <h3>Top Products</h3>
            </div>

            <div class="product-list">
                <div class="product-item">
                    <div class="product-thumb">P</div>
                    <div class="product-details">
                        <h4>Wireless Headphones</h4>
                        <p>120 sales</p>
                    </div>
                </div>

                <div class="product-item">
                    <div class="product-thumb">P</div>
                    <div class="product-details">
                        <h4>Smart Watch</h4>
                        <p>98 sales</p>
                    </div>
                </div>

                <div class="product-item">
                    <div class="product-thumb">P</div>
                    <div class="product-details">
                        <h4>Bluetooth Speaker</h4>
                        <p>76 sales</p>
                    </div>
                </div>

                <div class="product-item">
                    <div class="product-thumb">P</div>
                    <div class="product-details">
                        <h4>Mobile Stand</h4>
                        <p>61 sales</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection