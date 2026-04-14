<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('store.shop_title'))</title>
    <link rel="stylesheet" href="{{ asset('css/store.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/store-premium.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @if($siteSetting?->favicon_path)
        <link rel="icon" href="{{ asset('storage/' . $siteSetting->favicon_path) }}">
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --store-primary: {{ $siteSetting?->store_primary_color ?? '#e67e4d' }};
            --store-secondary: {{ $siteSetting?->store_secondary_color ?? '#f3b37a' }};
        }
    </style>
</head>
<body>
    <div class="store-page">
        @include('customer.partials.footer')

        <main class="store-main">
            @if(session()->has('impersonator_id'))
                <div style="position:fixed;top:0;left:0;right:0;z-index:9999;background:linear-gradient(135deg,#1a1410,#2f2419);color:#ffd9b8;padding:10px 20px;display:flex;align-items:center;gap:12px;font-size:13px;font-weight:600;backdrop-filter:blur(10px);">
                    <i class="fa-solid fa-user-secret"></i>
                    Viewing as <strong style="color:#fff">{{ Auth::user()->name ?? 'Guest' }}</strong>
                    <form action="{{ route('impersonate.stop') }}" method="POST" style="margin-left:auto;display:inline">
                        @csrf
                        <button type="submit" style="background:linear-gradient(135deg,var(--store-secondary),var(--store-primary));color:#fff;border:none;padding:6px 16px;border-radius:10px;font-weight:700;cursor:pointer;font-size:12px;font-family:inherit;">Return to Super Admin</button>
                    </form>
                </div>
            @endif
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="store-footer">
            <div class="store-container">
                <div class="footer-grid">
                    <div class="footer-brand">
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                            <div class="store-logo-icon">{{ strtoupper(substr(($siteSetting?->store_name ?? 'E'), 0, 1)) }}</div>
                            <div>
                                <h3 style="font-size:18px;font-weight:800;color:var(--text-primary);">{{ $siteSetting?->store_name ?? 'Ekka_Lv' }}</h3>
                                <p style="font-size:12px;color:var(--text-muted);margin:0;">{{ $siteSetting?->store_tagline ?? __('store.online_store') }}</p>
                            </div>
                        </div>
                        <p>{{ $siteSetting?->footer_about ?? __('store.footer_about_default') }}</p>
                        <div class="footer-social">
                            @if($siteSetting?->facebook_url)
                                <a href="{{ $siteSetting->facebook_url }}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                            @endif
                            @if($siteSetting?->instagram_url)
                                <a href="{{ $siteSetting->instagram_url }}" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                            @endif
                            @if($siteSetting?->twitter_url)
                                <a href="{{ $siteSetting->twitter_url }}" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                            @endif
                            @if($siteSetting?->youtube_url)
                                <a href="{{ $siteSetting->youtube_url }}" target="_blank"><i class="fa-brands fa-youtube"></i></a>
                            @endif
                            @if(!$siteSetting?->facebook_url && !$siteSetting?->instagram_url && !$siteSetting?->twitter_url && !$siteSetting?->youtube_url)
                                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                                <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                            @endif
                        </div>
                    </div>

                    <div class="footer-col">
                        <h4>{{ __('store.quick_links') }}</h4>
                        <ul>
                            <li><a href="{{ route('home') }}">{{ __('store.home') }}</a></li>
                            <li><a href="{{ route('cart.index') }}">{{ __('store.cart') }}</a></li>
                            <li><a href="{{ route('customer.wishlist') }}">{{ __('store.wishlist') }}</a></li>
                            <li><a href="{{ route('customer.orders') }}">{{ __('store.my_orders') }}</a></li>
                        </ul>
                    </div>

                    <div class="footer-col">
                        <h4>{{ __('store.customer_service') }}</h4>
                        <ul>
                            <li><a href="{{ route('customer.profile') }}">{{ __('store.my_account') }}</a></li>
                            <li><a href="#">{{ __('store.shipping_policy') }}</a></li>
                            <li><a href="#">{{ __('store.returns_policy') }}</a></li>
                            <li><a href="#">{{ __('store.faq') }}</a></li>
                        </ul>
                    </div>

                    <div class="footer-col">
                        <h4>{{ __('store.contact_us') }}</h4>
                        <ul>
                            @if($siteSetting?->contact_email)
                                <li><a href="mailto:{{ $siteSetting->contact_email }}"><i class="fa-regular fa-envelope" style="width:16px;"></i> {{ $siteSetting->contact_email }}</a></li>
                            @endif
                            @if($siteSetting?->contact_phone)
                                <li><a href="tel:{{ $siteSetting->contact_phone }}"><i class="fa-solid fa-phone" style="width:16px;"></i> {{ $siteSetting->contact_phone }}</a></li>
                            @endif
                            @if($siteSetting?->contact_address)
                                <li><span style="color:var(--text-muted);font-size:14px;"><i class="fa-solid fa-location-dot" style="width:16px;margin-right:6px;"></i>{{ $siteSetting->contact_address }}</span></li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="footer-bottom">
                    <span>{{ $siteSetting?->footer_copyright ?? '© ' . date('Y') . ' ' . ($siteSetting?->store_name ?? 'Ekka_Lv') . '. All rights reserved.' }}</span>
                    <span style="display:flex;align-items:center;gap:6px;">
                        <i class="fa-solid fa-shield-halved" style="color:var(--accent);"></i>
                        {{ __('store.secure_checkout') }}
                    </span>
                </div>
            </div>
        </footer>
    </div>

    {{-- Mobile Nav Drawer --}}
    <div class="mobile-nav-overlay" id="mobileNavOverlay" onclick="closeMobileNav()"></div>
    <div class="mobile-nav-drawer" id="mobileNavDrawer">
        <button class="mobile-nav-close" onclick="closeMobileNav()"><i class="fa-solid fa-xmark"></i></button>
        <nav class="mobile-nav-links">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i> {{ __('store.home') }}
            </a>
            @foreach($navCategories as $cat)
                <a href="{{ route('category.products', $cat->slug ?? $cat->id) }}">
                    <i class="fa-solid fa-tag"></i> {{ $cat->name }}
                </a>
            @endforeach
            <a href="{{ route('customer.orders') }}" class="{{ request()->routeIs('customer.orders') ? 'active' : '' }}">
                <i class="fa-solid fa-bag-shopping"></i> {{ __('store.orders') }}
            </a>
            <a href="{{ route('customer.wishlist') }}">
                <i class="fa-regular fa-heart"></i> {{ __('store.wishlist') }}
            </a>
            <a href="{{ route('cart.index') }}">
                <i class="fa-solid fa-cart-shopping"></i> {{ __('store.cart') }}
            </a>
            @auth
                <a href="{{ route('customer.profile') }}">
                    <i class="fa-regular fa-user"></i> {{ __('store.my_profile') }}
                </a>
            @else
                <a href="{{ route('loginForm') }}">
                    <i class="fa-solid fa-right-to-bracket"></i> {{ __('store.login') }}
                </a>
            @endauth
        </nav>
    </div>

    {{-- Back to Top --}}
    <button class="back-to-top" id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})">
        <i class="fa-solid fa-arrow-up"></i>
    </button>

    <script src="{{ asset('js/toast.js') }}"></script>
    <script src="{{ asset('js/customer/header.js') }}"></script>
    <script>
        // Header scroll effect
        (function() {
            var header = document.querySelector('.store-header');
            if (header) {
                window.addEventListener('scroll', function() {
                    header.classList.toggle('scrolled', window.scrollY > 20);
                });
            }
        })();

        // Close lang dropdown
        document.addEventListener('click', function(e) {
            var dd = document.getElementById('langDropdown');
            if (dd && !dd.contains(e.target)) dd.classList.remove('open');
        });

        // Mobile nav
        function openMobileNav() {
            document.getElementById('mobileNavOverlay').classList.add('open');
            document.getElementById('mobileNavDrawer').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeMobileNav() {
            document.getElementById('mobileNavOverlay').classList.remove('open');
            document.getElementById('mobileNavDrawer').classList.remove('open');
            document.body.style.overflow = '';
        }

        // Back to top
        (function() {
            var btn = document.getElementById('backToTop');
            if (btn) {
                window.addEventListener('scroll', function() {
                    btn.classList.toggle('visible', window.scrollY > 400);
                });
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
