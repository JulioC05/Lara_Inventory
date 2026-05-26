<li class="menu-item">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
        <div class="text-truncate" data-i18n="Ventas">Reportes</div>
    </a>

    <ul class="menu-sub">
        <li class="menu-item">
            <a href="{{ route('reportes.ventas') }}" class="menu-link">
                <div>Ventas</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                <div>Compras</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('reportes.movimientos-stock') }}" class="menu-link">
                <div>Inventario</div>
            </a>
        </li>
        {{-- <li class="menu-item">
            <a href="#" class="menu-link">
                <div>Productos más vendidos</div>
            </a>
        </li> --}}
    </ul>
</li>
