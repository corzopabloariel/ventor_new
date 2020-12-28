@if (auth()->guard('web')->check())
<tr>
    <td class="bg-light p-0">
        {!! $product->images(1, $no_img) !!}
    </td>
    <td>
        @isset($product->stmpdh_art)<p class="mb-0 product--code">{{ $product->stmpdh_art }}</p>@endisset
        @isset($product->web_marcas)<p class="mb-0 product--for">{{ $product->web_marcas }}</p>@endisset
        <p>{{ $product->stmpdh_tex }}</p>
    </td>
    <td class="text-center">{{ $product->cantminvta }}</td>
    <td class="bg-light">
        <div class="d-flex justify-content-center w-100">
            <button class="btn btn-dark" onclick="verificarStock(this, '{{ $product->use }}', {{ empty($product->stock_mini ) ? 0 : $product->stock_mini }});" type="button">
                <i class="fas fa-traffic-light"></i>
            </button>
            @if( auth()->guard('web')->user()->isShowQuantity())
            <div class="px-3 py-2 cantidad">-</div>
            @endif
        </div>
    </td>
    <td class="text-right">{{ $product->price() }}</td>
    @if((session()->has('markup') && session()->get('markup') != "venta") || !session()->has('markup'))
    <td class="text-center {{ session()->has('cart') && isset(session()->get('cart')[$product->_id]) ? 'bg-success border-success' : 'bg-dark border-dark' }}">
        <button @if(session()->has('cart') && isset(session()->get('cart')[$product->_id])) data-quantity="{{ session()->get('cart')[$product->_id]["quantity"] }}" @endif type="button" onclick="addPedido(this, {{$product->precio}}, {{$product->cantminvta}}, {{$product->stock_mini}}, {{isset($product->max_ventas) ? $product->max_ventas : '0'}}, '{{ $product->_id }}')" type="button" class="btn btn-secondary text-uppercase addCart"><i class="fas fa-cart-plus"></i></button>
    </td>
    @endif
</tr>
@else
<div class="product">
    <a href="{{ route('product', ['product' => $product->name_slug]) }}">
        {!! $product->images(1, $no_img) !!}
        <p class="product--code">{{ $product->stmpdh_art }}</p>
        <p class="product--for">{{ $product->web_marcas }}</p>
        <p class="product--name">{{ $product->stmpdh_tex }}</p>
    </a>
</div>
@endif