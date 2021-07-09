@push('styles')
    <link href="{{ asset('css/alertifyjs/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/alertifyjs/themes/bootstrap.min.css') }}" rel="stylesheet">
@endpush
@push('js')
    <script src="{{ asset('js/alertify.js') }}"></script>
@endpush
<section>
    <div class="wrapper checkout">
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
                                    <td>@isset($order->transport["description"]) {{ $order->transport["description"] }}@isset($order->transport["address"]) ({{ $order->transport["address"] }}) @endisset @endisset</td>
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