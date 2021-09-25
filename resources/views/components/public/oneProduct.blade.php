<div class="product--data">
    <div class="product--images">
        <div id="slider_product" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                @for($i = 0 ; $i < count($product['images']) ; $i++)
                    <li data-target="#slider_product" data-slide-to="{{$i}}" @if( $i == 0 ) class="active" @endif></li>
                @endfor
            </ol>
            <div class="carousel-inner">
                @for($i = 0 ; $i < count($product['images']) ; $i++)
                    <div class="carousel-item @if( $i == 0 ) active @endif">
                        <img src="{{ $product['images'][$i]['base64'] ?? $product['images'][$i]['url'] }}" class="w-100" onerror="this.src='{{ $no_img }}'" alt="{{ $product["name"] }}" srcset="">
                    </div>
                @endfor
            </div>
        </div>
    </div>
    <div>
        <h3 class="product--code">{{ $product["code"] }}</h3>
        <h1 class="product--color product--title">{{ $product["name"] }}</h1>
        <table class="table mt-4 mb-0">
            <tbody>
                <tr>
                    <td class="product--color">Código</td>
                    <td>{{ $product["code"] }}</td>
                </tr>
                <tr>
                    <td class="product--color">Cantidad Envasada</td>
                    <td>{{ $product["cantminvta"] == 1 ? $product["cantminvta"] . " unidad" : $product["cantminvta"] . " unidades"}}</td>
                </tr>
                @isset($product["brand"])
                <tr>
                    <td class="product--color">Marca</td>
                    <td>{{ $product["brand"] }}</td>
                </tr>
                @endisset
                @isset($product["modelo_anio"])
                <tr>
                    <td class="product--color">Modelo</td>
                    <td>{{ $product["modelo_anio"] }}</td>
                </tr>
                @endisset
            </tbody>
        </table>
    </div>
</div>