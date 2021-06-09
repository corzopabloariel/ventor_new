<section>
    <div class="wrapper container-fluid">
        <h3 class="products--title">CATEGORIAS</h3>
        <div class="container__category">
            @foreach($data["families"] AS $item)
            <a class="family shadow-sm" style="--color: {{ $item['color']['color'] }}" href="{{ route((auth()->guard('web')->check() ? 'order_part' : 'products_part'), ['part' => $item['slug']]) }}">
                <img src="{{ asset($item['icon']) }}" class="family__image" alt="" srcset="">
                <p>{{ $item['name'] }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>