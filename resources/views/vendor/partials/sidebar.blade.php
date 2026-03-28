<aside class="vendor-sidebar">
    <div class="vendor-brand">
        <div class="vendor-brand-icon">E</div>
        <div class="vendor-brand-text">
            <h2>Ekka_Lv</h2>
            <p>Vendor Hub</p>
        </div>
    </div>

    <nav class="vendor-nav">
        <a href="{{ route('vendor.dashboard') }}" class="vendor-nav-item {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('vendor.product.show') }}" class="vendor-nav-item {{ request()->routeIs('vendor.product.show') ? 'active' : '' }}">
            <i class="fa-solid fa-box-open"></i>
            <span>Products</span>
        </a>

        <a href="{{ route('vendor.orders') }}" class="vendor-nav-item {{ request()->routeIs('vendor.orders') ? 'active' : '' }}">
            <i class="fa-solid fa-bag-shopping"></i>
            <span>Orders</span>
        </a>

        <a href="{{ route('vendor.customers') }}" class="vendor-nav-item {{ request()->routeIs('vendor.customers') ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i>
            <span>Customers</span>
        </a>

        <a href="{{ route('vendor.analytics') }}" class="vendor-nav-item {{ request()->routeIs('vendor.analytics') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-pie"></i>
            <span>Analytics</span>
        </a>

        <a href="{{ route('vendor.earnings') }}" class="vendor-nav-item {{ request()->routeIs('vendor.earnings') ? 'active' : '' }}">
            <i class="fa-solid fa-wallet"></i>
            <span>Earnings</span>
        </a>

        <a href="{{ route('vendor.settings') }}" class="vendor-nav-item {{ request()->routeIs('vendor.settings') ? 'active' : '' }}">
            <i class="fa-solid fa-gear"></i>
            <span>Settings</span>
        </a>
    </nav>

    <div class="vendor-sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="vendor-logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>