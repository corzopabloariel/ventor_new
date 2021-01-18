@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alertifyjs/themes/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/page/productos.css') . '?t=' . time() }}" rel="stylesheet">
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
    <script src="{{ asset('js/page/producto.js') . '?t=' . time() }}"></script>
@endpush
<section>
    <div class="checkout">
        <div class="container-fluid">
            <ol class="breadcrumb bg-transparent p-0 border-0">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Mis pedidos</li>
            </ol>
            <div class="container--table">
                <div class="table-responsive">
                    <table class="table table-striped table-borderless">
                        <thead class="thead-dark">
                            <tr>
                                <th>Fecha</th>
                                @if(auth()->guard('web')->user()->role != "USR")
                                <th class="">Cliente</th>
                                @endif
                                <th>Transporte</th>
                                <th>Vendedor</th>
                                <th>Cant. Productos</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data["orders"] AS $order)
                                <tr>
                                    <td class="text-center">{{ date("d/m/Y H:i:s", strtotime($order->created_at)) }}</td>
                                    @if(auth()->guard('web')->user()->role != "USR")
                                    <td>
                                        @isset($order->client)
                                            {{ $order->client["razon_social"] }} ({{$order->client["nrocta"]}})</td>
                                        @endisset
                                    @endif
                                    <td>{{ $order->transport["description"] }} ({{ $order->transport["address"] }})</td>
                                    <td>{{ $order->seller["nombre"] ?? "" }}</td>
                                    <td class="text-center">{{ count($order->products) }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('order.pdf') }}" target="_blank" method="post">
                                            @csrf
                                            <input type="hidden" name="order_id__pedidos" value="{{ $order->_id }}">
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-file-pdf"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <h3 class="text-center">Sin información</h3>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="main--footer">
                <div class="table-responsive d-flex justify-content-center">
                    {{ $data["orders"]->links() }}
                </div>
            </div>
        </div>
    </div>
</section>