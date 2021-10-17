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

        <div class="card__cart">

            <a class="card__cart__cancel" href="#">
                <i class="fas fa-times"></i>
            </a>
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

                    <div class="card__product">
                        <span onclick="decrement(this, '{{$product['path']}}')">–</span>
                        <input data-id="{{$product['path']}}" data-min="0" value="{{ isset($data['cart']['products']) && isset($data['cart']['products'][$product['path']]) ? $data['cart']['products'][$product['path']]['quantity'] : '0' }}" data-step="{{$product['cantminvta']}}" type="text">
                        <span onclick="increment(this, '{{$product['path']}}')">+</span>
                    </div>

                    <div class="card__buttons">

                        <button data-id="{{$product['path']}}" class="button button--primary button--confirm" type="button">Agregar al pedido</button>

                    </div>
                </div>
            </div>

        </div>
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

                <a href="{{ route('product', ['product' => $product['path']]) }}" style="background-color: {{$product['family']['color']['color']}}" class="button">
                    Ver ficha
                </a>

                <button class="button button--primary button--cart"><i class="fas fa-shopping-cart"></i></button>

            </div>
    
        </div>

    </div>
</div>