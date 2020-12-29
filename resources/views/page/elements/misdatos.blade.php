@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alertifyjs/themes/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/page/productos.css') }}" rel="stylesheet">
    <style>
        .container--table {
            min-height: 300px;
        }
    </style>
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/page/producto.js') }}"></script>
@endpush
<section>
    <div class="checkout">
        <div class="container-fluid">
            <ol class="breadcrumb bg-transparent p-0 border-0">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Mis pedidos</li>
            </ol>
        </div>
    </div>
</section>