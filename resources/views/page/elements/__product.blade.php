@if (auth()->guard('web')->check())
<tr>
    <td class="bg-light p-0">
        {!! $product->images(1, $no_img) !!}
    </td>
    <td>{{ $product->stmpdh_tex }}</td>
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
    <td class="bg-dark text-center border-dark">
        <button onclick="addPedido(this)" type="button" class="btn btn-secondary text-uppercase"><i class="fas fa-cart-plus"></i></button>
    </td>
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