@extends('layouts.store')

@section('title', 'My Orders')

@section('content')
    <section class="page-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2>My Orders</h2>
                    <p>Track all your orders here</p>
                </div>
            </div>

            <div class="table-card">
                <table class="store-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#ORD1025</td>
                            <td>23 Mar 2026</td>
                            <td><span class="stock-badge in-stock">Delivered</span></td>
                            <td>₹2,598</td>
                            <td><a href="#" class="table-link">View</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection