@push('styles')
    <link href="{{ asset('css/mobile/home.css') . '?t=' . time() }}" rel="stylesheet">
@endpush
<section class="section--no_pad">
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