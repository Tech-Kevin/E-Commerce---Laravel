@extends('layouts.vendor')

@section('title', 'Earnings')
@section('page_title', 'Earnings')
@section('page_subtitle', 'Track your income, payouts and revenue')

@section('content')
    <section class="stats-grid">
        <div class="stats-card">
            <div class="stats-card-icon sales">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
            <div class="stats-card-info">
                <h3>Total Revenue</h3>
                <h2>₹ 1,48,500</h2>
                <p>All-time earnings</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon orders">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div class="stats-card-info">
                <h3>This Month</h3>
                <h2>₹ 24,800</h2>
                <p>+12% vs last month</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon products">
                <i class="fa-solid fa-money-bill-transfer"></i>
            </div>
            <div class="stats-card-info">
                <h3>Pending Payout</h3>
                <h2>₹ 8,500</h2>
                <p>Next payout cycle</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon customers">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div class="stats-card-info">
                <h3>Avg Order Value</h3>
                <h2>₹ 2,950</h2>
                <p>Per order estimate</p>
            </div>
        </div>
    </section>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Recent Payouts</h3>
        </div>

        <div class="table-wrapper">
            <table class="dashboard-table">
                <thead>
                    <tr>
                        <th>Payout ID</th>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#PAY1001</td>
                        <td>20 Mar 2026</td>
                        <td>Bank Transfer</td>
                        <td><span class="status-badge delivered">Completed</span></td>
                        <td>₹ 12,000</td>
                    </tr>
                    <tr>
                        <td>#PAY1002</td>
                        <td>10 Mar 2026</td>
                        <td>UPI</td>
                        <td><span class="status-badge delivered">Completed</span></td>
                        <td>₹ 8,500</td>
                    </tr>
                    <tr>
                        <td>#PAY1003</td>
                        <td>01 Mar 2026</td>
                        <td>Bank Transfer</td>
                        <td><span class="status-badge processing">Processing</span></td>
                        <td>₹ 6,000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection