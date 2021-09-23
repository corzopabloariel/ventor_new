@if (isset($application))
    <tr data-sku="{{ $application['sku'] }}">
        @php
        $bg = '';
        if(isset($application['element']['C']) && !empty($products['C']))
            $bg = $products['C']["images"][0] ?? '';
        if(isset($application['element']['A']) && !empty($products['A']))
            $bg = $products['A']["images"][0] ?? '';
        if(isset($application['element']['T']))
            $bg = $products['T']["images"][0] ?? '';
        @endphp
        <td>
            @if(!empty($bg))
            @php
            $bg = str_replace(' ', '%20', $bg);
            $type = pathinfo($bg, PATHINFO_EXTENSION);// Por ahora son todos JPG
            $bg = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($bg));
            @endphp
            <img src="{{$bg}}" class="w-100" alt="" srcset="">
            @endif
            <div style="line-height: normal;">
                <p>{{$application->title}}</p>
            </div>
            @if(isset($application['element']['C']) && !empty($products['C']))
                <table class="table table-sm table-striped table-borderless mb-0">
                    <thead>
                        <th colspan="2" class="th--venta">Conductor</th>
                    </thead>
                    <tbody>
                        <tr>
                            <th style="vertical-align: middle;"><a class="text-primary" target="_blank" href="{{ route('order_search', ['search' => $application['element']['C']['code']]) }}?one=1">{{ $application['element']['C']['code'] }} <i class="fas fa-external-link-alt"></i></a></th>
                            <td style="vertical-align: middle;">
                                <div class="d-flex justify-content-center w-100">
                                    @if((session()->has('markup') && session()->get('markup') == "venta"))
                                        <button type="button" data-unique="{{$application->_id}}::{{$application['element']['C']['code']}}" class="btn btn-info text-white button--budget" title="Agregar al presupuesto">
                                            <i class="fas fa-vote-yea"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-dark button--stock" data-use="{{$products['C']['use']}}" data-stock="{{ empty($products['C']['stock_mini'] ) ? 0 : $products['C']['stock_mini'] }}" type="button">
                                            <i class="fas fa-traffic-light"></i>
                                        </button>
                                        @if( auth()->guard('web')->user()->isShowQuantity() && !session()->has('accessADM'))
                                        <div class="px-3 py-2 cantidad">-</div>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @php
                        $priceNumberStd = $products['C']["priceNumber"];
                        $priceNumberStd += (auth()->guard('web')->user()->discount / 100) * $priceNumberStd;
                        $priceNumberStd = round($priceNumberStd, 2);
                        $priceNumberDiff = $priceNumberStd - $products['C']["priceNumberStd"];
                        $price = "$ " . number_format($priceNumberStd, 2, ",", ".");
                        $priceDiff = "$ " . number_format($priceNumberDiff, 2, ",", ".");
                        @endphp
                        @if((session()->has('markup') && session()->get('markup') == "venta"))
                        <tr>
                            <script>
                                products[{{$loop->index}}].element.C.price = "{{$priceNumberStd}}";
                            </script>
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
            @endif
            @if(isset($application['element']['A']) && !empty($products['A']))
                <table class="table table-sm table-striped table-borderless mb-0">
                    <thead>
                        <th colspan="2" class="th--venta">Pasajero</th>
                    </thead>
                    <tbody>
                        <tr>
                            <th style="vertical-align: middle;"><a class="text-primary" target="_blank" href="{{ route('order_search', ['search' => $application['element']['A']['code']]) }}?one=1">{{ $application['element']['A']['code'] }} <i class="fas fa-external-link-alt"></i></a></th>
                            <td style="vertical-align: middle;">
                                <div class="d-flex justify-content-center w-100">
                                    @if((session()->has('markup') && session()->get('markup') == "venta"))
                                        <button type="button" data-unique="{{$application->_id}}::{{$application['element']['A']['code']}}" class="btn btn-info text-white button--budget" title="Agregar al presupuesto">
                                            <i class="fas fa-vote-yea"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-dark button--stock" data-use="{{$products['A']['use']}}" data-stock="{{ empty($products['A']['stock_mini'] ) ? 0 : $products['A']['stock_mini'] }}" type="button">
                                            <i class="fas fa-traffic-light"></i>
                                        </button>
                                        @if( auth()->guard('web')->user()->isShowQuantity() && !session()->has('accessADM'))
                                        <div class="px-3 py-2 cantidad">-</div>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @php
                        $priceNumberStd = $products['A']["priceNumber"];
                        $priceNumberStd += (auth()->guard('web')->user()->discount / 100) * $priceNumberStd;
                        $priceNumberStd = round($priceNumberStd, 2);
                        $priceNumberDiff = $priceNumberStd - $products['A']["priceNumberStd"];
                        $price = "$ " . number_format($priceNumberStd, 2, ",", ".");
                        $priceDiff = "$ " . number_format($priceNumberDiff, 2, ",", ".");
                        @endphp
                        @if((session()->has('markup') && session()->get('markup') == "venta"))
                            <tr>
                                <script>
                                    products[{{$loop->index}}].element.A.price = "{{$priceNumberStd}}";
                                </script>
                                <th>Precio unitario</th>
                                <td class="text-right" style="vertical-align: middle;"><span class="product-table--price product-table--price--total">{{ $price }}</span></td>
                            </tr>
                        @else
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
                        @endif
                    </tbody>
                </table>
            @endif
            @if(isset($application['element']['T']))
                <table class="table table-sm table-striped table-borderless mb-0">
                    <thead>
                        <th colspan="2" class="th--venta">Luneta</th>
                    </thead>
                    <tbody>
                        <tr>
                            <th style="vertical-align: middle;"><a class="text-primary" target="_blank" href="{{ route('order_search', ['search' => $application['element']['T']['code']]) }}?one=1">{{ $application['element']['T']['code'] }} <i class="fas fa-external-link-alt"></i></a></th>
                            <td style="vertical-align: middle;">
                                <div class="d-flex justify-content-center w-100">
                                    @if((session()->has('markup') && session()->get('markup') == "venta"))
                                        <button type="button" data-unique="{{$application->_id}}::{{$application['element']['T']['code']}}" class="btn btn-info text-white button--budget" title="Agregar al presupuesto">
                                            <i class="fas fa-vote-yea"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-dark button--stock" data-use="{{$products['T']['use']}}" data-stock="{{ empty($products['T']['stock_mini'] ) ? 0 : $products['T']['stock_mini'] }}" type="button">
                                            <i class="fas fa-traffic-light"></i>
                                        </button>
                                        @if( auth()->guard('web')->user()->isShowQuantity() && !session()->has('accessADM'))
                                        <div class="px-3 py-2 cantidad">-</div>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @php
                        $priceNumberStd = $products['T']["priceNumber"];
                        $priceNumberStd += (auth()->guard('web')->user()->discount / 100) * $priceNumberStd;
                        $priceNumberStd = round($priceNumberStd, 2);
                        $priceNumberDiff = $priceNumberStd - $products['T']["priceNumberStd"];
                        $price = "$ " . number_format($priceNumberStd, 2, ",", ".");
                        $priceDiff = "$ " . number_format($priceNumberDiff, 2, ",", ".");
                        @endphp
                        @if((session()->has('markup') && session()->get('markup') == "venta"))
                        <tr>
                            <script>
                                products[{{$loop->index}}].element.T.price = "{{$priceNumberStd}}";
                            </script>
                            <th>Precio unitario</th>
                            <td class="text-right" style="vertical-align: middle;"><span class="product-table--price product-table--price--total">{{ $price }}</span></td>
                        </tr>
                        @else
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
                        @endif
                    </tbody>
                </table>
            @endif
        </td>
    </tr>
