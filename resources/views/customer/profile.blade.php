@extends('layouts.store')

@section('title', __('store.my_account'))

@section('content')
    <section class="page-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2><i class="fa-solid fa-user-gear" style="color:var(--accent);margin-right:8px;"></i>{{ __('store.my_account') }}</h2>
                    <p>{{ __('store.manage_profile') }}</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:18px;">
                    <i class="fa-solid fa-circle-check" style="margin-right:6px;"></i> {{ session('success') }}
                </div>
            @endif

            <div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;align-items:start;">
                {{-- Profile Sidebar --}}
                <div class="profile-card" style="text-align:center;">
                    <div class="profile-avatar" style="width:90px;height:90px;font-size:32px;border-radius:var(--radius-xl);margin:0 auto 16px;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <h3 style="font-size:20px;font-weight:800;color:var(--text-primary);margin-bottom:4px;">{{ Auth::user()->name }}</h3>
                    <p style="font-size:14px;color:var(--text-muted);margin-bottom:20px;">{{ Auth::user()->email }}</p>

                    <div style="display:flex;flex-direction:column;gap:8px;text-align:left;">
                        <a href="{{ route('customer.orders') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:var(--radius-sm);color:var(--text-secondary);font-size:14px;font-weight:600;transition:all 0.2s;">
                            <i class="fa-solid fa-bag-shopping" style="width:18px;color:var(--text-faint);"></i> {{ __('store.my_orders') }}
                        </a>
                        <a href="{{ route('customer.wishlist') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:var(--radius-sm);color:var(--text-secondary);font-size:14px;font-weight:600;transition:all 0.2s;">
                            <i class="fa-regular fa-heart" style="width:18px;color:var(--text-faint);"></i> {{ __('store.my_wishlist') }}
                        </a>
                        <a href="{{ route('cart.index') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:var(--radius-sm);color:var(--text-secondary);font-size:14px;font-weight:600;transition:all 0.2s;">
                            <i class="fa-solid fa-cart-shopping" style="width:18px;color:var(--text-faint);"></i> {{ __('store.cart') }}
                        </a>
                    </div>
                </div>

                {{-- Profile Form --}}
                <div class="profile-card">
                    <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);margin-bottom:6px;display:flex;align-items:center;gap:8px;">
                        <i class="fa-solid fa-pen" style="font-size:14px;color:var(--accent);"></i>
                        {{ __('store.edit_profile') }}
                    </h3>
                    <p style="font-size:13px;color:var(--text-muted);margin-bottom:24px;">Update your personal information</p>

                    <form action="{{ route('customer.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if($errors->any())
                            <div class="alert alert-error" style="margin-bottom:18px;">
                                <ul style="margin:0; padding-left:16px;">
                                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="checkout-grid">
                            <div class="form-group">
                                <label class="form-label">{{ __('store.name') }}</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">{{ __('store.email') }}</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">{{ __('store.phone') }}</label>
                                <input type="text" name="number" class="form-control" value="{{ old('number', Auth::user()->number) }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">{{ __('store.address') }}</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address', Auth::user()->address) }}">
                            </div>
                        </div>

                        <div class="profile-actions" style="margin-top:24px;">
                            <button type="submit" class="primary-btn"><i class="fa-solid fa-check"></i> {{ __('store.save_changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
