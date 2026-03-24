<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

                <div class="vendor-header-right">
                    <div class="vendor-profile">
                        <div class="vendor-avatar">V</div>
                        <div>
                            <h4>Vendor</h4>
                            <span>Store Manager</span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="vendor-content">
                @yield('content')
            </main>
        </div>
    </div>
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