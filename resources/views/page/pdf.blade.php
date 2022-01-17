<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} productos</title>
    <style>
        @page {
            margin: 1cm 1cm;
            font-family: Arial;
        }
        * {
            margin: 0;
            padding: 0;
        }

        .break {
            display:block;
            page-break-before:always;
        }
        .page-header {
            position: fixed;
            top: 0cm;
            left: 1cm;
            right: 1cm;
        }
        .page-footer {
            position: fixed;
            bottom: 0cm;
            left: 1cm;
            right: 1cm;
            text-align: center;
            height: 90px;
        }
        .row {
            margin-top: 89px;
            margin-left: 1cm;
            margin-right: 1cm;
        }
        .page-header, .page-header-space {
            height: 89px;
        } 
        .page-footer, .page-footer-space {
            height: 55px;
        }
    </style>
</head>
<body>
    @php
    $date = date("d/m/Y");
    $even = true;
    @endphp
    @for($page = 0; $page < count($products); $page ++)
        @php
        $headerFooter = $even ? "a-" : "b-";
        @endphp
        <div style="clear: left;"></div>
        <div class="page">
            <div
                class="page-header"
                style="background-image: url(http://staticbcp.ventor.com.ar/img/{{$headerFooter}}1.png);background-size: 100%;background-repeat: no-repeat;background-position: center center;"
            ></div>
            <div class="row">
                {!! implode('', $products[$page]) !!}
                <div style="clear: left;"></div>
            </div>
            <div
                class="page-footer text-center"
                style="background-image: url(http://staticbcp.ventor.com.ar/img/{{$headerFooter}}3.png);background-size: 100%;background-repeat: no-repeat;background-position: center bottom;"
            >
                <p style="font-size: 9px;height: 35px;">PRECIOS VIGENTES AL {{ $date }} - LOS PRECIOS INCLUYEN LA BONIFICACIÓN DEL 15% POR PAGO A 30 DÍAS FF - NO INCLUYEN IVA</p>
            </div>
            <div class="break"></div>
        </div>
        @php
        $even = !$even;
        @endphp
    @endfor
</body>
</html>