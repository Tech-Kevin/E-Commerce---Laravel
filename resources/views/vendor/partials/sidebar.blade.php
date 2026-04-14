<aside class="vendor-sidebar">
    <div class="vendor-brand">
        <div class="vendor-brand-icon">
            @if(!empty($siteSetting?->logo_path))
                <img src="{{ asset('storage/' . $siteSetting->logo_path) }}" alt="logo" style="width:100%;height:100%;object-fit:cover;border-radius:inherit;">
            @else
                {{ strtoupper(substr(($siteSetting?->store_name ?? 'E'), 0, 1)) }}
            @endif
        </div>
        <div class="vendor-brand-text">
            <h2>{{ $siteSetting?->store_name ?? 'Ekka_Lv' }}</h2>
            <p>Super Admin Hub</p>
        </div>
    </div>

    <nav class="vendor-nav">
        <div class="vendor-nav-group-label">Overview</div>
        <a href="{{ route('vendor.dashboard') }}" class="vendor-nav-item {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('vendor.analytics') }}" class="vendor-nav-item {{ request()->routeIs('vendor.analytics') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-pie"></i>
            <span>Analytics</span>
        </a>
        <a href="{{ route('vendor.earnings') }}" class="vendor-nav-item {{ request()->routeIs('vendor.earnings') ? 'active' : '' }}">
            <i class="fa-solid fa-wallet"></i>
            <span>Earnings</span>
        </a>

        <div class="vendor-nav-group-label">Catalog</div>
        <a href="{{ route('vendor.product.show') }}" class="vendor-nav-item {{ request()->routeIs('vendor.product.show') ? 'active' : '' }}">
            <i class="fa-solid fa-box-open"></i>
            <span>Products</span>
        </a>
        <a href="{{ route('vendor.categories') }}" class="vendor-nav-item {{ request()->routeIs('vendor.categories') ? 'active' : '' }}">
            <i class="fa-solid fa-layer-group"></i>
            <span>Categories</span>
        </a>
        <a href="{{ route('vendor.sales') }}" class="vendor-nav-item {{ request()->routeIs('vendor.sales') ? 'active' : '' }}">
            <i class="fa-solid fa-tags"></i>
            <span>Sales</span>
        </a>

        <div class="vendor-nav-group-label">Commerce</div>
        <a href="{{ route('vendor.orders') }}" class="vendor-nav-item {{ request()->routeIs('vendor.orders') || request()->routeIs('vendor.orders.*') ? 'active' : '' }}">
            <i class="fa-solid fa-bag-shopping"></i>
            <span>Orders</span>
        </a>
        <a href="{{ route('vendor.customers') }}" class="vendor-nav-item {{ request()->routeIs('vendor.customers') ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i>
            <span>Customers</span>
        </a>
        <a href="{{ route('vendor.delivery-boys') }}" class="vendor-nav-item {{ request()->routeIs('vendor.delivery-boys') ? 'active' : '' }}">
            <i class="fa-solid fa-motorcycle"></i>
            <span>Delivery Boys</span>
        </a>

        <div class="vendor-nav-group-label">God Mode</div>
        <a href="{{ route('vendor.users.index') }}" class="vendor-nav-item {{ request()->routeIs('vendor.users.*') ? 'active' : '' }}">
            <i class="fa-solid fa-user-shield"></i>
            <span>Users</span>
        </a>
        <a href="{{ route('vendor.site.content') }}" class="vendor-nav-item {{ request()->routeIs('vendor.site.content') ? 'active' : '' }}">
            <i class="fa-solid fa-palette"></i>
            <span>Site Content</span>
        </a>
        <a href="{{ route('vendor.settings') }}" class="vendor-nav-item {{ request()->routeIs('vendor.settings') ? 'active' : '' }}">
            <i class="fa-solid fa-gear"></i>
            <span>Branding / Theme</span>
        </a>
        <a href="{{ route('vendor.system') }}" class="vendor-nav-item {{ request()->routeIs('vendor.system') ? 'active' : '' }}">
            <i class="fa-solid fa-server"></i>
            <span>System Tools</span>
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
