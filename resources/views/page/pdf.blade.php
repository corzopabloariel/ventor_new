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
    $clear = 0;
    $page = 1;
    $headerFooter = "a-";
    @endphp
    @for($i = 0; $i < count($products); $i ++)
        @php
        $clear ++;
        $product = $products[$i];
        @endphp
        @if ($i == 0)
            <div class="page">
            <div class="page-header" style="background-image: url(http://staticbcp.ventor.com.ar/img/{{$headerFooter}}1.png);background-size: 100%;background-repeat: no-repeat;background-position: center center;"></div>
            <div class="row">
        @elseif ($i != 0 && $i % 18 == 0)
        @php
        $page = $page == 1 ? 2 : 1;
        $headerFooter = $page == 1 ? "a-" : "b-";
        @endphp
        </div>
        <div class="page-footer text-center" style="background-image: url(http://staticbcp.ventor.com.ar/img/{{$headerFooter}}3.png);background-size: 100%;background-repeat: no-repeat;background-position: center bottom;">
            <p style="font-size: 9px;height: 35px;">PRECIOS VIGENTES AL {{ $date }} - LOS PRECIOS INCLUYEN LA BONIFICACIÓN DEL 15% POR PAGO A 30 DÍAS FF - NO INCLUYEN IVA</p>
        </div>
        <div class="break"></div>
        </div>
        <div class="page">
        <div class="page-header" style="background-image: url(http://staticbcp.ventor.com.ar/img/{{$headerFooter}}1.png);background-size: 100%;background-repeat: no-repeat;background-position: center center;"></div>
        <div class="row">
        @endif
        <div style="float: left; width: 33%; margin-bottom:5px; @if($clear != 3) margin-right:.5% @endif">
            <p class="code" style="background-color: {{ $colors[$product['subpart']['code']] ?? '#dedede' }}; color: #fff;border-top-right-radius: .6em;border-top-left-radius: .6em;padding: .6em;text-align: right;margin:0;line-height: 0.7em;"><span style="float: left;font-weight: 600;">{{ $product['price'] }}</span>{{ $product['code'] }}</p>
            <div style="background-image: url({{ $product['images'][0] ?? '' }}); background-position: center center; background-repeat: no-repeat; border: 1px solid;border-bottom-right-radius: .6em;border-bottom-left-radius: .6em;margin-top: -1px; border-color: {{ $colors[$product['subpart']['code']] ?? '#dedede' }}; background-size: auto 100%;">
                <div style="padding: .6em;background-color: rgba(255, 255, 255, .4);border-bottom-right-radius: .6em;">
                    <div style="height: 105px;font-size: 11px;line-height: 13px; color: #333">{{ $product['name'] }}</div>
                </div>
            </div>
        </div>
        @if ($clear == 3)
            @php
            $clear = 0;
            @endphp
            <div style="clear: left;"></div>
        @endif
    @endfor
    </div>
    <div class="page-footer" style="background-image: url(http://staticbcp.ventor.com.ar/img/{{$headerFooter}}3.png);background-size: 100%;background-repeat: no-repeat;background-position: center bottom;">
        <p style="font-size: 9px;height: 35px;">PRECIOS VIGENTES AL {{ $date }} - LOS PRECIOS INCLUYEN LA BONIFICACIÓN DEL 15% POR PAGO A 30 DÍAS FF - NO INCLUYEN IVA</p>
    </div>
    </div>
</body>
</html>