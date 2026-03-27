<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vendor Panel')</title>
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="vendor-layout">
        @include('vendor.partials.sidebar')

        <div class="vendor-main">
            @if(session('success'))
                <div id="flash-message" class="flash-message success">
                    {{ session('success') }}
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
                            <span>Store Manager</span>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="margin-left:12px;">
                        @csrf
                        <button type="submit" style="background:none;border:1px solid #f2e7dc;color:#8a7769;padding:6px 14px;border-radius:6px;cursor:pointer;font-size:13px;">
                            <i class="fa-solid fa-right-from-bracket"></i> Logout
                        </button>
                    </form>
                </div>
            </header>

            <main class="vendor-content">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
    <script>
    setTimeout(() => {
        const flash = document.getElementById('flash-message');
        if (flash) {
            flash.style.animation = "fadeOut 0.5s ease forwards";
            setTimeout(() => flash.remove(), 500);
        }
    }, 3000);
</script>
</body>

</html>