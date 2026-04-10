<header class="store-header">
    <div class="store-container header-inner">
        <a href="{{ route('home') }}" class="store-logo">
            <div class="store-logo-icon">E</div>
            <div>
                <h2>Ekka_Lv</h2>
                <p>{{ __('store.online_store') }}</p>
            </div>
        </a>

        <form action="{{ route('home') }}" method="get" class="header-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" placeholder="{{ __('store.search_placeholder') }}" value="{{ request('search') }}">
        </form>

        <nav class="store-nav">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('store.home') }}</a>
            <div class="nav-dropdown">
                <a href="#" class="nav-dropdown-trigger {{ request()->routeIs('category.products') ? 'active' : '' }}">
                    {{ __('store.category') }} <i class="fa-solid fa-chevron-down" style="font-size:11px; margin-left:4px;"></i>
                </a>
                <div class="nav-dropdown-menu">
                    @foreach($navCategories as $cat)
                        <a href="{{ route('category.products', $cat->slug ?? $cat->id) }}" class="nav-dropdown-item">{{ $cat->name }}</a>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('customer.orders') }}" class="{{ request()->routeIs('customer.orders') ? 'active' : '' }}">{{ __('store.orders') }}</a>
        </nav>

        <div class="header-actions">
            {{-- Language Flag Dropdown --}}
            <div class="lang-flag-dropdown" id="langDropdown">
                @php $currentLocale = Auth::check() ? (Auth::user()->locale ?? 'en') : (session('locale', 'en')); @endphp
                <button type="button" class="lang-flag-btn" onclick="document.getElementById('langDropdown').classList.toggle('open')">
                    @if($currentLocale === 'hi')
                        <img src="https://flagcdn.com/w40/in.png" alt="HI" class="flag-img">
                    @else
                        <img src="https://flagcdn.com/w40/us.png" alt="EN" class="flag-img">
                    @endif
                    <i class="fa-solid fa-chevron-down" style="font-size:9px; color:#8b7769;"></i>
                </button>
                <div class="lang-flag-menu">
                    <form action="{{ route('language.switch') }}" method="POST" id="lang-switch-form">
                        @csrf
                        <input type="hidden" name="locale" id="lang-switch-val">
                        @if($currentLocale === 'hi')
                            <button type="button" class="lang-flag-option" onclick="document.getElementById('lang-switch-val').value='en';document.getElementById('lang-switch-form').submit();">
                                <img src="https://flagcdn.com/w40/us.png" alt="EN" class="flag-img"> English
                            </button>
                        @else
                            <button type="button" class="lang-flag-option" onclick="document.getElementById('lang-switch-val').value='hi';document.getElementById('lang-switch-form').submit();">
                                <img src="https://flagcdn.com/w40/in.png" alt="HI" class="flag-img"> हिन्दी
                            </button>
                        @endif
                    </form>
                </div>
            </div>

            <a href="{{ route('customer.wishlist') }}" class="header-icon-btn">
                <i class="fa-regular fa-heart"></i>
                <span>{{ __('store.wishlist') }}</span>
                <span class="header-badge" id="wishlist-count" style="{{ $wishlistCount > 0 ? '' : 'display:none;' }}">{{ $wishlistCount }}</span>
            </a>

            <a href="{{ route('cart.index') }}" class="header-icon-btn">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>{{ __('store.cart') }}</span>
                <span class="header-badge" id="cart-count" style="{{ $cartCount > 0 ? '' : 'display:none;' }}">{{ $cartCount }}</span>
            </a>

            @auth
            <div class="header-icon-btn user-btn" style="position:relative; cursor:pointer;" onclick="this.querySelector('.user-dropdown').classList.toggle('open')">
                <i class="fa-regular fa-user"></i>
                <span>{{ Auth::user()->name }}</span>
                <div class="user-dropdown" style="display:none; position:absolute; top:100%; right:0; background:#fff; border:1px solid #f2e7dc; border-radius:8px; min-width:140px; z-index:100; box-shadow:0 4px 16px rgba(0,0,0,.08);">
                    <a href="{{ route('customer.profile') }}" style="display:block; padding:10px 16px; color:#2f241f; text-decoration:none; font-size:14px;">{{ __('store.my_profile') }}</a>
                    <a href="{{ route('customer.orders') }}" style="display:block; padding:10px 16px; color:#2f241f; text-decoration:none; font-size:14px;">{{ __('store.my_orders') }}</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" style="width:100%; text-align:left; padding:10px 16px; background:none; border:none; color:#e05a2b; font-size:14px; cursor:pointer;">{{ __('store.logout') }}</button>
                    </form>
                </div>
            </div>
            @else
            <a href="{{ route('loginForm') }}" class="header-icon-btn user-btn">
                <i class="fa-regular fa-user"></i>
                <span>{{ __('store.login') }}</span>
            </a>
            @endauth
        </div>
    </div>
</header>
