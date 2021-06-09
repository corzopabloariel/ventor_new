@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js"></script>
    <script src="{{ asset('js/mobile/home.js') }}"></script>
@endpush
<section>
    <div class="wrapper container">
        <h2 class="news--title">¡Novedades!</h2>
        <div class="container__news">
            <div id="card-slider" class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                    @foreach($data['newness'] AS $item)
                        @php
                        $filename = public_path() . "/{$item['file']}";
                        @endphp
                        <li class="splide__slide">
                            <a @if(!empty($item['file']) && file_exists($filename)) href="{{ asset($item['file']) }}" download @endif class="new--download">
                                <div class="splide__slide__container">
                                    <img src="{{ asset($item['image']) }}" class="w-100" onerror="this.src='{{ $no_img }}'" alt="{{ $item['name'] }}">
                                </div>
                                <h5 class="text-center mb-0">{{ $item['name'] }}</h5>
                            </a>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@includeIf('page.mobile.productos')