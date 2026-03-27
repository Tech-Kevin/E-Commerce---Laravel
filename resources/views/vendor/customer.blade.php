@extends('layouts.vendor')

@section('title', 'Customers')
@section('page_title', 'Customers')
@section('page_subtitle', 'View and manage your customer base')

@section('content')
    <div class="dashboard-card">
        <div class="card-header">
            <div>
                <h3>Customer List</h3>
                <p class="card-subtext">Registered customers on your store</p>
            </div>
        </div>

        @if($customers->isEmpty())
            <p style="padding: 24px; color: #8a7769; text-align: center;">No customers registered yet.</p>
        @else
        <div class="customer-grid">
            @foreach($customers as $customer)
            <div class="customer-card">
                <div class="customer-avatar">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                <div class="customer-info">
                    <h4>{{ $customer->name }}</h4>
                    <p>{{ $customer->email }}</p>
                    <span>{{ $customer->orders_count }} order(s)</span>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
@endsection
