<section>
    <div class="data" style="min-height: 40px;">
        @isset($transport["description"]) 
        <div>
            <strong>Transporte:</strong> {{ $transport["description"] }}@isset($transport["address"]) ({{ $transport["address"] }}) @endisset 
        </div>
        @endisset
        @isset($seller)
            <div class="transport">
                <strong>Vendedor:</strong> {{ $seller["name"] }}
                @if (!empty($seller["phone"]))
                <br/><strong>Teléfono:</strong> {{ $seller["phone"] }}
                @endif
                @if (!empty($seller["email"]))
                <br/><strong>Email:</strong> {{ $seller["email"] }}
                @endif
            </div>
        @endisset
    </div>
    <table>
        <thead>
            <tr>
                <th></th>
                <th>Producto</th>
                <th></th>
                <th></th>
                <th class="text-right">subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
            $total = 0;
            @endphp
            @foreach($products AS $product)
            @php
            $total += $product['price'] * $product['quantity'];
            @endphp
            <tr>
                <td style="width: 120px;"></td>
                <td style="width: 350px; color: {{$product['product']['family']['color']['color']}}">{{ $product['product']['name'] }}</td>
                <td class="text-right price">$ {{ number_format($product['price'], 2, ',', '.') }}</td>
                <td class="text-center">{{ $product['quantity'] }}</td>
                <td class="text-right price" style="border-left: 1px solid #dee2e6;">$ {{ number_format($product['price'] * $product['quantity'], 2, ",", ".") }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if (!empty($obs))
    <div class="obs">
        <hr/>
        <strong>OBSERVACIONES:</strong> {{ $obs }}
    </div>
    @endif
    <div class="page-footer">
        <div class="total bg-dark">
            <strong>TOTAL:</strong> $ {{ number_format($total, 2, ",", ".") }}
        </div>
    </div>
</section>