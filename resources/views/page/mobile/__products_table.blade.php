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
<div class="products">
    @foreach($data["elements"]["products"] AS $element)
        @include('page.mobile.__product', ['product' => $element])
    @endforeach
</div>
<div class="product__loading" style="display: none;"></div>