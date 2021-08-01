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
                <p class="lead">{!!$data['message']!!}</p>
                <div class="checkout">
                    <div class="table-responsive container--table">
                        <table class="table table-striped table-borderless">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="th--image"></th>
                                    <th class="th--name">producto</th>
                                    <th class="th--precio">p. unitario</th>
                                    <th class="th--stock">cantidad</th>
                                    <th class="th--precio">subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                {!! $data['tbody'] !!}
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr class="my-4">
                <form action="{{ route('order.pdf') }}" target="_blank" id="createPdfOrder" method="post">
                    @csrf
                    <button class="btn btn-lg" type="submit">Descargar información</button>
                    <a class="btn btn-lg" style="background-color: #3490dc !important;border-color: #3490dc !important;" href="{{route('order')}}">Volver a pedidos</a>
                </form>
            </div>
        </div>
    </div>
</section>