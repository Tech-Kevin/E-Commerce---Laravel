<header class="store-header">
    <div class="store-container header-inner">
        <a href="#" class="store-logo">
            <div class="store-logo-icon">S</div>
            <div>
                <h2>ShopPanel</h2>
                <p>Online Store</p>
            </div>
        </a>

        <form action="#" method="get" class="header-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" placeholder="Search for products...">
        </form>

        <nav class="store-nav">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="#">Shop</a>
            <a href="#">Categories</a>
            <a href="#">Deals</a>
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

            <a href="{{ route('customer.profile') }}" class="header-icon-btn user-btn">
                <i class="fa-regular fa-user"></i>
                <span>Account</span>
            </a>
        </div>
    </div>
</header>