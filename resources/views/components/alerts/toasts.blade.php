{{-- SUCCESS --}}
@if(session('success'))
<div class="bs-toast toast toast-placement-ex m-2 fade bg-success top-0 end-0 hide"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
    data-bs-delay="3000"
    id="successToast">

    <div class="toast-header">
        <i class="icon-base bx bx-check-circle me-2"></i>

        <div class="me-auto fw-semibold">
            Éxito
        </div>

        <button type="button"
            class="btn-close"
            data-bs-dismiss="toast">
        </button>
    </div>

    <div class="toast-body">
        {{ session('success') }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toast = new bootstrap.Toast(
            document.getElementById('successToast')
        );

        toast.show();
    });
</script>
@endif


{{-- ERROR --}}
@if(session('error'))
<div class="bs-toast toast toast-placement-ex m-2 fade bg-danger top-0 end-0 hide"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
    data-bs-delay="4000"
    id="errorToast">

    <div class="toast-header">
        <i class="bx bx-x-circle me-2 text-danger"></i>

        <div class="me-auto fw-semibold">
            Error
        </div>

        <button type="button"
            class="btn-close"
            data-bs-dismiss="toast">
        </button>
    </div>

    <div class="toast-body">
        {{ session('error') }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toast = new bootstrap.Toast(
            document.getElementById('errorToast')
        );

        toast.show();
    });
</script>
@endif


{{-- WARNING --}}
@if(session('warning'))
<div class="bs-toast toast toast-placement-ex m-2 fade bg-warning top-0 end-0 hide"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
    data-bs-delay="4000"
    id="warningToast">

    <div class="toast-header">
        <i class="bx bx-error me-2 text-warning"></i>

        <div class="me-auto fw-semibold">
            Advertencia
        </div>

        <button type="button"
            class="btn-close"
            data-bs-dismiss="toast">
        </button>
    </div>

    <div class="toast-body">
        {{ session('warning') }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toast = new bootstrap.Toast(
            document.getElementById('warningToast')
        );

        toast.show();
    });
</script>
@endif


{{-- INFO --}}
@if(session('info'))
<div class="bs-toast toast toast-placement-ex m-2 fade bg-info top-0 end-0 hide"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
    data-bs-delay="4000"
    id="infoToast">

    <div class="toast-header">
        <i class="icon-base bx bx-info-circle me-2"></i>

        <div class="me-auto fw-semibold">
            Información
        </div>

        <button type="button"
            class="btn-close"
            data-bs-dismiss="toast">
        </button>
    </div>

    <div class="toast-body">
        {{ session('info') }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toast = new bootstrap.Toast(
            document.getElementById('infoToast')
        );

        toast.show();
    });
</script>
@endif

{{-- TOAST DINÁMICO PARA JAVASCRIPT (ESCANER) --}}
<div class="bs-toast toast toast-placement-ex m-2 fade bg-success top-0 end-0 hide"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
    data-bs-delay="2500"
    id="scanSuccessToast">

    <div class="toast-header">
        <i class="bx bx-check-circle me-2 text-success"></i>
        <div class="me-auto fw-semibold">Producto Escaneado</div>
        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
    </div>

    <div class="toast-body" id="scanToastBody">
        <!-- El contenido se inyectará dinámicamente desde JS -->
    </div>
</div>