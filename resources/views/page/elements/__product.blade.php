@if (auth()->guard('web')->check())
<tr class="product-table">
    @php
    $images = collect($product["images"])->map(function($i) {
        return asset($i);
    })->join("|");
    $bg = file_exists('http://ventor.com.ar'.$product["images"][0]) ? 'http://ventor.com.ar'.$product["images"][0] : $no_img;
    @endphp
    <td class="product-table__image" style="background-image: url({{ $bg }})">
        @if ($product["isSale"])
        <div class="product-table__liquidacion">
            <img class="product-table__image--liquidacion" src="{{ asset('images/liquidacion-producto.png') }}" data-color="{{ configs('COLOR_LIQUIDACION_ICONO') }}" alt="Liquidación" style="">
            <span style="background-color: {{ configs('COLOR_TEXTO_LIQUIDACION') }}"></span>
        </div>
        @endif
        <i data-noimg="{{ $no_img }}" data-name="{{ $product["name"] }}" data-images="{{ $images }}" class="fas fa-images product-images"></i>
    </td>
    <td class="product-table__name">
        <div>
            <div>
                @isset($product["code"])<p class="product-table__name--code"><strong>CÓDIGO:</strong> {{ $product["code"] }}</p>@endisset
                @isset($product["brand"])<p class="product-table__name--for"><strong>MARCA:</strong> {{ $product["brand"] }}</p>@endisset
                <p>{{ $product["name"] }}</p>
                <p class="product-table__name--min"><strong>U. VENTA:</strong> {{ $product["cantminvta"] }}</p>
            </div>
        </div>
        <br/>
        <table class="table table-borderless mb-0">
            <thead class="thead-light">
                @if($product["priceNumberStd"] != $product["priceNumber"])
                <th>Precio unitario</th>
                @else
                <th>Precio unitario</th>
                <th>Diferencia</th>
                <th>Precio c/ markup</th>
                @endif
                @if((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup'))
                    <th id="th--{{$product['_id']}}" class="text-white text-center {{ session()->has('cart') && isset(session()->get('cart')[$product['_id']]) ? 'bg-success' : 'bg-dark' }}" style="width: 120px;"><i class="fas fa-cart-plus"></i></th>
                @endif
            </thead>
            <tbody>
                <tr class="table-active">
                    @if($product["priceNumberStd"] != $product["priceNumber"])
                        <td style="vertical-align: middle;"><span class="product-table--price product-table--price--markup">{{ $product["price"] }}</span></td>
                    @else
                    @php
                    $priceNumberStd = $product["priceNumber"];
                    $priceNumberStd += (auth()->guard('web')->user()->discount / 100) * $priceNumberStd;
                    $priceNumberDiff = $priceNumberStd - $product["priceNumberStd"];
                    $price = "$ " . number_format($priceNumberStd, 2, ",", ".");
                    $priceDiff = "$ " . number_format($priceNumberDiff, 2, ",", ".");
                    @endphp
                    <td style="vertical-align: middle;"><span class="product-table--price">{{ $product["price"] }}</span></td>
                    <td style="vertical-align: middle;"><span class="product-table--price product-table--price--sell">+ {{ $priceDiff }}</span></td>
                    <td style="vertical-align: middle;"><span class="product-table--price product-table--price--markup">{{ $price }}</span></td>
                    @endif
                    @if((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup'))
                        <td>
                            <input data-id="{{$product['_id']}}" min="0" value="{{ session()->has('cart') && isset(session()->get('cart')[$product['_id']]) ? session()->get('cart')[$product['_id']]['quantity'] : '0' }}" step="{{$product['cantminvta']}}" type="number" class="form-control text-center cart__product__amount">
                        </td>
                    @endif
                </tr>
            </tbody>
        </table>
    </td>
    <td style="vertical-align: middle;">
        <div class="d-flex justify-content-center w-100">
            <button class="btn btn-dark button--stock" data-use="{{$product['use']}}" data-stock="{{ empty($product['stock_mini'] ) ? 0 : $product['stock_mini'] }}" type="button">
                <i class="fas fa-traffic-light"></i>
            </button>
            @if( auth()->guard('web')->user()->isShowQuantity() && !session()->has('accessADM'))
            <div class="px-3 py-2 cantidad">-</div>
            @endif
        </div>
    </td>
</tr>
@else
<div class="product">
    <a href="{{ route('product', ['product' => $product['name_slug']]) }}">
        <img src="{{ 'http://ventor.com.ar'.$product['images'][0] }}" alt="{{$product['name']}}" onerror="this.src='{{$no_img}}'" class="w-100"/>
        <p class="product--code">{{ $product["code"] }}</p>
        <p class="product--for">{{ $product["brand"] }}</p>
        <p class="product--name">{{ $product["name"] }}</p>
    </a>
</div>
@endif