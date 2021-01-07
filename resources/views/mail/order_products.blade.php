<section>
    <div class="data">
        <div class="transport">
            <strong>Transporte:</strong> {{ $order->transport["description"] }} ({{ $order->transport["address"] }})
        </div>
        <div class="date">
            {{ date("d/m/Y H:i:s", strtotime($order->created_at)) }}
        </div>
    </div>
    @isset($order->seller)
    <div class="data">
        <div class="transport">
            <strong>Vendedor:</strong> {{ $order->seller["nombre"] }}
            @if (!empty($order->seller["telefono"]))
            <br/><strong>Teléfono:</strong> {{ $order->seller["telefono"] }}
            @endif
            @if (!empty($order->seller["email"]))
            <br/><strong>Email:</strong> {{ $order->seller["email"] }}
            @endif
        </div>
    </div>
    @endisset
    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>cantidad</th>
                <th>p. unitario</th>
                <th>subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
            $total = 0;
            @endphp
            @foreach($order->products AS $k => $product)
            @php
            $price = $product["price"] * $product["quantity"];
            $total += $price
            @endphp
            <tr>
                <td>
                    <p>{{ $product["product"]["stmpdh_art"] }}</p>
                    <p><a href="{{ route('product', ['product' => $product["product"]["name_slug"]]) }}" target="_blank">{{ $product["product"]["stmpdh_tex"] }}</a></p>
                </td>
                <td class="text-center">{{ $product["quantity"] }}</td>
                <td class="text-right price">$ {{ number_format($product["price"], 2, ",", ".") }}</td>
                <td class="text-right price">$ {{ number_format($price, 2, ",", ".") }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2"></th>
                <th colspan="3" class="bg-dark">
                    <div class="total">
                        <span>TOTAL</span>
                        <span>$ {{ number_format($total, 2, ",", ".") }}</span>
                    </div>
                </th>
            </tr>
        </tfoot>
    </table>
    <div class="obs">{{ $order->obs }}</div>
</section>