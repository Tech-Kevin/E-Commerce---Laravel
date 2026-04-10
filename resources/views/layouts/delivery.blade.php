<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('delivery.panel_title'))</title>
    <link rel="stylesheet" href="{{ asset('css/delivery.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    <div class="vendor-layout">
        @include('delivery.partials.sidebar')

        <div class="vendor-main">
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
                <div style="display:flex;align-items:center;gap:12px;">
                    <button class="hamburger-btn" id="hamburgerBtn" onclick="toggleSidebar()">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div class="vendor-header-left">
                        <h1>@yield('page_title', __('delivery.dashboard'))</h1>
                        <p>@yield('page_subtitle', __('delivery.track_deliveries'))</p>
                    </div>
                </div>

                @php use Illuminate\Support\Facades\Auth; @endphp
                <div class="vendor-header-right" style="display:flex;align-items:center;">
                    <div class="vendor-profile">
                        <div class="vendor-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'D', 0, 1)) }}</div>
                        <div>
                            <h4>{{ Auth::user()->name ?? 'Delivery' }}</h4>
                            <span>{{ __('delivery.delivery_partner') }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="vendor-content">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
    <script>
        // Auto-hide flash messages
        const flash = document.getElementById('flash-message');
        if (flash) setTimeout(() => flash.style.display = 'none', 3000);

        // Mobile sidebar toggle
        function toggleSidebar() {
            document.querySelector('.vendor-sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('open');
        }
    </script>
</body>

</html>
