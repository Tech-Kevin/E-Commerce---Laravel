<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vendor Panel')</title>
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/super-admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @if(!empty($siteSetting?->favicon_path))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $siteSetting->favicon_path) }}">
    @endif
    <style>
        :root {
            --vendor-primary: {{ $siteSetting?->vendor_primary_color ?? '#e67e4d' }};
            --vendor-secondary: {{ $siteSetting?->vendor_secondary_color ?? '#f2af78' }};
            --vendor-bg: {{ $siteSetting?->vendor_background_color ?? '#f8f6f3' }};
        }

        body {
            background: var(--vendor-bg);
        }

        .vendor-brand-icon,
        .vendor-avatar,
        .customer-avatar,
        .product-avatar,
        .product-thumb,
        .analytics-product-rank {
            background: linear-gradient(135deg, var(--vendor-secondary), var(--vendor-primary));
        }

        .vendor-nav-item.active,
        .card-link,
        .sale-price,
        .analytics-product-growth,
        .vendor-logout-btn {
            color: var(--vendor-primary);
        }

        .vendor-nav-item.active,
        .analytics-pill.active,
        .analytics-pill:hover {
            border-color: var(--vendor-primary);
        }

        .add-product-btn,
        .product-submit-btn,
        .goal-fill,
        .analytics-fill {
            background: linear-gradient(135deg, var(--vendor-secondary), var(--vendor-primary));
        }

        .stats-card-icon.sales,
        .stats-card-icon.orders,
        .stats-card-icon.products,
        .stats-card-icon.customers {
            color: var(--vendor-primary);
        }
    </style>
</head>

<body>
    <div class="vendor-layout">
        @include('vendor.partials.sidebar')

        <div class="vendor-main">
            @if(session()->has('impersonator_id'))
                <div class="sa-impersonate-bar">
                    <i class="fa-solid fa-user-secret"></i>
                    You are impersonating <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->role }})
                    <form action="{{ route('impersonate.stop') }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit">Return to Super Admin</button>
                    </form>
                </div>
            @endif
            @if(session('success'))
                <div id="flash-message" class="flash-message success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div id="flash-message" class="flash-message error">
                    {{ session('error') }}
                </div>
            @endif
            <header class="vendor-header">
                <div class="vendor-header-left">
                    <h1>@yield('page_title', 'Dashboard')</h1>
                    <p>@yield('page_subtitle', 'Welcome to your vendor dashboard')</p>
                </div>

                @php use Illuminate\Support\Facades\Auth; @endphp
                <div class="vendor-header-right" style="display:flex;align-items:center;">
                    <div class="vendor-profile">
                        <div class="vendor-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'V', 0, 1)) }}</div>
                        <div>
                            <h4>{{ Auth::user()->name ?? 'Vendor' }}</h4>
                            <span>{{ (Auth::user()->role ?? 'vendor') === 'admin' ? 'Super Admin' : 'Store Manager' }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="vendor-content">
                @yield('content')
            </main>
        </div>
    </div>
    <script src="{{ asset('js/vendor/super-admin.js') }}"></script>
    @stack('scripts')
    <script src="{{ asset('js/vendor/layout.js') }}"></script>
</body>

</html>
