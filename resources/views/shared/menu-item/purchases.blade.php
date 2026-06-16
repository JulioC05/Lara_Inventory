@role('Admin|Almacen')
    <li class="menu-item {{ request()->routeIs('compras.*') || request()->routeIs('proveedores.*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-cart-add"></i>
            <div class="text-truncate">Compras</div>
        </a>

        <ul class="menu-sub">
            <li class="menu-item {{ request()->routeIs('compras.create') ? 'active' : '' }}">
                <a href="{{ route('compras.create') }}" class="menu-link">
                    <div>Nueva compra</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('compras.index') ? 'active' : '' }}">
                <a href="{{ route('compras.index') }}" class="menu-link">
                    <div>Historial de compras</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('proveedores.*') ? 'active' : '' }}">
                <a href="{{ route('proveedores.index') }}" class="menu-link">
                    <div>Proveedores</div>
                </a>
            </li>
        </ul>
    </li>
@endrole
