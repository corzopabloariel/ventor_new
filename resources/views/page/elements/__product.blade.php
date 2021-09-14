@if (isset($application))
    <tr data-sku="{{ $application['sku'] }}">
        @php
        $bg = '';
        @endphp
        <td style="vertical-align:middle;">
            @if(isset($application['element']['C']))
                @php
                $bg = $products['C']["images"][0] ?? '';
                @endphp
                <table class="table table-sm table-striped table-borderless mb-0">
                    <tbody>
                        <tr>
                            <th style="vertical-align: middle;"><a class="text-primary" target="_blank" href="{{ route('order_search', ['search' => $application['element']['C']['code']]) }}">{{ $application['element']['C']['code'] }} <i class="fas fa-external-link-alt"></i></a></th>
                            <td style="vertical-align: middle;">
                                <div class="d-flex justify-content-center w-100">
                                    <button class="btn btn-dark button--stock" data-use="{{$products['A']['use']}}" data-stock="{{ empty($products['A']['stock_mini'] ) ? 0 : $products['A']['stock_mini'] }}" type="button">
                                        <i class="fas fa-traffic-light"></i>
                                    </button>
                                    @if( auth()->guard('web')->user()->isShowQuantity() && !session()->has('accessADM'))
                                    <div class="px-3 py-2 cantidad">-</div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @php
                        $priceNumberStd = $products['C']["priceNumber"];
                        $priceNumberStd += (auth()->guard('web')->user()->discount / 100) * $priceNumberStd;
                        $priceNumberDiff = $priceNumberStd - $products['C']["priceNumberStd"];
                        $price = "$ " . number_format($priceNumberStd, 2, ",", ".");
                        $priceDiff = "$ " . number_format($priceNumberDiff, 2, ",", ".");
                        @endphp
                        @if((session()->has('markup') && session()->get('markup') == "venta"))
                        <tr>
                            <th>Precio unitario</th>
                            <td class="text-right" style="vertical-align: middle;"><span class="product-table--price product-table--price--total">{{ $price }}</span></td>
                        </tr>
                        @else
                            <tr>
                                <th>Precio unitario</th>
                                <td class="text-right" style="vertical-align: middle;"><span class="product-table--price product-table--price--total">{{ $products['C']["price"] }}</span></td>
                            </tr>
                            <tr>
                                <th>Diferencia</th>
                                <td class="text-right" style="vertical-align: middle;"><span class="product-table--price product-table--price--sell">+ {{ $priceDiff }}</span></td>
                            </tr>
                            <tr>
                                <th>Precio c/ markup</th>
                                <td class="text-right" style="vertical-align: middle;"><span class="product-table--price product-table--price--markup">{{ $price }}</span></td>
                            </tr>
                            @if((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup'))
                            <tr class="table-active">
                                <th style="vertical-align: middle;" id="th--{{str_replace(' ', '_', $products['C']['_id'])}}" class="text-white text-center {{ isset($data['cart']['products']) && isset($data['cart']['products'][$products['C']['_id']]) ? 'bg-success' : 'bg-dark' }}" style="width: 120px;"><i class="fas fa-cart-plus"></i></th>
                                <td>
                                    <input data-id="{{$products['C']['_id']}}" @if(session()->has('accessADM')) data-username="{{session()->get('accessADM')->username}}" @endif min="0" value="{{ isset($data['cart']['products']) && isset($data['cart']['products'][$products['C']['_id']]) ? $data['cart']['products'][$products['C']['_id']]['quantity'] : '0' }}" step="{{$products['C']['cantminvta']}}" type="number" class="form-control text-center cart__product__amount">
                                </td>
                            </tr>
                            @endif
                        @endif
                    </tbody>
                </table>
            @else
            <p class="text-center">-</p>
            @endif
        </td>{{-- conductor --}}
        <td style="vertical-align:middle;">
            @if(isset($application['element']['A']))
                @php
                $bg = $products['A']["images"][0] ?? '';
                @endphp
                <table class="table table-sm table-striped table-borderless mb-0">
                    <tbody>
                        <tr>
                            <th style="vertical-align: middle;"><a class="text-primary" target="_blank" href="{{ route('order_search', ['search' => $application['element']['A']['code']]) }}">{{ $application['element']['A']['code'] }} <i class="fas fa-external-link-alt"></i></a></th>
                            <td style="vertical-align: middle;">
                                <div class="d-flex justify-content-center w-100">
                                    <button class="btn btn-dark button--stock" data-use="{{$products['A']['use']}}" data-stock="{{ empty($products['A']['stock_mini'] ) ? 0 : $products['A']['stock_mini'] }}" type="button">
                                        <i class="fas fa-traffic-light"></i>
                                    </button>
                                    @if( auth()->guard('web')->user()->isShowQuantity() && !session()->has('accessADM'))
                                    <div class="px-3 py-2 cantidad">-</div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @if($products['A']["priceNumberStd"] != $products['A']["priceNumber"])
                        <tr>
                            <th>Precio unitario</th>
                            <td style="vertical-align: middle;"><span class="product-table--price product-table--price--total">{{ $products['A']["price"] }}</span></td>
                        </tr>
                        @endif
                        @php
                        $priceNumberStd = $products['A']["priceNumber"];
                        $priceNumberStd += (auth()->guard('web')->user()->discount / 100) * $priceNumberStd;
                        $priceNumberDiff = $priceNumberStd - $products['A']["priceNumberStd"];
                        $price = "$ " . number_format($priceNumberStd, 2, ",", ".");
                        $priceDiff = "$ " . number_format($priceNumberDiff, 2, ",", ".");
                        @endphp
                        <tr>
                            <th>Precio unitario</th>
                            <td class="text-right" style="vertical-align: middle;"><span class="product-table--price product-table--price--total">{{ $products['A']["price"] }}</span></td>
                        </tr>
                        <tr>
                            <th>Diferencia</th>
                            <td class="text-right" style="vertical-align: middle;"><span class="product-table--price product-table--price--sell">+ {{ $priceDiff }}</span></td>
                        </tr>
                        <tr>
                            <th>Precio c/ markup</th>
                            <td class="text-right" style="vertical-align: middle;"><span class="product-table--price product-table--price--markup">{{ $price }}</span></td>
                        </tr>
                        @if((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup'))
                        <tr class="table-active">
                            <th style="vertical-align: middle;" id="th--{{str_replace(' ', '_', $products['A']['_id'])}}" class="text-white text-center {{ isset($data['cart']['products']) && isset($data['cart']['products'][$products['A']['_id']]) ? 'bg-success' : 'bg-dark' }}" style="width: 120px;"><i class="fas fa-cart-plus"></i></th>
                            <td>
                                <input data-id="{{$products['A']['_id']}}" @if(session()->has('accessADM')) data-username="{{session()->get('accessADM')->username}}" @endif min="0" value="{{ isset($data['cart']['products']) && isset($data['cart']['products'][$products['A']['_id']]) ? $data['cart']['products'][$products['A']['_id']]['quantity'] : '0' }}" step="{{$products['A']['cantminvta']}}" type="number" class="form-control text-center cart__product__amount">
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            @else
            <p class="text-center">-</p>
            @endif
        </td>{{-- pasajero --}}
        <td style="vertical-align:middle;">
            @if(isset($application['element']['T']))
                @php
                $bg = $products['T']["images"][0] ?? '';
                @endphp
                <table class="table table-sm table-striped table-borderless mb-0">
                    <tbody>
                        <tr>
                            <th style="vertical-align: middle;"><a class="text-primary" target="_blank" href="{{ route('order_search', ['search' => $application['element']['T']['code']]) }}">{{ $application['element']['T']['code'] }} <i class="fas fa-external-link-alt"></i></a></th>
                            <td style="vertical-align: middle;">
                                <div class="d-flex justify-content-center w-100">
                                    <button class="btn btn-dark button--stock" data-use="{{$products['T']['use']}}" data-stock="{{ empty($products['T']['stock_mini'] ) ? 0 : $products['T']['stock_mini'] }}" type="button">
                                        <i class="fas fa-traffic-light"></i>
                                    </button>
                                    @if( auth()->guard('web')->user()->isShowQuantity() && !session()->has('accessADM'))
                                    <div class="px-3 py-2 cantidad">-</div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @if($products['T']["priceNumberStd"] != $products['T']["priceNumber"])
                        <tr>
                            <th>Precio unitario</th>
                            <td style="vertical-align: middle;"><span class="product-table--price product-table--price--total">{{ $products['T']["price"] }}</span></td>
                        </tr>
                        @endif
                        @php
                        $priceNumberStd = $products['T']["priceNumber"];
                        $priceNumberStd += (auth()->guard('web')->user()->discount / 100) * $priceNumberStd;
                        $priceNumberDiff = $priceNumberStd - $products['T']["priceNumberStd"];
                        $price = "$ " . number_format($priceNumberStd, 2, ",", ".");
                        $priceDiff = "$ " . number_format($priceNumberDiff, 2, ",", ".");
                        @endphp
                        <tr>
                            <th>Precio unitario</th>
                            <td class="text-right" style="vertical-align: middle;"><span class="product-table--price product-table--price--total">{{ $products['T']["price"] }}</span></td>
                        </tr>
                        <tr>
                            <th>Diferencia</th>
                            <td class="text-right" style="vertical-align: middle;"><span class="product-table--price product-table--price--sell">+ {{ $priceDiff }}</span></td>
                        </tr>
                        <tr>
                            <th>Precio c/ markup</th>
                            <td class="text-right" style="vertical-align: middle;"><span class="product-table--price product-table--price--markup">{{ $price }}</span></td>
                        </tr>
                        @if((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup'))
                        <tr class="table-active">
                            <th style="vertical-align: middle;" id="th--{{str_replace(' ', '_', $products['T']['_id'])}}" class="text-white text-center {{ isset($data['cart']['products']) && isset($data['cart']['products'][$products['T']['_id']]) ? 'bg-success' : 'bg-dark' }}" style="width: 120px;"><i class="fas fa-cart-plus"></i></th>
                            <td>
                                <input data-id="{{$products['T']['_id']}}" @if(session()->has('accessADM')) data-username="{{session()->get('accessADM')->username}}" @endif min="0" value="{{ isset($data['cart']['products']) && isset($data['cart']['products'][$products['T']['_id']]) ? $data['cart']['products'][$products['T']['_id']]['quantity'] : '0' }}" step="{{$products['T']['cantminvta']}}" type="number" class="form-control text-center cart__product__amount">
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            @else
            <p class="text-center">-</p>
            @endif
        </td>{{-- luneta --}}
        <td>
            @if(!empty($bg))
            <img src="{{$bg}}" class="w-100" alt="" srcset="">
            @endif
        </td>{{-- image --}}
    </tr>
@else
    @if (auth()->guard('web')->check())
    <tr class="product-table">
        @php
        $images = collect($product["images"])->map(function($i) {
            return $i;
        })->join("|");
        $bg = $product["images"][0] ?? '';
        @endphp
        <td class="product-table__image" style="background-image: url({{ $bg }})">
            @if ($product["isSale"])
            <div class="product-table__liquidacion">
                <img class="product-table__image--liquidacion" src="{{ asset('images/liquidacion-producto.png') }}" data-color="{{ configs('COLOR_LIQUIDACION_ICONO') }}" alt="Liquidación" style="">
                <span style="background-color: {{ configs('COLOR_TEXTO_LIQUIDACION') }}"></span>
            </div>
            @endif
            @if (!isset($simple))
            <i data-noimg="{{ $no_img }}" onclick="showImages(this)" data-name="{{ $product["name"] }}" data-images="{{ $images }}" class="fas fa-images product-images"></i>
            @endif
        </td>
        <td class="product-table__name">
            @if (!isset($simple))
                <div>
                    <div>
                        @isset($product["code"])<p class="product-table__name--code"><strong>CÓDIGO:</strong> {{ $product["code"] }}</p>@endisset
                        @isset($product["brand"])<p class="product-table__name--for"><strong>MARCA:</strong> {{ $product['brand'] }}</p>@endisset
                        <p>{{ $product["name"] }}</p>
                        <p class="product-table__name--min"><strong>U. VENTA:</strong> {{ $product["cantminvta"] }}</p>
                    </div>
                </div>
                <br/>
            @endif
            <table class="table table-striped table-borderless mb-0">
                <thead class="thead-light">
                    @if($product["priceNumberStd"] != $product["priceNumber"])
                    <th>Precio unitario</th>
                    @else
                    <th>Precio unitario</th>
                    <th>Diferencia</th>
                    <th>Precio c/ markup</th>
                    @endif
                    @if((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup'))
                        <th id="th--{{str_replace(' ', '_', $product['_id'])}}" class="text-white text-center {{ isset($data['cart']['products']) && isset($data['cart']['products'][$product['_id']]) ? 'bg-success' : 'bg-dark' }}" style="width: 120px;"><i class="fas fa-cart-plus"></i></th>
                    @endif
                </thead>
                <tbody>
                    <tr class="table-active">
                        @if($product["priceNumberStd"] != $product["priceNumber"])
                            <td style="vertical-align: middle;"><span class="product-table--price product-table--price--total">{{ $product["price"] }}</span></td>
                        @else
                        @php
                        $priceNumberStd = $product["priceNumber"];
                        $priceNumberStd += (auth()->guard('web')->user()->discount / 100) * $priceNumberStd;
                        $priceNumberDiff = $priceNumberStd - $product["priceNumberStd"];
                        $price = "$ " . number_format($priceNumberStd, 2, ",", ".");
                        $priceDiff = "$ " . number_format($priceNumberDiff, 2, ",", ".");
                        @endphp
                        <td style="vertical-align: middle;"><span class="product-table--price product-table--price--total">{{ $product["price"] }}</span></td>
                        <td style="vertical-align: middle;"><span class="product-table--price product-table--price--sell">+ {{ $priceDiff }}</span></td>
                        <td style="vertical-align: middle;"><span class="product-table--price product-table--price--markup">{{ $price }}</span></td>
                        @endif
                        @if((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup'))
                            <td>
                                <input data-id="{{$product['_id']}}" @if(session()->has('accessADM')) data-username="{{session()->get('accessADM')->username}}" @endif min="0" value="{{ isset($data['cart']['products']) && isset($data['cart']['products'][$product['_id']]) ? $data['cart']['products'][$product['_id']]['quantity'] : '0' }}" step="{{$product['cantminvta']}}" type="number" class="form-control text-center cart__product__amount">
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
        <a href="{{ route('product', ['product' => $product['path']]) }}">
            <img src="{{ $product['images'][0] ?? '' }}" alt="{{$product['name']}}" onerror="this.src='{{$no_img}}'" class="w-100"/>
            <p class="product--code">{{ $product["code"] }}</p>
            <p class="product--for">{{ $product["brand"] }}</p>
            @if(isset($replace))
                {!! $replace['with'] !!}
            @else
                <p class="product--name">{{ $product["name"] }}</p>
            @endif
        </a>
    </div>
    @endif
@endif