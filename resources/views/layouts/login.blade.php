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
    <link rel="icon" type="image/x-icon" href="Sneat-Admin/assets/img/favicon/favicon.ico" />

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

    <!-- Vendors CSS -->

    <link rel="stylesheet"
        href="{{ asset('Sneat-Admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <link rel="stylesheet" href="{{ asset('Sneat-Admin/assets/vendor/css/pages/page-auth.css') }}">

    <!-- endbuild -->

    <link rel="stylesheet" href="{{ asset('Sneat-Admin/assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <link rel="stylesheet" href="{{ asset('Sneat-Admin/assets/vendor/libs/@form-validation/form-validation.css') }}" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('Sneat-Admin/assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{ asset('Sneat-Admin/assets/js/config.js') }}"></script>
</head>

<body>

    @yield('contenido')

    <!-- Core JS -->

    <script src="{{ asset('Sneat-Admin/assets/vendor/libs/jquery/jquery.js') }}"></script>

    <script src="{{ asset('Sneat-Admin/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('Sneat-Admin/assets/vendor/js/bootstrap.js') }}"></script>

    {{-- <script src="{{ asset('Snet-Admin/assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script> --}}
    <script src="{{ asset('Sneat-Admin/assets/vendor/libs/pickr/pickr.js') }}"></script>

    <script src="{{ asset('Sneat-Admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    {{-- <script src="{{ asset('Sneat-Admin/assets/vendor/libs/hammer/hammer.js') }}"></script>
        
    <script src="{{ asset('Sneat-Admin/assets/vendor/libs/i18n/i18n.js') }}"></script> --}}

    <script src="{{ asset('Sneat-Admin/assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('Sneat-Admin/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->

    {{-- <script src="Sneat-Admin/assets/vendor/libs/select2/select2.js"></script>
    <script src="Sneat-Admin/assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
    <script src="Sneat-Admin/assets/vendor/libs/moment/moment.js"></script>
    <script src="Sneat-Admin/assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="Sneat-Admin/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="Sneat-Admin/assets/vendor/libs/tagify/tagify.js"></script> --}}
    <script src="{{ asset('Sneat-Admin/assets/vendor/libs/@form-validation/popular.js') }}"></script>
    <script src="{{ asset('Sneat-Admin/assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
    <script src="{{ asset('Sneat-Admin/assets/vendor/libs/@form-validation/auto-focus.js') }}"></script>

    <script src="{{ asset('Sneat-Admin/assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('Sneat-Admin/assets/js/dashboards-analytics.js') }}"></script>

    <script src="{{ asset('Sneat-Admin/assets/js/form-validation.js') }}"></script>

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const forms = document.querySelectorAll('.needs-validation');

            Array.from(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {

                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');

                }, false);
            });

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Si corre en modo PWA o dentro del APK
            if (window.matchMedia('(display-mode: standalone)').matches) {
                const inputRemember = document.createElement('input');
                inputRemember.type = 'hidden';
                inputRemember.name = 'remember';
                inputRemember.value = '1'; // Fuerza el login persistente de 30 días
                document.getElementById('loginForm').appendChild(inputRemember);
            }
        });
    </script>
</body>

</html>
