@extends('layouts.delivery')

@section('title', 'Settings')
@section('page_title', 'Settings')
@section('page_subtitle', 'Manage your profile and preferences')

@section('content')
    <div class="dashboard-card">
        <div class="card-header">
            <div>
                <h3>Profile Settings</h3>
                <p class="card-subtext">Update your account information</p>
            </div>
        </div>

        @if($errors->any())
            <div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
                <ul style="margin:0; padding-left:16px;">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form class="settings-form" action="{{ route('delivery.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                </div>

                <div class="form-group full-width">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="number" class="form-control" value="{{ old('number', $user->number) }}">
                </div>
            </div>

            <div class="product-form-actions">
                <button type="submit" class="product-submit-btn">Save Settings</button>
            </div>
        </form>
    </div>
@endsection
