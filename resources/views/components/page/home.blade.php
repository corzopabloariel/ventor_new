@includeIf('components.page.home_newness', ['items' => $newness])

<section class="section listing-home">
    <div class="section__holder">
        <h2 class="section__title">Categorias</h2>
        <div class="categorias --horizontal">
            <div class="categorias__item">
                <ul class="categorias__item__list --horizontal">
                    @foreach($families AS $item)
                    <li style="color: {{ $item['color']['color'] }}" class="categorias__item__list__item --horizontal">
                        <a href="{{ route('products_part', ['part' => $item['slug']]) }}">
                            <img src="https://ventor.com.ar/{{ $item['icon'] }}" />
                        </a>
                        {{ $item['name'] }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>