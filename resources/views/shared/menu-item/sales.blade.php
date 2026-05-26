@role('Admin|Cajero')
<li class="menu-item">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-cart"></i>
        <div class="text-truncate" data-i18n="Ventas">Ventas</div>
    </a>

    <ul class="menu-sub">
        <li class="menu-item">
            <a href="{{ route('ventas.create') }}" class="menu-link">
                <div>Nueva venta</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('ventas.index') }}" class="menu-link">
                <div>Historial de ventas</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                <div>Clientes</div>
            </a>
        </li>
    </ul>
</li>
@endrole
