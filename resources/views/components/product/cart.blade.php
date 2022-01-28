<div class="cart__product">
    <h4 class="cart__product__title">
        <div>
            {!! $product['label'] !!}
            <a target="_blank" href="{{route('product', ['product' => $product['product']])}}">{{ $product['code'] }}</a>
        </div>
        <a href="#" data-code="{{$product['product']}}" class="cart__product--remove"><i class="far fa-trash-alt"></i></a>
    </h4>
    <p class="cart__product__price">
        <span class="product product--quantity">
            <input type="number" min="0" data-code="{{ $product['product'] }}" step="{{ $product['input']['step'] }}" value="{{ $product['quantity'] }}">
        </span>
        <span class="product product--price">{{ $product['price']['unit']['string'] }}</span>
        <span class="product product--total">{{ $product['price']['total']['string'] }}</span>
    </p>
</div>