<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $order->title ?? 'Pedido' }}</title>
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web:200,300,400,400i,600,700,900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9ab0ab8372.js" crossorigin="anonymous"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Titillium Web', sans-serif;
            font-size: 14px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }
        table td, table th {
            padding: .75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
            text-align: left;
            vertical-align: middle;
        }
        table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            text-transform: uppercase;
            white-space: nowrap;
            background-color: #343a40;
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
            display: flex;
            justify-content: space-between!important;
            font-size: 25px;
        }
        .data {
            display: flex;
            justify-content: space-between!important;
            margin-bottom: 1em;
        }
        .bg-dark {
            background-color: #343a40;
            color: #fff;
        }
        header {
            margin-bottom: 2em;
            border-bottom: 2px solid #343a40;
            padding: 10px 0;
            display: grid;
            grid-template-columns: 1fr auto;
            column-gap: 20px;
        }
        .obs:not(:empty) {
            margin-top: 1em;
        }
        .obs:not(:empty)::before {
            content: "Observaciones";
            color: #4a4a4a;
            display: block;
            text-transform: uppercase;
            font-weight: bold;
        }
        .ventor a {
            text-decoration: none;
            color: inherit;
        }
        .ventor--data {
            padding: 0;
            display: grid;
            grid-template-rows: auto;
            row-gap: 10px;
            margin-bottom: 0;
        }
        .ventor--data p {
            margin: 0;
        }
        .ventor--data > li {
            display: grid;
            grid-template-columns: 20px auto;
            column-gap: 10px;
        }
        .ventor--data .data {
            display: grid;
            grid-template-rows: auto;
            row-gap: 5px;
        }
        .logo {
            display: grid;
            align-items: center!important;
        }
        .logo img {
            width: 255px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img class="header--logo" src="{{ asset($ventor->images['logo']['i']) }}" alt="{{ config('app.name') }}" srcset="">
        </div>
        <div class="ventor">
            <ul class="ventor--data">
                <li>{!! $ventor->addressPrint() !!}</li>
                <li>{!! $ventor->phonesPrint() !!}</li>
            </ul>
        </div>
    </header>
    <section>
        <div class="data">
            @isset($order->transport["description"]) 
            <div class="transport">
                <strong>Transporte:</strong> {{ $order->transport["description"] }}@isset($order->transport["address"]) ({{ $order->transport["address"] }}) @endisset 
            </div>
            @endisset
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
        <table>
            <thead>
                <tr>
                    <th class="image"></th>
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
                $price = $product["product"]["priceNumber"] * $product["quantity"];
                $total += $price;

                $bg = $product["product"]["images"][0] ?? '';
                if (!empty($bg)) {

                    $bg = str_replace(' ', '%20', $bg);
                    $type = pathinfo($bg, PATHINFO_EXTENSION);// Por ahora son todos JPG
                    $bg = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($bg));

                }
                @endphp
                <tr>
                    <td><img onerror="this.src='{{$no_img}}'" src="{{ $bg }}" style="width: 100%"></td>
                    <td>
                        <p>{{ $product["product"]["name"] }}</p>
                    </td>
                    <td class="text-center">{{ $product["quantity"] }}</td>
                    <td class="text-right price">{{ $product["product"]["price"] }}</td>
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
    <script>
        window.addEventListener("load", function(event) {
            window.print();
        });
        window.onafterprint = function(event) {
            window.close();
        };
    </script>
</body>
</html>