<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title ?? 'Pedido'}}</title>
    <style>
        @page {
        }
        * {
            margin: 0;
            padding: 0;
        }
        body {
            padding: 1cm 1cm;
            font-family: Arial;
            font-size: 14px;
            line-height: 18px;
        }
        .page-footer {
            position: fixed;
            bottom: 0cm;
            right: 1cm;
            width: 100%;
            text-align: center;
            height: 90px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            color: #212529;
        }
        table td, table th {
            padding: .45rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
            text-align: left;
            vertical-align: middle;
        }
        table thead th {
            vertical-align: bottom;
            text-transform: uppercase;
            white-space: nowrap;
            background-color: #000;
            color: #fff;
        }
        .price {
            white-space: nowrap;
        }
        th.image {
            width: 60px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .total {
            font-size: 18px;
            float: right;
            padding: .45rem;
        }
        .data {
            margin-bottom: 1em;
        }
        .bg-dark {
            background-color: #000;
            color: #fff;
        }
        header {
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding: 10px 0;
            height: 80px;
        }
        hr {
            border: none;
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding-top: 10px;
        }
        .obs:not(:empty) {
            margin-top: 1em;
        }
        .ventor {
            float: right;
        }
        .ventor a {
            text-decoration: none;
            color: inherit;
        }
        .ventor--data {
            padding: 0;
            margin-bottom: 0;
            list-style: none;
        }
        .ventor--data p {
            margin: 0;
        }
        .ventor--data > li {
        }
        .ventor--data .data {
        }
        .logo {
        }
        .logo img {
            width: 255px;
        }
    </style>
</head>
<body>
    <header>
        <div class="ventor">
            <ul class="ventor--data">
                <li>{!! $ventor->addressPrint() !!}</li>
                <li>{!! $ventor->phonesPrint() !!}</li>
            </ul>
        </div>
        <div class="logo">
            <img class="header--logo" src="https://ventor.com.ar/images/empresa_images/1575909002_logo.png" alt="{{ config('app.name') }}" srcset="">
        </div>
    </header>
    <section>
        <div class="data" style="min-height: 40px;">
            @isset($transport["description"]) 
            <div>
                <strong>Transporte:</strong> {{ $transport["description"] }}@isset($transport["address"]) ({{ $transport["address"] }}) @endisset 
            </div>
            @endisset
            @isset($seller)
                <div class="transport">
                    <strong>Vendedor:</strong> {{ $seller["nombre"] }}
                    @if (!empty($seller["telefono"]))
                    <br/><strong>Teléfono:</strong> {{ $seller["telefono"] }}
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
                $total += $product['price']['total'];
                @endphp
                <tr>
                    <td style="width: 120px;"></td>
                    <td style="width: 350px; color: {{$product['color']}}">{{ $product['name'] }}</td>
                    <td class="text-right price">$ {{ number_format($product['price']['unit'], 2, ',', '.') }}</td>
                    <td class="text-center">{{ $product['quantity'] }}</td>
                    <td class="text-right price" style="border-left: 1px solid #dee2e6;">$ {{ number_format($product['price']['total'], 2, ",", ".") }}</td>
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
</body>
</html>