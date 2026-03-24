@extends('layouts.vendor')

@section('title', 'Analytics')
@section('page_title', 'Analytics')
@section('page_subtitle', 'Understand your store performance better')

@section('content')
    <section class="stats-grid">
        <div class="stats-card">
            <div class="stats-card-icon sales">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div class="stats-card-info">
                <h3>Weekly Sales</h3>
                <h2>₹34,500</h2>
                <p>+18% from last week</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon orders">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div class="stats-card-info">
                <h3>Conversion</h3>
                <h2>4.8%</h2>
                <p>Strong order growth</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon customers">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stats-card-info">
                <h3>Visitors</h3>
                <h2>12.4K</h2>
                <p>Monthly store traffic</p>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-card-icon products">
                <i class="fa-solid fa-box"></i>
            </div>
            <div class="stats-card-info">
                <h3>Return Rate</h3>
                <h2>1.2%</h2>
                <p>Very low return ratio</p>
            </div>
        </div>
    </section>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Performance Overview</h3>
        </div>

        <div class="analytics-bars">
            <div class="analytics-row">
                <span>Sales</span>
                <div class="analytics-track"><div class="analytics-fill sales-fill"></div></div>
                <strong>85%</strong>
            </div>

            <div class="analytics-row">
                <span>Orders</span>
                <div class="analytics-track"><div class="analytics-fill orders-fill"></div></div>
                <strong>72%</strong>
            </div>

            <div class="analytics-row">
                <span>Customers</span>
                <div class="analytics-track"><div class="analytics-fill customers-fill"></div></div>
                <strong>64%</strong>
            </div>

            <div class="analytics-row">
                <span>Growth</span>
                <div class="analytics-track"><div class="analytics-fill growth-fill"></div></div>
                <strong>91%</strong>
            </div>
        </div>
    </div>
@endsection