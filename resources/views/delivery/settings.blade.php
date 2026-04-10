@extends('layouts.delivery')

@section('title', __('delivery.settings'))
@section('page_title', __('delivery.settings'))
@section('page_subtitle', __('delivery.manage_preferences'))

@section('content')
    {{-- Language Switcher --}}
    <div class="dashboard-card" style="margin-bottom: 20px;">
        <div class="card-header">
            <div>
                <h3>{{ __('delivery.language') }}</h3>
                <p class="card-subtext">{{ __('delivery.language_subtitle') }}</p>
            </div>
        </div>
        <div class="lang-switcher">
            <form action="{{ route('delivery.language') }}" method="POST" id="lang-form">
                @csrf
                <input type="hidden" name="locale" id="locale-input" value="{{ Auth::user()->locale ?? 'en' }}">
                <div class="lang-options">
                    <button type="button" class="lang-btn {{ (Auth::user()->locale ?? 'en') === 'en' ? 'active' : '' }}" onclick="switchLang('en')">
                        <span class="lang-flag">EN</span>
                        <span class="lang-name">{{ __('delivery.language_english') }}</span>
                    </button>
                    <button type="button" class="lang-btn {{ (Auth::user()->locale ?? 'en') === 'hi' ? 'active' : '' }}" onclick="switchLang('hi')">
                        <span class="lang-flag">HI</span>
                        <span class="lang-name">{{ __('delivery.language_hindi') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Profile Settings --}}
    <div class="dashboard-card">
        <div class="card-header">
            <div>
                <h3>{{ __('delivery.profile_settings') }}</h3>
                <p class="card-subtext">{{ __('delivery.update_account_info') }}</p>
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
                    <label class="form-label">{{ __('delivery.full_name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('delivery.email') }}</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                </div>

                <div class="form-group full-width">
                    <label class="form-label">{{ __('delivery.address') }}</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">{{ __('delivery.contact_number') }}</label>
                    <input type="text" name="number" class="form-control" value="{{ old('number', $user->number) }}">
                </div>
            </div>

            <div class="product-form-actions">
                <button type="submit" class="product-submit-btn">{{ __('delivery.save_settings') }}</button>
            </div>
        </form>
    </div>

@push('scripts')
<script>
function switchLang(locale) {
    document.getElementById('locale-input').value = locale;
    document.getElementById('lang-form').submit();
}
</script>
@endpush
@endsection
