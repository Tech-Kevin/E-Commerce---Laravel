<aside class="vendor-sidebar">
    <div class="vendor-brand">
        <div class="vendor-brand-icon">{{ strtoupper(substr(($siteSetting?->store_name ?? 'D'), 0, 1)) }}</div>
        <div class="vendor-brand-text">
            <h2>{{ $siteSetting?->store_name ?? 'Ekka_Lv' }}</h2>
            <p>{{ __('delivery.delivery_hub') }}</p>
        </div>
    </div>

    <nav class="vendor-nav">
        <a href="{{ route('delivery.dashboard') }}" class="vendor-nav-item {{ request()->routeIs('delivery.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i>
            <span>{{ __('delivery.nav_dashboard') }}</span>
        </a>

        <a href="{{ route('delivery.orders') }}" class="vendor-nav-item {{ request()->routeIs('delivery.orders') ? 'active' : '' }}">
            <i class="fa-solid fa-box"></i>
            <span>{{ __('delivery.nav_assigned_orders') }}</span>
        </a>

        <a href="{{ route('delivery.completed') }}" class="vendor-nav-item {{ request()->routeIs('delivery.completed') ? 'active' : '' }}">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ __('delivery.nav_completed_orders') }}</span>
        </a>

        <a href="{{ route('delivery.settings') }}" class="vendor-nav-item {{ request()->routeIs('delivery.settings') ? 'active' : '' }}">
            <i class="fa-solid fa-gear"></i>
            <span>{{ __('delivery.nav_settings') }}</span>
        </a>
    </nav>

    <div class="vendor-sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="vendor-logout-btn">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>{{ __('delivery.logout') }}</span>
            </button>
        </form>
    </div>
</aside>
