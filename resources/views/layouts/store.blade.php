<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ShopPanel')</title>
    <link rel="stylesheet" href="{{ asset('css/store.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="store-page">
        @include('customer.partials.footer')

        <main class="store-main">
            @yield('content')
        </main>

        {{-- @include('store.partials.footer') --}}
    </div>
</body>
</html>