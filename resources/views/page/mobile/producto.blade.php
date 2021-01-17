@push('styles')
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">
    <link href="{{ asset('css/mobile/product.css') . '?t=' . time() }}" rel="stylesheet">
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{ asset('js/alertify.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/color.js') }}"></script>
    <script src="{{ asset('js/solver.js') }}"></script>
    <script src="{{ asset('js/mobile/product.js') . '?t=' . time() }}"></script>
@endpush
@includeIf('page.mobile.__filter', ['elements' => $data["lateral"]])
<section class="section--no_pad">
    <div class="product">
        <div class="container-fluid">
            <div class="product__container product__container--filter shadow-sm text-truncate" id="btn-filter">
                <i class="fas fa-filter"></i>
                @if(isset($data["elements"]["part"]) || isset($data["elements"]["subpart"]))
                Parte y subparte: <span class="text-uppercase">{{$data["elements"]["part"]["name"]}}</span>
                @isset($data["elements"]["subpart"])
                | {{ $data["elements"]["subpart"]["name"] }}
                @endisset
                @else
                filtrar
                @endif
            </div>
            <div class="product__container shadow-sm">
                <h3 class="product--code">{{ $data["elements"]["product"]["code"] }}</h3>
                <h1 class="product--color product--title">{{ $data["elements"]["product"]["name"] }}</h1>

                <div id="card-slider" class="splide">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @for($i = 0 ; $i < count($data["elements"]["product"]['images']) ; $i++)
                                <li class="splide__slide">
                                    <img src="{{ asset($data["elements"]["product"]['images'][$i]) }}" class="w-100" onerror="this.src='{{ $no_img }}'" alt="" srcset="">
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