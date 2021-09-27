@if ($isDesktop)
<div class="card">
    <a href="{{ route('product', ['product' => $product['path']]) }}">
        <img src="{{ $product['images'][0]['base64'] ?? $product['images'][0]['url'] }}" alt="{{$product['name']}}" class="w-100"/>
        <p class="product--code">{{ $product["code"] }}</p>
        <p class="product--for">{{ $product["brand"] }}</p>
        <p class="product--name">{{ $product["name"] }}</p>
    </a>
</div>
@else
<div class="product_element">
    <div class="product__image">
        @if ($product['isSale'])
        <div class="product--liquidacion" style="--color: {{ configs('COLOR_TEXTO_LIQUIDACION') }}">
            <img class="product--liquidacion__img" src="{{ asset('images/liquidacion-producto.png') }}" data-color="{{configs('COLOR_LIQUIDACION_ICONO')}}" alt="Liquidación" style="">
        </div>
        @endif
        <i data-noimg="{{ $no_img }}" onclick="showImages(this)" data-name="{{$product['name']}}" data-images="{{$product['imagesString']}}" class="fas fa-images product__images"></i>
        <img src="{{ $product['images'][0]['base64'] ?? $product['images'][0]['url'] }}" alt="{{$product['name']}}" class="w-100"/>
    </div>
    <a href="{{ route('product', ['product' => $product['path']]) }}">
        <p class="product__code">{{$product['code']}}</p>
        <p class="product__name">{{$product['name']}}</p>
        <p class="product--for">{{$product['brand']}}</p>
    </a>
</div>
@endif