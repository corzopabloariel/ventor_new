@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alertifyjs/themes/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mobile/product.css') . '?t=' . time() }}" rel="stylesheet">
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/mobile/product.js') . '?t=' . time() }}"></script>
@endpush
<section>
    <div class="confirm">
        <div class="container">
            <div class="jumbotron bg-white shadow-sm">
                <h1 class="display-4">Pedido finalizado</h1>
                @if (!auth()->guard('web')->user()->isShowData())
                    @if (isset($data["order"]["client"]))
                        <p class="lead">El pedido del cliente <strong>{{$data["order"]["client"]["razon_social"]}}</strong> fue enviado con éxito.</p>
                    @else
                        <p class="lead">El pedido fue enviado con éxito.</p>
                    @endif
                @else
                <p class="lead">El pedido fue enviado con éxito, en breve recibirá noticias.</p>
                @endif
                <hr class="my-4">
                <form action="{{ route('order.pdf') }}" target="blank" onsubmit="createPdfOrder(this)" method="post">
                    @csrf
                    <button class="btn btn-primary btn-lg" type="submit">Descargar información</button>
                </form>
            </div>
        </div>
    </div>
</section>