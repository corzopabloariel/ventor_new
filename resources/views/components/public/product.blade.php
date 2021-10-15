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
                <span class="card__application"><i class="fab fa-elementor"></i> {{ implode(', ', $product['application']) }}</span>
            </div>
        </div>
        <ul class="card__highlights">
            @if ($product["isSale"])
            <li class="card__highlights__item card__highlights__item--special">EN LIQUIDACIÓN</li>
            @endif
            {!! $product["brand"] !!}
        </ul>
        <p class="card__description">{{ $product["name"] }}</p>
        <div class="card__footer">

            <div class="card__price">

                <span class="card__price__actual card__price__aux" data-code="{{$product['path']}}"></span>

            </div>
    
            <div class="card__buttons">
    
                <a href="{{ route('product', ['product' => $product['path']]) }}" class="button button--primary">
                    Ver ficha
                </a>
    
            </div>
    
        </div>

    </div>
</div>