@extends('layouts.vendor')

@section('title', 'Customers')
@section('page_title', 'Customers')
@section('page_subtitle', 'View and manage your customer base')

@section('content')
    <div class="dashboard-card">
        <div class="card-header">
            <div>
                <h3>Customer List</h3>
                <p class="card-subtext">Recent and active buyers from your store</p>
            </div>
        </div>

        <div class="customer-grid">
            <div class="customer-card">
                <div class="customer-avatar">R</div>
                <div class="customer-info">
                    <h4>Rahul Mehta</h4>
                    <p>rahul@example.com</p>
                    <span>12 Orders</span>
                </div>
            </div>

            <div class="customer-card">
                <div class="customer-avatar">P</div>
                <div class="customer-info">
                    <h4>Priya Shah</h4>
                    <p>priya@example.com</p>
                    <span>8 Orders</span>
                </div>
            </div>

            <div class="customer-card">
                <div class="customer-avatar">A</div>
                <div class="customer-info">
                    <h4>Amit Patel</h4>
                    <p>amit@example.com</p>
                    <span>5 Orders</span>
                </div>
            </div>

            <div class="customer-card">
                <div class="customer-avatar">N</div>
                <div class="customer-info">
                    <h4>Neha Joshi</h4>
                    <p>neha@example.com</p>
                    <span>9 Orders</span>
                </div>
            </div>
        </div>
    </div>
@endsection