@extends('layouts.store')

@section('title', __('store.my_account'))

@section('content')
    <section class="page-section">
        <div class="store-container">
            <div class="section-heading">
                <div>
                    <h2>{{ __('store.my_account') }}</h2>
                    <p>{{ __('store.manage_profile') }}</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success" style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="profile-card">
                <div class="profile-top">
                    <div class="profile-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    <div>
                        <h3>{{ Auth::user()->name }}</h3>
                        <p>{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <form action="{{ route('customer.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                        <div class="alert alert-error" style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:12px 16px;border-radius:8px;margin-bottom:16px;">
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

                    <div class="profile-actions">
                        <button type="submit" class="primary-btn">{{ __('store.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
