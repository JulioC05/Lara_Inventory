<nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="icon-base bx bx-menu icon-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
        <!-- Search -->


        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
            <!-- Place this tag where you want the button to render. -->
            <li class="nav-item lh-1 me-4">
                <span></span>
            </li>

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <div class="avatar avatar-online">
                         @if (auth()->user()->avatar &&
                                                auth()->user()->avatar !== 'avatars/default.png' &&
                                                Storage::disk('public')->exists(auth()->user()->avatar))
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="user-avatar"
                                class="rounded-circle w-px-40 h-px-40" style="object-fit: cover;">
                        @else
                            <div class="avatar avatar-online me-2">
                                <span class="avatar-initial rounded-circle bg-label-primary fw-semibold fs-5">
                                    {{auth()->user()->iniciales }}
                                </span>
                            </div>
                        @endif
                        {{-- alt="" class="w-px-40 h-auto rounded-circle"> --}}
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        @if (auth()->user()->avatar &&
                                                auth()->user()->avatar !== 'avatars/default.png' &&
                                                Storage::disk('public')->exists(auth()->user()->avatar))
                                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="user-avatar"
                                                class="rounded-circle w-px-40 h-px-40" style="object-fit: cover;">
                                        @else
                                            <div class="avatar avatar-online me-2">
                                                <span
                                                    class="avatar-initial rounded-circle bg-label-primary fw-semibold fs-5">
                                                    {{ auth()->user()->iniciales }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">
                                        <span class="fw-semibold d-block">{{ auth()->user()->name }}</span>
                                    </h6>
                                    <small class="text-body-secondary">
                                        {{-- {{ Auth::user()->rol }} --}}
                                        {{ auth()->user()->getRoleNames()->first() ?? 'Empleado' }}
                                    </small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="icon-base bx bx-user icon-md me-3"></i><span>Mi Perfil</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="icon-base bx bx-cog icon-md me-3"></i><span>Configuraciones</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                            <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Cerrar Sesión</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
