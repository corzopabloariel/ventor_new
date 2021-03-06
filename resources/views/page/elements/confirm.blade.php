@push('styles')
    <link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alertifyjs/themes/bootstrap.min.css') }}" rel="stylesheet">
@endpush
@push('js')
    <script src="{{ asset('js/alertify.js') }}"></script>
@endpush
<section>
    <div class="wrapper confirm">
        <div class="container">
            <div class="jumbotron">
                <h1 class="display-4">Pedido finalizado</h1>
                @if (isset($data["order"]["client"]))
                <p class="lead">El pedido del cliente <strong>{{$data["order"]["client"]["razon_social"]}}</strong> fue enviado con éxito.</p>
                @else
                <p class="lead">El pedido fue enviado con éxito.</p>
                @endif
                <hr class="my-4">
                <form action="{{ route('order.pdf') }}" target="_blank" id="createPdfOrder" method="post">
                    @csrf
                    <button class="btn btn-lg" type="submit">Descargar información</button>
                </form>
            </div>
        </div>
    </div>
</section>