@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">
    <link href="{{ asset('css/mobile/home.css') . '?t=' . time() }}" rel="stylesheet">
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js"></script>
    <script src="{{ asset('js/mobile/home.js') }}"></script>
@endpush
<section>
    <div class="home__news">
        <div class="container-fluid">
            <h2 class="home__title home__title--news">¡Novedades!</h2>
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
    </div>
    <div class="home__categories">
        <div class="container-fluid">
            <h3 class="home__title home__title--category">CATEGORIAS</h3>
            <div class="container__category">
                @foreach($data["families"] AS $item)
                <a class="family shadow-sm" style="--color: {{ $item['color']['color'] }}" href="{{ route((auth()->guard('web')->check() ? 'order_part' : 'products_part'), ['part' => $item['slug']]) }}">
                    <img src="{{ asset($item['icon']) }}" class="family__image" alt="" srcset="">
                    <p>{{ $item['name'] }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>