@role('Admin')
<li class="menu-item">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-group"></i>
        <div class="text-truncate" data-i18n="Ventas">Usuarios</div>
    </a>

    <ul class="menu-sub">
        <li class="menu-item">
            <a href="{{ route('usuarios.index') }}" class="menu-link">
                <div>Lista de usuarios</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                <div>Roles</div>
            </a>
        </li>
    </ul>
</li>
@endrole
