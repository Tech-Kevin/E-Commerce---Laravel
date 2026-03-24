@extends('layouts.vendor')

@section('title', 'Settings')
@section('page_title', 'Settings')
@section('page_subtitle', 'Manage your store preferences and profile')

@section('content')
    <div class="dashboard-card">
        <div class="card-header">
            <div>
                <h3>Store Settings</h3>
                <p class="card-subtext">Update basic store information</p>
            </div>
        </div>

        <form class="settings-form">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Store Name</label>
                    <input type="text" class="form-control" value="ShopPanel Store">
                </div>

                <div class="form-group">
                    <label class="form-label">Store Email</label>
                    <input type="email" class="form-control" value="vendor@shoppanel.com">
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Store Address</label>
                    <input type="text" class="form-control" value="Ahmedabad, Gujarat, India">
                </div>

                <div class="form-group">
                    <label class="form-label">Contact Number</label>
                    <input type="text" class="form-control" value="+91 98765 43210">
                </div>

                <div class="form-group">
                    <label class="form-label">Currency</label>
                    <input type="text" class="form-control" value="INR">
                </div>
            </div>

            <div class="product-form-actions">
                <button type="button" class="product-submit-btn">Save Settings</button>
            </div>
        </form>
    </div>
@endsection