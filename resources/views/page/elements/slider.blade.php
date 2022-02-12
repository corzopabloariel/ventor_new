@if (!empty($slider))
<div id="image-slider__{{ $page }}" class="swiper mySwiper">
    <div class="swiper-wrapper">
        @for($i = 0 ; $i < count($slider) ; $i++)
            <div class="swiper-slide">
                @if (!empty($slider[$i]['text']))
                <div class="swiper-text">
                    <div class="section__holder">
                        <div class="listing-lowered">
                            {!! $slider[$i]['text'] !!}
                        </div>
                    </div>
                </div>
                @endif
                <img src="{{$slider[$i]['image']}}">
            </div>
        @endfor
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>
</div>
<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper(".swiper", {
        spaceBetween: 30,
        effect: "fade",
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });
</script>
{{--<div id="slider_{{ $page }}" class="splide" data-ride="carousel">
    <ol class="carousel-indicators">
        @for($i = 0 ; $i < count($slider) ; $i++)
            <li data-target="#slider_{{ $page }}" data-slide-to="{{$i}}" @if( $i == 0 ) class="active" @endif></li>
        @endfor
    </ol>
    <div class="carousel-inner">
        @for($i = 0 ; $i < count($slider) ; $i++)
            <div class="carousel-item @if( $i == 0 ) active @endif">
                <img src="{{ asset() }}" class="w-100" onerror="this.src='{{ $no_img }}'" alt="" srcset="">
                @if (!empty($slider[$i]['text']))
                <div class="carousel-caption position-absolute w-100" style="top: 0; left: 0; z-index:0">
                    <div class="container position-relative h-100 w-100 d-flex align-items-center justify-content-start">
                        <div class="texto w-100">
                            {!! $slider[$i]['text'] !!}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        @endfor
    </div>
</div>--}}
@endif