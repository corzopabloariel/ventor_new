<div class="card-app">

    <div class="card-app__content">
        <p class="card-app__title">{{$application['title']}}</p>
    </div>
    <div class="card-app__content">
        @if(!empty($application['image']))
        <img alt="{{$application['title']}}" class="card-app__image" loading="lazy" src="{{$application['image']}}">
        @endif
    </div>

</div>
@isset($application['products'])
    @foreach($application['products'] AS $product)
        @includeIf('components.public.product', [
            'product'   => $product['product'],
            'markup'    => $markup,
            'cart'      => $dataCartProducts ? collect($dataCartProducts['element'])->firstWhere('product', $product['product']['path']) : null,
            'tag'       => $product['type']
        ])
    @endforeach
@endisset