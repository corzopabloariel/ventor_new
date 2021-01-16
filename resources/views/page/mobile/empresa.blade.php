@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">
    <link href="{{ asset('css/mobile/empresa.css') . '?t=' . time() }}" rel="stylesheet">
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js"></script>
    <script src="{{ asset('js/mobile/empresa.js') }}"></script>
@endpush
<section class="section--no_pad">
    <div class="splide" id="splide">
        <div class="splide__track">
            <ul class="splide__list">
            @for($i = 0 ; $i < count($data['sliders']) ; $i++)
                <li class="splide__slide">
                    <img src="{{ asset($data['sliders'][$i]['image']) }}" class="w-100" onerror="this.src='{{ $no_img }}'" alt="" srcset="">
                </li>
            @endfor
            </ul>
        </div>
        
        <div class="splide__progress">
            <div class="splide__progress__bar">
            </div>
        </div>
    </div>
    <div class="empresa">
        <div class="container-fluid">
            <div class="shadow-sm empresa_container">
                {!! $data["content"]["texto"] !!}
            </div>
        </div>
    </div>
    <div class="empresa">
        <div class="container-fluid">
            <div id="card-slider" class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                    @for($i = 0; $i < count($data["content"]["anio"]); $i++)
                        <li class="splide__slide">
                            <div class="empresa__year shadow-sm">
                                {!! $data["content"]["anio"][$i]["texto"] !!}
                            </div>
                        </li>
                    @endfor
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>