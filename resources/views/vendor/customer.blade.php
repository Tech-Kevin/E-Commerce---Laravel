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
        <div class="table-wrapper">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Orders</th>
                        <th>Total Spent</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $index => $customer)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="customer-avatar" style="width: 36px; height: 36px; font-size: 14px;">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
                                <span style="font-weight: 600;">{{ $customer->name }}</span>
                            </div>
                        </td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->number ?? 'N/A' }}</td>
                        <td>{{ $customer->address ? Str::limit($customer->address, 30) : 'N/A' }}</td>
                        <td><span class="badge-status" style="background: #fff3e6; color: #e67e22;">{{ $customer->orders_count }}</span></td>
                        <td>₹ {{ number_format($customer->orders_sum_grand_total ?? 0, 2) }}</td>
                        <td>{{ $customer->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
@endsection
