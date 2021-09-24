<section>
    <div class="wrapper container__product">
        <div class="lateral">
            <div class="container-fluid">
                @include("page.elements.__lateral", ['elements' => $data["lateral"]])
            </div>
        </div>
        <div class="main">
            <div class="container-fluid">
                @include("page.elements.__breadcrumb")
                <div class="product--data">
                    <div class="product--images">
                        <div id="slider_product" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                @for($i = 0 ; $i < count($data["elements"]["products"][0]['images']) ; $i++)
                                    <li data-target="#slider_product" data-slide-to="{{$i}}" @if( $i == 0 ) class="active" @endif></li>
                                @endfor
                            </ol>
                            <div class="carousel-inner">
                                @for($i = 0 ; $i < count($data["elements"]["products"][0]['images']) ; $i++)
                                    <div class="carousel-item @if( $i == 0 ) active @endif">
                                        <img src="{{ $data['elements']['products'][0]['images'][$i]['base64'] ?? $data['elements']['products'][0]['images'][$i]['url'] }}" class="w-100" onerror="this.src='{{ $no_img }}'" alt="{{ $data["elements"]["products"][0]["name"] }}" srcset="">
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="product--code">{{ $data["elements"]["products"][0]["code"] }}</h3>
                        <h1 class="product--color product--title">{{ $data["elements"]["products"][0]["name"] }}</h1>
                        <table class="table mt-4 mb-0">
                            <tbody>
                                <tr>
                                    <td class="product--color">Código</td>
                                    <td>{{ $data["elements"]["products"][0]["code"] }}</td>
                                </tr>
                                <tr>
                                    <td class="product--color">Cantidad Envasada</td>
                                    <td>{{ $data["elements"]["products"][0]["cantminvta"] == 1 ? $data["elements"]["products"][0]["cantminvta"] . " unidad" : $data["elements"]["products"][0]["cantminvta"] . " unidades"}}</td>
                                </tr>
                                @isset($data["elements"]["products"][0]["brand"])
                                <tr>
                                    <td class="product--color">Marca</td>
                                    <td>{{ $data["elements"]["products"][0]["brand"] }}</td>
                                </tr>
                                @endisset
                                @isset($data["elements"]["products"][0]["modelo_anio"])
                                <tr>
                                    <td class="product--color">Modelo</td>
                                    <td>{{ $data["elements"]["products"][0]["modelo_anio"] }}</td>
                                </tr>
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>