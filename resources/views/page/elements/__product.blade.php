@if (auth()->guard('web')->check())
<tr>
    <td class="bg-light p-0 position-relative">
        @if ($product["isSale"])
        <div class="product--liquidacion" style="--color: {{ configs('COLOR_TEXTO_LIQUIDACION') }}">
            <img class="product--liquidacion__img" src="{{ asset('images/liquidacion-producto.png') }}" data-color="{{ configs('COLOR_LIQUIDACION_ICONO') }}" alt="Liquidación" style="">
        </div>
        @endif
        <img src='{{ asset("{$product["images"][0]}") }}' alt='{{$product["name"]}}' onerror="this.src='{{$no_img}}'" class='w-100'/>
    </td>
    <td>
        @isset($product["code"])<p class="mb-0 product--code">{{ $product["code"] }}</p>@endisset
        @isset($product["brand"])<p class="mb-0 product--for">{{ $product["brand"] }}</p>@endisset
        <p>{{ $product["name"] }}</p>
    </td>
    <td class="text-center">{{ $product["cantminvta"] }}</td>
    <td class="bg-light">
        <div class="d-flex justify-content-center w-100">
            <button class="btn btn-dark" onclick="verificarStock(this, '{{ $product["use"] }}', {{ empty($product["stock_mini"] ) ? 0 : $product["stock_mini"] }});" type="button">
                <i class="fas fa-traffic-light"></i>
            </button>
            @if( auth()->guard('web')->user()->isShowQuantity())
            <div class="px-3 py-2 cantidad">-</div>
            @endif
        </div>
    </td>
    <td class="text-right">{{ $product["price"] }}</td>
    @if((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup'))
    <td class="text-center {{ session()->has('cart') && isset(session()->get('cart')[$product["_id"]]) ? 'bg-success border-success' : 'bg-dark border-dark' }}">
        <button data-id="{{$product["_id"]}}" @if(session()->has('cart') && isset(session()->get('cart')[$product["_id"]])) data-quantity="{{ session()->get('cart')[$product["_id"]]["quantity"] }}" @endif type="button" onclick="addPedido(this, {{$product["priceNumber"]}}, {{$product["cantminvta"]}}, {{$product["stock_mini"]}}, {{isset($product["cantminvta"]) ? $product["cantminvta"] : '0'}}, '{{ $product["_id"] }}')" type="button" class="btn btn-secondary text-uppercase addCart"><i class="fas fa-cart-plus"></i></button>
    </td>
    @endif
</tr>
@else
<div class="product">
    <a href="{{ route('product', ['product' => $product["name_slug"]]) }}">
        <img src='{{ asset("{$product["images"][0]}") }}' alt='{{$product["name"]}}' onerror="this.src='{{$no_img}}'" class='w-100'/>
        <p class="product--code">{{ $product["code"] }}</p>
        <p class="product--for">{{ $product["brand"] }}</p>
        <p class="product--name">{{ $product["name"] }}</p>
    </a>
</div>
@endif