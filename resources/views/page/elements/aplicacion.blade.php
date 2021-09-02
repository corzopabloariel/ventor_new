@push('styles')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
@endpush
@push('js')
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
};
</script>
@endpush
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
<img src="http://staticbcp.ventor.com.ar/img/parabrisas.jpg" alt="" srcset="" class="w-100">
<div class="wrapper wrapper__application">
    <section>
        <div class="container">
            <h2 class="title">Limpiaparabrisas</h2>
            <div class="row">
                <div class="col-12 col-md">
                    <select name="brand" id="brandList" class="form-control" @if(isset($data['models']) && isset($data['years'])) disabled @endif>
                        <option value="">Seleccione marca</option>
                        {!! $data['brandsOptions'] !!}
                    </select>
                </div>
                <div class="col-12 col-md">
                    <select name="model" id="modelList" class="form-control" disabled>
                        <option value="">Seleccione modelo</option>
                        {!! $data['models']['dataOptions'] ?? '' !!}
                    </select>
                </div>
                <div class="col-12 col-md">
                    <select name="year" id="yearList" class="form-control" @if(!isset($data['year']) || (isset($data['year']) && !empty($data['year']))) disabled @endif>
                        <option value="">Seleccione año</option>
                        {!! $data['years']['dataOptions'] ?? '' !!}
                    </select>
                </div>
            </div>
            <div class="row mt-4 mb-5">
                <div class="col d-flex justify-content-end">
                    @if(isset($data['models']) && isset($data['years']))
                    <a href="{{ URL::to('aplicacion') }}" class="btn btn-lg btn-dark mr-3">Resetear</a>
                    @endif
                    <button type="button" id="btnListApplication" @if(!isset($data['year']) || (isset($data['year']) && !empty($data['year']))) disabled @endif class="btn btn-lg btn-primary">Buscar</button>
                </div>
            </div>
            @isset($data["products"])
            @auth
            <div class="container--table">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th class="th--image"></th>
                                <th class="th--name">Producto</th>
                                <th class="th--venta">Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data["products"] AS $element)
                                @foreach($element->data AS $e)
                                    @include('page.elements.__product', ['product' => $e, 'replace' => ['attr' => 'name', 'with' => "<hr/><p><strong>{$element->title}</strong></p>{$element->description}<hr/>"]])
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endauth
            @guest
            <div class="container__products" id="product-main">
                @foreach($data["products"] AS $element)
                    @foreach($element->data AS $e)
                        @include('page.elements.__product', ['product' => $e, 'replace' => ['attr' => 'name', 'with' => "<hr/><p><strong>{$element->title}</strong></p>{$element->description}<hr/>"]])
                    @endforeach
                @endforeach
            </div>
            @endguest
            @endisset
        </div>
    </section>
</div>