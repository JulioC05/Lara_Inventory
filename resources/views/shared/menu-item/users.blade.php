@role('Admin')
<li class="menu-item {{ request()->routeIs('usuarios.*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-group"></i>
        <div class="text-truncate" data-i18n="Ventas">Usuarios y Roles</div>
    </a>

    <ul class="menu-sub">
        <li class="menu-item {{ request()->routeIs('usuarios.index') ? 'active' : '' }}">
            <a href="{{ route('usuarios.index') }}" class="menu-link">
                <div>Lista de usuarios</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('usuarios.roles_accesos') ? 'active' : '' }}">
            <a href="{{ route('usuarios.roles_accesos') }}" class="menu-link">
                <div>Roles</div>
            </a>
        </li>
    </ul>
</li>
@endrole
