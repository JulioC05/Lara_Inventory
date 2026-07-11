@role('Admin|Almacen|Cajero')
<li class="menu-item {{ request()->routeIs('productos.*')  || request()->routeIs('categorias.*') || request()->routeIs('marcas.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-package"></i>
        <div class="text-truncate" data-i18n="Ventas">Inventario</div>
    </a>

    <ul class="menu-sub">
        @role('Admin|Almacen|Cajero')
        <li class="menu-item {{ request()->routeIs('productos.index') ? 'active' : '' }}">
            <a href="{{ route('productos.index') }}" class="menu-link">
                <div>Productos</div>
            </a>
        </li>
        @endrole
        @role('Admin|Almacen')
        <li class="menu-item {{ request()->routeIs('categorias.index') ? 'active' : '' }}">
            <a href="{{ route('categorias.index') }}" class="menu-link">
                <div>Categorías</div>
            </a>
        </li>
        @endrole
        @role('Admin|Almacen')
        <li class="menu-item {{ request()->routeIs('marcas.index') ? 'active' : '' }}">
            <a href="{{ route('marcas.index') }}" class="menu-link">
                <div>Marcas</div>
            </a>
        </li>
        @endrole
        {{-- <li class="menu-item">
            <a href="#" class="menu-link">
                <div>Movimientos</div>
            </a>
        </li> --}}
    </ul>
</li>
@endrole
