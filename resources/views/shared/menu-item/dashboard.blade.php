@role('Admin|Cajero|Almacen')
<li class="menu-item {{ request()->routeIs('dashboard') ? 'active open' : '' }}">
    <a href="{{ route('dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-smile"></i>
        <div class="text-truncate" data-i18n="Dashboard">Dashboard</div>
    </a>
</li>
@endrole
