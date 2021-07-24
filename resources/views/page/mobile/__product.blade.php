<div class="product_element">
    <div class="product__image">
        @auth('web')
            @if((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup') && !isset($checkout))
            <button data-id="{{ $product["_id"] }}" class="btn btn-sm {{ isset($data['cart']['products']) && isset($data['cart']['products'][$product['_id']]) ? 'btn-success' : '' }} shadow-sm product__cart" type="button">
                <i class="fas fa-cart-plus"></i>
            </button>
            @endif
        @endauth
        @if ($product["isSale"])
        <div class="product--liquidacion" style="--color: {{ configs('COLOR_TEXTO_LIQUIDACION') }}">
            <img class="product--liquidacion__img" src="{{ asset('images/liquidacion-producto.png') }}" data-color="{{ configs('COLOR_LIQUIDACION_ICONO') }}" alt="Liquidación" style="">
        </div>
        @endif
        @php
        $images = collect($product["images"])->map(function($i) {
            return $i;
        })->join("|");
        @endphp
        <i data-noimg="{{ $no_img }}" onclick="showImages(this)" data-name="{{ $product["name"] }}" data-images="{{ $images }}" class="fas fa-images product__images"></i>
        <img src='{{ $product["images"][0] }}' alt='{{$product["name"]}}' onerror="this.src='{{$no_img}}'" class='w-100'/>
    </div>
    @auth('web')
        <input data-id="{{ $product["_id"] }}" @if(isset($data['cart']['products']) && isset($data['cart']['products'][$product['_id']])) value="{{$data['cart']['products'][$product["_id"]]["quantity"]}}" @endif placeholder="Ingrese cantidad" style="display: none;" step="{{ $product["cantminvta"] }}" min="{{ $product["cantminvta"] }}" type="number" class="form-control text-center product__quantity">
        <p class="product__code">{{ $product["code"] }}</p>
        @php
        $product["name"] = str_replace('&nbsp;', ' ', htmlentities($product["name"]));
        $product["name"] = html_entity_decode($product["name"]);
        @endphp
        <p class="product__name">{!! $product["name"] !!}</p>
    @endauth
    @unless (Auth::check())
        <a href="{{ route('product', ['product' => $product["name_slug"]]) }}">
            <p class="product__code">{{ $product["code"] }}</p>
            <p class="product__name">{{ $product["name"] }}</p>
        </a>
    @endunless
    @auth('web')
    <div class="product__price">
        @if($product["priceNumberStd"] != $product["priceNumber"])
            <p class="text-right">
                <span class="table__product--price">{{ $product["price"] }}</span>
            </p>
            @else
            @php
            $priceNumberStd = $product["priceNumber"];
            $priceNumberStd += (auth()->guard('web')->user()->discount / 100) * $priceNumberStd;
            $priceNumberDiff = $priceNumberStd - $product["priceNumberStd"];
            $price = "$ " . number_format($priceNumberStd, 2, ",", ".");
            $priceDiff = "$ " . number_format($priceNumberDiff, 2, ",", ".");
            @endphp
            @if(!isset($checkout))
            <p class="text-right">
                <strike class="table__product--price-markup text-muted" title="Precio c/markup">{{ $price }}</strike>
            </p>
            @endif
            <p class="text-right" data-price="{{ $product["price"] }}" data-pricenumber="{{ $product["priceNumber"] }}">
                @if(isset($data['cart']['products']) && isset($data['cart']['products'][$product['_id']]))
                <small class="table__product--price text-muted">{{ $product["price"] }} x {{ $data['cart']['products'][$product["_id"]]["quantity"] }}</small><br/>
                @php
                $priceNumber = $product["priceNumber"];
                $priceNumber *= $data['cart']['products'][$product["_id"]]["quantity"];
                $price = "$ " . number_format($priceNumber, 2, ",", ".");
                @endphp
                <span class="table__product--price">{{ $price }}</span>
                @else
                <span class="table__product--price">{{ $product["price"] }}</span>
                @endif
            </p>
            @if(!isset($checkout))
            <p class="text-right">
                <span class="table__product--price-sell text-success">+ {{ $priceDiff }}</span>
                <small class="text-muted">{{ auth()->guard('web')->user()->discount }}%</small>
            </p>
            @endif
        @endif
    </div>
    <hr>
    <p class="product__cantmin">{{ $product["cantminvta"] }}</p>
    <div class="product__stock" data-use="{{$product['use']}}" data-stock="{{ empty($product['stock_mini'] ) ? 0 : $product['stock_mini'] }}">
        @if( auth()->guard('web')->user()->isShowQuantity() && !session()->has('accessADM'))
        <span class="value"></span>
        @endif
    </div>
    @endauth
</div>