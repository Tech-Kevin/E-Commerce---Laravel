@extends('layouts.vendor')

@section('title', 'Settings')
@section('page_title', 'Super Admin Settings')
@section('page_subtitle', 'Manage profile, global branding, and full site theme')

@section('content')
    <div class="dashboard-card">
        <div class="card-header">
            <div>
                <h3>Super Admin Controls</h3>
                <p class="card-subtext">Update account info, website branding, and system colors</p>
            </div>
        </div>

        @if($errors->any())
            <div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
                <ul style="margin:0; padding-left:16px;">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form class="settings-form" action="{{ route('vendor.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <h4 class="settings-section-title">Admin Profile</h4>
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

            <hr class="settings-divider">

            <h4 class="settings-section-title">Global Branding</h4>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Store Name</label>
                    <input type="text" name="store_name" class="form-control" value="{{ old('store_name', $siteSetting?->store_name ?? 'Ekka_Lv') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Store Tagline</label>
                    <input type="text" name="store_tagline" class="form-control" value="{{ old('store_tagline', $siteSetting?->store_tagline ?? 'Online Store') }}">
                </div>
            </div>

            <hr class="settings-divider">

            <h4 class="settings-section-title">Theme Colors</h4>
            <p class="card-subtext" style="margin-bottom: 14px;">These colors update vendor panel, customer storefront, and delivery dashboard.</p>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Vendor Primary</label>
                    <input type="color" name="vendor_primary_color" class="form-control color-control" value="{{ old('vendor_primary_color', $siteSetting?->vendor_primary_color ?? '#e67e4d') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Vendor Secondary</label>
                    <input type="color" name="vendor_secondary_color" class="form-control color-control" value="{{ old('vendor_secondary_color', $siteSetting?->vendor_secondary_color ?? '#f2af78') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Vendor Background</label>
                    <input type="color" name="vendor_background_color" class="form-control color-control" value="{{ old('vendor_background_color', $siteSetting?->vendor_background_color ?? '#f8f6f3') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Store Primary</label>
                    <input type="color" name="store_primary_color" class="form-control color-control" value="{{ old('store_primary_color', $siteSetting?->store_primary_color ?? '#e67e4d') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Store Secondary</label>
                    <input type="color" name="store_secondary_color" class="form-control color-control" value="{{ old('store_secondary_color', $siteSetting?->store_secondary_color ?? '#f3b37a') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Delivery Primary</label>
                    <input type="color" name="delivery_primary_color" class="form-control color-control" value="{{ old('delivery_primary_color', $siteSetting?->delivery_primary_color ?? '#e67e4d') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Delivery Secondary</label>
                    <input type="color" name="delivery_secondary_color" class="form-control color-control" value="{{ old('delivery_secondary_color', $siteSetting?->delivery_secondary_color ?? '#f2af78') }}">
                </div>
            </div>

            <div class="product-form-actions">
                <button type="button" id="resetThemeDefaults" class="modal-cancel-btn">Reset Theme</button>
                <button type="submit" class="product-submit-btn">Save Super Admin Settings</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('resetThemeDefaults')?.addEventListener('click', function () {
            const defaults = {
                vendor_primary_color: '#e67e4d',
                vendor_secondary_color: '#f2af78',
                vendor_background_color: '#f8f6f3',
                store_primary_color: '#e67e4d',
                store_secondary_color: '#f3b37a',
                delivery_primary_color: '#e67e4d',
                delivery_secondary_color: '#f2af78',
            };

            Object.keys(defaults).forEach(function (name) {
                const input = document.querySelector('[name=\"' + name + '\"]');
                if (input) {
                    input.value = defaults[name];
                }
            });
        });
    </script>
@endpush
