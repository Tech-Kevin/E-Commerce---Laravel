@extends('layouts.vendor')

@section('title', 'Orders')
@section('page_title', 'Orders')
@section('page_subtitle', 'Manage all customer orders from one place')

@section('content')
    <div class="dashboard-card">
        <div class="card-header">
            <div>
                <h3>Recent Orders</h3>
                <p class="card-subtext">Track placed, shipped and delivered orders</p>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#ORD1001</td>
                        <td>Rahul Mehta</td>
                        <td>2 Items</td>
                        <td><span class="status-badge processing">Processing</span></td>
                        <td>24 Mar 2026</td>
                        <td>₹2,499</td>
                    </tr>
                    <tr>
                        <td>#ORD1002</td>
                        <td>Priya Shah</td>
                        <td>1 Item</td>
                        <td><span class="status-badge delivered">Delivered</span></td>
                        <td>23 Mar 2026</td>
                        <td>₹5,299</td>
                    </tr>
                    <tr>
                        <td>#ORD1003</td>
                        <td>Amit Patel</td>
                        <td>3 Items</td>
                        <td><span class="status-badge pending">Pending</span></td>
                        <td>22 Mar 2026</td>
                        <td>₹8,999</td>
                    </tr>
                    <tr>
                        <td>#ORD1004</td>
                        <td>Neha Joshi</td>
                        <td>1 Item</td>
                        <td><span class="status-badge delivered">Delivered</span></td>
                        <td>21 Mar 2026</td>
                        <td>₹1,199</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection