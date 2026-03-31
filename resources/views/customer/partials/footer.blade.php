<header class="store-header">
    <div class="store-container header-inner">
        <a href="{{ route('home') }}" class="store-logo">
            <div class="store-logo-icon">E</div>
            <div>
                <h2>Ekka_Lv</h2>
                <p>Online Store</p>
            </div>
        </a>

        <form action="{{ route('home') }}" method="get" class="header-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" placeholder="Search for products..." value="{{ request('search') }}">
        </form>

        <nav class="store-nav">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <div class="nav-dropdown">
                <a href="#" class="nav-dropdown-trigger {{ request()->routeIs('category.products') ? 'active' : '' }}">
                    Category <i class="fa-solid fa-chevron-down" style="font-size:11px; margin-left:4px;"></i>
                </a>
                <div class="nav-dropdown-menu">
                    @foreach($navCategories as $cat)
                        <a href="{{ route('category.products', $cat->slug ?? $cat->id) }}" class="nav-dropdown-item">{{ $cat->name }}</a>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('customer.orders') }}" class="{{ request()->routeIs('customer.orders') ? 'active' : '' }}">Orders</a>
        </nav>

        <div class="header-actions">
            <a href="{{ route('customer.wishlist') }}" class="header-icon-btn">
                <i class="fa-regular fa-heart"></i>
                <span>Wishlist</span>
            </a>

            <a href="{{ route('cart.index') }}" class="header-icon-btn">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>Cart</span>
            </a>

            @auth
            <div class="header-icon-btn user-btn" style="position:relative; cursor:pointer;" onclick="this.querySelector('.user-dropdown').classList.toggle('open')">
                <i class="fa-regular fa-user"></i>
                <span>{{ Auth::user()->name }}</span>
                <div class="user-dropdown" style="display:none; position:absolute; top:100%; right:0; background:#fff; border:1px solid #f2e7dc; border-radius:8px; min-width:140px; z-index:100; box-shadow:0 4px 16px rgba(0,0,0,.08);">
                    <a href="{{ route('customer.profile') }}" style="display:block; padding:10px 16px; color:#2f241f; text-decoration:none; font-size:14px;">My Profile</a>
                    <a href="{{ route('customer.orders') }}" style="display:block; padding:10px 16px; color:#2f241f; text-decoration:none; font-size:14px;">My Orders</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" style="width:100%; text-align:left; padding:10px 16px; background:none; border:none; color:#e05a2b; font-size:14px; cursor:pointer;">Logout</button>
                    </form>
                </div>
            </div>
            @else
            <a href="{{ route('loginForm') }}" class="header-icon-btn user-btn">
                <i class="fa-regular fa-user"></i>
                <span>Login</span>
            </a>
            @endauth
        </div>
    </div>
</header>
