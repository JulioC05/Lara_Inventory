<!doctype html>

<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="{{ asset('Sneat-Admin/assets/') }}"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('titulo')</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('Sneat-Admin/assets/img/favicon/logo-imagen.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('Sneat-Admin/assets/vendor/fonts/iconify-icons.css') }}" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{ asset('Sneat-Admin/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('Sneat-Admin/assets/css/demo.css') }}" />

    <!-- Datatables CSS -->

    <link rel="stylesheet"
        href="{{ asset('Sneat-Admin/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('Sneat-Admin/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />

    <!-- Vendors CSS -->

    <link rel="stylesheet"
        href="{{ asset('Sneat-Admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- endbuild -->

    <link rel="stylesheet" href="{{ asset('Sneat-Admin/assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    {{-- Tom-select --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('Sneat-Admin/assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{ asset('Sneat-Admin/assets/js/config.js') }}"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            @include('shared.aside')
            <div class="layout-page">
                @include('shared.nav')

                @yield('content')

            </div>

            {{-- @include('shared.footer') --}}
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->

    <script src="{{ asset('Sneat-Admin/assets/vendor/libs/jquery/jquery.js') }}"></script>

    <script src="{{ asset('Sneat-Admin/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('Sneat-Admin/assets/vendor/js/bootstrap.js') }}"></script>

    <script src="{{ asset('Sneat-Admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('Sneat-Admin/assets/vendor/js/menu.js') }}"></script>

    <!-- Datatables JS -->

    <script src="{{ asset('Sneat-Admin/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('Sneat-Admin/assets/js/tables-datatables-advanced.js') }}"></script>
    <script src="{{ asset('Sneat-Admin/assets/js/ventas-detalles.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('Sneat-Admin/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->

    <script src="{{ asset('Sneat-Admin/assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('Sneat-Admin/assets/js/dashboards-analytics.js') }}"></script>

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script src="{{ asset('Sneat-Admin/assets/js/ui-toasts.js') }}"></script>
    <script src="{{ asset('Sneat-Admin/assets/js/delete-modal.js') }}"></script>
    <script src="{{ asset('Sneat-Admin/assets/js/edit-modal.js') }}"></script>
    {{-- <script src="{{ asset('Sneat-Admin/assets/js/categorias/edit-modal.js') }}"></script> --}}
    <script src="{{ asset('Sneat-Admin/assets/js/usuarios/edit-modal.js') }}"></script>
    {{-- <script src="{{ asset('Sneat-Admin/assets/js/marcas/edit-modal.js') }}"></script> --}}
    {{-- <script src="{{ asset('Sneat-Admin/assets/js/productos/edit-modal.js') }}"></script> --}}
    <script src="{{ asset('Sneat-Admin/assets/js/preventModalSubmit.js') }}"></script>

    {{-- Tom-select --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <x-alerts.toasts />

    @stack('scripts')
    @yield('page-script')
</body>

</html>
