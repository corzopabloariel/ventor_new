@if ($isDesktop)
<div class="card">
    <div class="card__image">
        <div class="royalSlider rsDefault" data-p="{{$product['path']}}">
            @foreach($product['images'] AS $image)
                <div id="{{$product['path']}}" class="rsContent">
                    <a href="{{ route('product', ['product' => $product['path']]) }}">
                        <div class="card__image__photo" style="background: url('{{$image['base64'] ?? $image['url']}}') center center no-repeat; background-size: auto 100%"></div>
                        <div class="card__image__shadow"></div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <div class="card__content">

        <div class="card__content__header">
            <div class="card__content__header__data">
                <h4 class="card__title">{{ $product["code"] }}</h4>
            </div>
        </div>
        <ul class="card__highlights">
            {!! $product["brand"] !!}
        </ul>
        <p class="card__description">{{ $product["name"] }}</p>
        <div class="card__footer">
    
            <div class="card__buttons">
    
                <a href="{{ route('product', ['product' => $product['path']]) }}" class="button button--primary">
                    Ver ficha
                </a>
    
            </div>
    
        </div>

    </div>
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