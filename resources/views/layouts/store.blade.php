<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('store.shop_title'))</title>
    <link rel="stylesheet" href="{{ asset('css/store.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
    <div class="store-page">
        @include('customer.partials.footer')

        <main class="store-main">
            @yield('content')
        </main>

        {{-- @include('store.partials.footer') --}}
    </div>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script src="{{ asset('js/customer/header.js') }}"></script>
    <script>
        // Close lang dropdown when clicking outside
        document.addEventListener('click', function(e) {
            var dd = document.getElementById('langDropdown');
            if (dd && !dd.contains(e.target)) dd.classList.remove('open');
        });
    </script>
    @stack('scripts')
</body>
</html>
