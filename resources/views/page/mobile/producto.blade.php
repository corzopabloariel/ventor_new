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
                <h3 class="product--code">{{ $data["elements"]["product"]["code"] }}</h3>
                @php
                $data["elements"]["product"]["name"] = str_replace('&nbsp;', ' ', htmlentities($data["elements"]["product"]["name"]));
                $data["elements"]["product"]["name"] = html_entity_decode($data["elements"]["product"]["name"]);
                @endphp
                <h1 class="product--color product--title">{{ $data["elements"]["product"]["name"] }}</h1>

                <div id="card-slider-product" class="splide">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @for($i = 0 ; $i < count($data["elements"]["product"]['images']) ; $i++)
                                <li class="splide__slide">
                                    <img src="{{ $data["elements"]["product"]['images'][$i] }}" class="w-100" onerror="this.src='{{ $no_img }}'" alt="" srcset="">
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
                            <td style="border-top: none;">{{ $data["elements"]["product"]["cantminvta"] == 1 ? $data["elements"]["product"]["cantminvta"] . " unidad" : $data["elements"]["product"]["cantminvta"] . " unidades"}}</td>
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
</section>