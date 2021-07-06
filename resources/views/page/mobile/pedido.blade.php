@push('modal')
<div class="modal fade bd-example-modal-lg" id="imagesProductModal" role="dialog" aria-labelledby="imagesProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagesProductModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="--swiper-navigation-color: #000; --swiper-pagination-color: #000" class="swiper-container swiperBig">
                    <div class="swiper-wrapper"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
                <div thumbsSlider="" class="swiper-container swiperThumbs">
                    <div class="swiper-wrapper"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endpush
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
@endpush
@push('js')
<script src="{{ asset('js/color.js') }}"></script>
<script src="{{ asset('js/solver.js') }}"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
const showImages = function(t) {
    let swiper__thumbs = document.querySelector('.swiperThumbs');
    let swiper__big = document.querySelector('.swiperBig');
    let images = t.dataset.images.split("|");
    let name = t.dataset.name;
    let noimg = t.dataset.noimg;
    document.querySelector("#imagesProductModalLabel").textContent = name;
    /////
    images.map((i, index) => {
        swiper__thumbs.querySelector('.swiper-wrapper').innerHTML += `<div class="swiper-slide"><img src="${i}" onerror="this.src='${noimg}'" alt="${name}"/></div>`;
        swiper__big.querySelector('.swiper-wrapper').innerHTML += `<div class="swiper-slide"><img src="${i}" onerror="this.src='${noimg}'" alt="${name}"/></div>`;
    });
    $("#imagesProductModal").modal("show");
    $('#imagesProductModal').on('shown.bs.modal', function (e) {
        window.swiperThumbs = new Swiper(swiper__thumbs, {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
        });
        window.swiperBig = new Swiper(swiper__big, {
            spaceBetween: 10,
            loop: true,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            thumbs: {
                swiper: window.swiperThumbs,
            },
        });
    });
    $('#imagesProductModal').on('hidden.bs.modal', function (e) {
        document.querySelector("#imagesProductModalLabel").textContent = '';
        if (window.swiperThumbs) {
            window.swiperThumbs.destroy();
            window.swiperBig.destroy();
            swiper__thumbs.querySelector('.swiper-wrapper').innerHTML = '';
            swiper__big.querySelector('.swiper-wrapper').innerHTML = '';
            swiper__big.setAttribute('style', '--swiper-navigation-color: #000; --swiper-pagination-color: #000');
        }
    });
}
</script>
@endpush
@includeIf('page.mobile.__filter', ['elements' => $data["lateral"]])
<section>
    <div class="product">
        <div class="container-fluid">
            @isset($data["clients"])
            <div class="product__container product__container--client shadow-sm">
                <select id="clientList" class="form-control">
                    <option value="">Seleccione cliente</option>
                    @foreach($data["clients"] AS $client)
                    @php
                    $selected = "";
                    if (session()->has('nrocta_client') && session()->get('nrocta_client') == $client->nrocta)
                        $selected = "selected=true";
                    @endphp
                    <option {{ $selected }} value="{{ $client->nrocta }}">{{ $client->nrocta }} | {{ $client->razon_social }} @if(!empty($client->direml))({{ $client->direml }})@endif</option>
                    @endforeach
                </select>
            </div>
            @endisset
            <div class="product__container product__container--filter shadow-sm text-truncate" id="btn-filter">
                <i class="fas fa-filter"></i>
                @if(isset($data["elements"]["part"]) || isset($data["elements"]["subpart"]))
                filtro aplicado: <span class="text-uppercase">{{$data["elements"]["part"]["name"]}}</span>
                @isset($data["elements"]["subpart"])
                | {{ $data["elements"]["subpart"]["name"] }}
                @endisset
                @else
                filtrar
                @endif
            </div>
            <div class="product__container product__container--btns shadow-sm">
                <button data-filter="nuevos" type="button" class="btn py-2 px-4 type__product @if(session()->has('type') && session()->get('type') == 'nuevos') btn-dark @else btn-light @endif border-0">NUEVOS</button>
                <button data-filter="liquidacion" type="button" class="btn py-2 px-4 type__product @if(session()->has('type') && session()->get('type') == 'liquidacion') btn-dark @else btn-light @endif border-0">EN LIQUIDACIÓN</button>
                <div class="price__type">
                    <input id="input-costo" @if((session()->has('markup') && session()->get('markup') == "costo") || !session()->has('markup')) checked @endif class="form-check-input changeMarkUp" data-type="costo" type="radio" name="markup">
                    <label for="input-costo">
                        COSTO
                    </label>
                </div>
                <div class="price__type">
                    <input id="input-venta" @if(session()->has('markup') && session()->get('markup') == "venta") checked @endif class="form-check-input changeMarkUp" data-type="venta" type="radio" name="markup">
                    <label for="input-venta">
                        VENTA
                    </label>
                </div>
            </div>
            @include('page.mobile.__products_table')
            @if ($data["elements"]["products"]->total() == 0)
                @include('page.elements.__not_found')
            @else
            <div class="mt-3">
                <div class="table-responsive">
                    {{ $data["elements"]["products"]->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</section>