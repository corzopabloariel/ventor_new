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
                                @for($i = 0 ; $i < count($data["elements"]["product"]['images']) ; $i++)
                                    <li data-target="#slider_product" data-slide-to="{{$i}}" @if( $i == 0 ) class="active" @endif></li>
                                @endfor
                            </ol>
                            <div class="carousel-inner">
                                @for($i = 0 ; $i < count($data["elements"]["product"]['images']) ; $i++)
                                    <div class="carousel-item @if( $i == 0 ) active @endif">
                                        <img src="{{ $data['elements']['product']['images'][$i] }}" class="w-100" onerror="this.src='{{ $no_img }}'" alt="{{ $data["elements"]["product"]["name"] }}" srcset="">
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="product--code">{{ $data["elements"]["product"]["code"] }}</h3>
                        <h1 class="product--color product--title">{{ $data["elements"]["product"]["name"] }}</h1>
                        <table class="table mt-4 mb-0">
                            <tbody>
                                <tr>
                                    <td class="product--color">Código</td>
                                    <td>{{ $data["elements"]["product"]["code"] }}</td>
                                </tr>
                                <tr>
                                    <td class="product--color">Cantidad Envasada</td>
                                    <td>{{ $data["elements"]["product"]["cantminvta"] == 1 ? $data["elements"]["product"]["cantminvta"] . " unidad" : $data["elements"]["product"]["cantminvta"] . " unidades"}}</td>
                                </tr>
                                @isset($data["elements"]["product"]["brand"])
                                <tr>
                                    <td class="product--color">Marca</td>
                                    <td>{{ $data["elements"]["product"]["brand"] }}</td>
                                </tr>
                                @endisset
                                @isset($data["elements"]["product"]["modelo_anio"])
                                <tr>
                                    <td class="product--color">Modelo</td>
                                    <td>{{ $data["elements"]["product"]["modelo_anio"] }}</td>
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