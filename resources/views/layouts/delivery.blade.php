<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Delivery Panel')</title>
    <link rel="stylesheet" href="{{ asset('css/delivery.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
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
                <div class="vendor-header-left">
                    <h1>@yield('page_title', 'Dashboard')</h1>
                    <p>@yield('page_subtitle', 'Welcome to your delivery dashboard')</p>
                </div>

                @php use Illuminate\Support\Facades\Auth; @endphp
                <div class="vendor-header-right" style="display:flex;align-items:center;">
                    <div class="vendor-profile">
                        <div class="vendor-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'D', 0, 1)) }}</div>
                        <div>
                            <h4>{{ Auth::user()->name ?? 'Delivery' }}</h4>
                            <span>Delivery Partner</span>
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
    </script>
</body>

</html>
