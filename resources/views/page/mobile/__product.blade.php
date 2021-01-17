<div class="product_element">
    <div class="product__image">
        @if((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup'))
        <button class="btn btn-sm {{ session()->has('cart') && isset(session()->get('cart')[$product["_id"]]) ? 'bg-success border-success' : 'btn-light' }} shadow-sm product__cart" type="button">
            <i class="fas fa-cart-plus"></i>
        </button>
        @endif
        @if ($product["isSale"])
        <div class="product--liquidacion" style="--color: {{ configs('COLOR_TEXTO_LIQUIDACION') }}">
            <img class="product--liquidacion__img" src="{{ asset('images/liquidacion-producto.png') }}" data-color="{{ configs('COLOR_LIQUIDACION_ICONO') }}" alt="Liquidación" style="">
        </div>
        @endif
        @php
        $images = collect($product["images"])->map(function($i) {
            return asset($i);
        })->join("|");
        @endphp
        <i data-noimg="{{ $no_img }}" data-name="{{ $product["name"] }}" data-images="{{ $images }}" class="fas fa-images product-images"></i>
        <img src='{{ asset("{$product["images"][0]}") }}' alt='{{$product["name"]}}' onerror="this.src='{{$no_img}}'" class='w-100'/>
    </div>
    <p class="product__name">{{ $product["name"] }}</p>
    <div class="product__price">
        @if($product["priceNumberStd"] != $product["priceNumber"])
        <span class="table__product--price">{{ $product["price"] }}</span>
        @else
        @php
        $priceNumberStd = $product["priceNumber"];
        $priceNumberStd += (auth()->guard('web')->user()->discount / 100) * $priceNumberStd;
        $priceNumberDiff = $priceNumberStd - $product["priceNumberStd"];
        $price = "$ " . number_format($priceNumberStd, 2, ",", ".");
        $priceDiff = "$ " . number_format($priceNumberDiff, 2, ",", ".");
        @endphp
        <div>
            <strike class="table__product--price-markup text-muted" title="Precio c/markup">{{ $price }}</strike>
            <span class="table__product--price">{{ $product["price"] }}</span>
        </div>
        <p class="text-right">
            <span class="table__product--price-sell text-success">+ {{ $priceDiff }}</span>
        </p>
        @endif
    </div>
    <hr>
    <p class="product__cantmin">{{ $product["cantminvta"] }}</p>
    <div class="product__stock" onclick="verificarStock(this, '{{ $product["use"] }}', {{ empty($product["stock_mini"] ) ? 0 : $product["stock_mini"] }})">
        @if( auth()->guard('web')->user()->isShowQuantity() && !session()->has('accessADM'))
        <span class="value"></span>
        @endif
    </div>
</div>