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
    <br/>
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
    <br/>
    <table class="table">
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
                    <hr/>
                    <p style="margin: 0;">{{ $product["product"]["code"] }}</p>
                    <p style="margin: 0;"><a href="{{ route('product', ['product' => $product["product"]["name_slug"]]) }}" target="_blank">{{ $product["product"]["name"] }}</a></p>
                    <p style="margin: 0; text-align: right">{{ $product["product"]["price"] }} x {{ $product["quantity"] }} = $ {{ number_format($price, 2, ",", ".") }}</p>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th class="bg-dark">
                    <div class="total" style="font-size: 30px">
                        <span style="float: left;">TOTAL</span>
                        <span style="float: right;">$ {{ number_format($total, 2, ",", ".") }}</span>
                    </div>
                </th>
            </tr>
        </tfoot>
    </table>
    <div class="obs">{{ $order->obs }}</div>
</section>