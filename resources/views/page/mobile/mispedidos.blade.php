@push('styles')
    <link href="{{ asset('css/mobile/mispedidos.css') . '?t=' . time() }}" rel="stylesheet">
@endpush
<section>
    <div class="pedidos">
        <div class="container-fluid">
            <div class="container--table">
                <h2 class="pedido__title">Pedidos</h2>
                <div class="pedido__list shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless">
                            <thead class="thead-dark">
                                <tr>
                                    <th>FECHA</th>
                                    @if(auth()->guard('web')->user()->role != "USR")
                                    <th class="">CLIENTE</th>
                                    @endif
                                    <th>TRANSPORTE</th>
                                    <th>VENDEDOR</th>
                                    <th class="pedido__importe">CANT. PRODUCTO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data["orders"] AS $order)
                                    <tr>
                                        <td class="text-center pedido__importe">{{ date("d/m/Y H:i:s", strtotime($order->created_at)) }}</td>
                                        @if(auth()->guard('web')->user()->role != "USR")
                                        <td class="pedido__importe">
                                            @isset($order->client)
                                                {{ $order->client["razon_social"] }} ({{$order->client["nrocta"]}})</td>
                                            @endisset
                                        @endif
                                        <td>@isset($order->transport["description"]) {{ $order->transport["description"] }} ({{ $order->transport["address"] }}) @endisset</td>
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
            </div>
            <div class="mt-3">
                <div class="table-responsive d-flex justify-content-center">
                    {{ $data["orders"]->links() }}
                </div>
            </div>
        </div>
    </div>
</section>