@else
    @php
    $cart = isset($cart) ? $cart : (isset($data['cart']) ? $data['cart'] : []);
    @endphp
    <div class="product_element">
        @if(isset($checkout))
            <span class="product__delete" data-id="{{ $product["_id"] }}">[Eliminar]</span>
        @endif
        <div class="product__image">
            @auth('web')
                @if((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup') && !isset($checkout))
                <button data-id="{{ $product["_id"] }}" class="btn btn-sm {{ isset($cart['products']) && isset($cart['products'][$product['_id']]) ? 'btn-success' : '' }} shadow-sm product__cart" type="button">
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
                $i = str_replace(' ', '%20', $i);
                $type = pathinfo($i, PATHINFO_EXTENSION);// Por ahora son todos JPG
                return 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($i));
            })->toArray();
            $bg = $images[0] ?? '';
            $images = implode('|', $images);
            @endphp
            <i data-noimg="{{ $no_img }}" onclick="showImages(this)" data-name="{{ $product["name"] }}" data-images="{{ $images }}" class="fas fa-images product__images"></i>
            <img src='{{ $bg }}' alt='{{$product["name"]}}' onerror="this.src='{{$no_img}}'" class='w-100'/>
        </div>
        @auth('web')
            <input data-id="{{ $product["_id"] }}" @if(isset($cart['products']) && isset($cart['products'][$product['_id']])) value="{{$cart['products'][$product["_id"]]["quantity"]}}" @endif placeholder="Ingrese cantidad" style="display: none;" step="{{ $product["cantminvta"] }}" min="{{ $product["cantminvta"] }}" type="number" class="form-control text-center product__quantity">
            <p class="product__code">{{ $product["code"] }}</p>
            @php
            $product["name"] = str_replace('&nbsp;', ' ', htmlentities($product["name"]));
            $product["name"] = html_entity_decode($product["name"]);
            @endphp
            <p class="product__name">{!! $product["name"] !!}</p>
            <p class="product__name product__name--brand">{{ $product["brand"] }}</p>
        @endauth
        @unless (Auth::check())
            <a href="{{ route('product', ['product' => $product['path']]) }}">
                <p class="product__code">{{ $product["code"] }}</p>
                <p class="product__name">{{ $product["name"] }}</p>
                <p class="product--for">{{ $product["brand"] }}</p>
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
                    @if(isset($cart['products']) && isset($cart['products'][$product['_id']]))
                    <small class="table__product--price text-muted">{{ $product["price"] }} x {{ $cart['products'][$product["_id"]]["quantity"] }}</small><br/>
                    @php
                    $priceNumber = $product["priceNumber"];
                    $priceNumber *= $cart['products'][$product["_id"]]["quantity"];
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
@endif