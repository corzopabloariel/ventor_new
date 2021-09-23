@push('js')
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/color.js') }}"></script>
    <script src="{{ asset('js/solver.js') }}"></script>
@endpush
@includeIf('page.mobile.__filter', ['elements' => $data["lateral"]])
<section>
    <div class="product">
        <div class="container-fluid">
            <div class="product__container product__container--filter shadow-sm text-truncate">
                <button id="btn-filter" type="button">
                    <i class="fas fa-filter"></i>
                    @if(isset($data["elements"]["part"]) || isset($data["elements"]["subpart"]))
                    Parte y subparte: <span class="text-uppercase">{{$data["elements"]["part"]["name"]}}</span>
                    @isset($data["elements"]["subpart"])
                    | {{ $data["elements"]["subpart"]["name"] }}
                    @endisset
                    @else
                    filtrar
                    @endif
                </button>
            </div>
            <div class="product__container shadow-sm">
                <h3 class="product--code">{{ $data["elements"]["products"][0]["code"] }}</h3>
                @php
                $data["elements"]["products"][0]["name"] = str_replace('&nbsp;', ' ', htmlentities($data["elements"]["products"][0]["name"]));
                $data["elements"]["products"][0]["name"] = html_entity_decode($data["elements"]["products"][0]["name"]);
                @endphp
                <h1 class="product--color product--title">{{ $data["elements"]["products"][0]["name"] }}</h1>

                <div id="card-slider-product" class="splide">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @for($i = 0 ; $i < count($data["elements"]["products"][0]['images']) ; $i++)
                                <li class="splide__slide">
                                    <img src="{{ $data["elements"]["products"][0]['images'][$i] }}" class="w-100" onerror="this.src='{{ $no_img }}'" alt="" srcset="">
                                </li>
                            @endfor
                        </ul>
                    </div>
                </div>
            </div>
            <div class="product__container shadow-sm product__container--details">
                <table class="table mb-0">
                    <tbody>
                        <tr>
                            <td style="border-top: none;" class="product--color">Cantidad Envasada</td>
                            <td style="border-top: none;">{{ $data["elements"]["products"][0]["cantminvta"] == 1 ? $data["elements"]["products"][0]["cantminvta"] . " unidad" : $data["elements"]["products"][0]["cantminvta"] . " unidades"}}</td>
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
</section>