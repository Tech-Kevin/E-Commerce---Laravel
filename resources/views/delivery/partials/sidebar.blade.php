<aside class="vendor-sidebar">
    <div class="vendor-brand">
        <div class="vendor-brand-icon">D</div>
        <div class="vendor-brand-text">
            <h2>Ekka_Lv</h2>
            <p>Delivery Hub</p>
        </div>
    </div>

    <nav class="vendor-nav">
        <a href="{{ route('delivery.dashboard') }}" class="vendor-nav-item {{ request()->routeIs('delivery.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('delivery.orders') }}" class="vendor-nav-item {{ request()->routeIs('delivery.orders') ? 'active' : '' }}">
            <i class="fa-solid fa-box"></i>
            <span>Assigned Orders</span>
        </a>

        <a href="{{ route('delivery.completed') }}" class="vendor-nav-item {{ request()->routeIs('delivery.completed') ? 'active' : '' }}">
            <i class="fa-solid fa-circle-check"></i>
            <span>Completed Orders</span>
        </a>

        <a href="{{ route('delivery.settings') }}" class="vendor-nav-item {{ request()->routeIs('delivery.settings') ? 'active' : '' }}">
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
