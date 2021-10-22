<div class="card {{ $cart ? '--order' : ''}}">
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

            <a class="card__cart__cancel" data-code="{{$product['path']}}" href="#">
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
                        <input data-code="{{$product['path']}}" data-min="0" value="{{$cart ? $cart['quantity'] : '0'}}" data-step="{{$product['cantminvta']}}" type="text">
                        <span onclick="increment(this, '{{$product['path']}}')">+</span>
                    </div>

                    <div class="card__buttons">

                        <button data-code="{{$product['path']}}" data-order="{{$cart ? 1 : 0}}" class="button button--primary button--confirm" type="button">{{ $cart ? 'Modificar el pedido' : 'Agregar al pedido' }}</button>

                    </div>
                </div>
            </div>

        </div>
        <div class="card__content__header">
            <div class="card__content__header__data">
                <h4 class="card__title">{{ $product["code"] }}</h4>
                <span class="card__application"><i class="fab fa-elementor"></i> {{ implode(', ', $product['application']) }}</span>
            </div>
            <div class="card__content__header__stock">
                <button class="button button--primary-outline-grey button--stock" data-code="{{ $product['code'] }}"><i class="fas fa-traffic-light"></i></button>
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

            <div class="card__buttons cart__primary">

                <a href="{{ route('product', ['product' => $product['path']]) }}" style="background-color: {{$product['family']['color']['color']}}" class="button">
                    Ver ficha
                </a>

                @if (!isset($markup) || (isset($markup) && $markup == 'costo'))
                    <button class="button button--primary button--cart" data-code="{{$product['path']}}">{!!$cart ? $cart['quantity'] : '<i class="fas fa-shopping-cart"></i>'!!}</button>
                @endif

            </div>
    
        </div>

    </div>
</div>