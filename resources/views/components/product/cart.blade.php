<div class="cart__product">
    <h4 class="cart__product__title"><a target="_blank" href="{{route('product', ['product' => $product['product']])}}">{{ $product['product'] }}</a></h4>
    <p class="cart__product__price">
        <span class="product product--price">{{ $product['price']['unit']['string'] }}</span>
        <span class="product product--quantity">{{ $product['quantity'] }}</span>
        <span class="product product--price product--total">{{ $product['price']['total']['string'] }}</span>
    </p>
</div>