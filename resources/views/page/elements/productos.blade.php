@push('styles')
    <link href="{{ asset('css/page/productos.css') }}" rel="stylesheet">
@endpush
<section>
    <div class="productos">
        <div class="container">
            <h3 class="productos--title">CATEGORIAS</h3>
            <div class="container--productos">
                @foreach($data["families"] AS $item)
                <div class="family">
                    <a href="{{ route((auth()->guard('web')->check() ? 'order_part' : 'part'), ['part' => $item['slug']]) }}">
                        <img src="{{ asset($item['icon']) }}" alt="" srcset="">
                        <p>{{ $item['name'] }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>