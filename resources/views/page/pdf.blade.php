<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} productos</title>
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web:200,300,400,400i,600,700,900&display=swap" rel="stylesheet">
    <link href="{{ asset('css/page/pdf.css') . '?t=' . time() }}" rel="stylesheet" type="text/css" />
</head>
<body>
    @php
    $date = date("d/m/Y");
    @endphp
    @for($i = 0; $i < count($products); $i ++)
        @php
        $product = $products[$i];
        @endphp
        @if ($i == 0)
            <div class="page">
            <div class="page-header"></div>
            <div class="row">
        @elseif ($i != 0 && $i % 15 == 0)
        </div>
        <div class="page-footer text-center">
            PRECIOS VIGENTES AL {{ $date }} - LOS PRECIOS INCLUYEN LA BONIFICACIÓN DEL 15% POR PAGO A 30 DÍAS FF - NO INCLUYEN IVA
            <div></div>
        </div>
        <div class="break"></div>
        </div>
        <div class="page">
        <div class="page-header"></div>
        <div class="row">
        @endif
        <div>
            <p class="code" style="--color: {{ $colors[$product->subparte['code']] ?? '#dedede' }}">{{ $product->stmpdh_art }}</p>
            <div class="download" style="background-image: url({{ $product->images(0, $no_img)[0] }}); background-position: center center; background-repeat: no-repeat; border-color: {{ $colors[$product->subparte['code']] ?? '#dedede' }}; background-size: cover;">
                <div class="download-data">
                    <p class="name">{{ $product->stmpdh_tex }}</p>
                    <p class="price">{{ $product->price() }}</p>
                </div>
            </div>
        </div>
    @endfor
    </div>
    <div class="page-footer">
        PRECIOS VIGENTES AL {{ $date }} - LOS PRECIOS INCLUYEN LA BONIFICACIÓN DEL 15% POR PAGO A 30 DÍAS FF - NO INCLUYEN IVA
        <div></div>
    </div>
    </div>
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